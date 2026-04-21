<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAssessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use LdapRecord\Laravel\LdapRecord;
use LdapRecord\Ldap;
use LdapRecord\Container;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Connection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Courses;
use App\Models\Faculty;
use App\Models\Assessment;
use App\Models\CourseAssessor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }

    public function checkLogin(Request $request)
    {
        // 1. Validation (คงเดิม)
        $request->validate([
            'email' => ['required', 'string'],
            'password' => 'required|min:6',
        ]);

        $input = $request->email;

        // 2. Security Check (คงเดิม)
        if (str_contains($input, '@') && !str_ends_with($input, '@go.buu.ac.th')) {
            return back()->withErrors(['email' => 'ไม่อนุญาตให้ใช้ Domain ภายนอก'])->withInput();
        }

        $username = explode('@', $input)[0];
        $ldapUsername = 'BUU\\' . $username;

        try {
            // 3. LDAP Connection & Binding
            $connection = new Connection([
                'hosts' => [config('app.ldap_host_2')],
                'base_dn' => config('app.ldap_base_dn'),
                'username' => $ldapUsername,
                'password' => $request->password,
                'port' => config('app.ldap_port'),
            ]);

            $connection->connect();
            $connection->auth()->bind();

            // 4. ค้นหาข้อมูลจาก LDAP
            $ldapUser = $connection->query()->where('samaccountname', '=', $username)->first();

            if (!$ldapUser) {
                return back()->withErrors(['email' => 'ไม่พบข้อมูลในระบบมหาวิทยาลัย']);
            }

            $userEmail = $ldapUser['mail'][0] ?? $username . '@go.buu.ac.th';
            $displayName = $ldapUser['displayname'][0] ?? $username;

            // 5. ค้นหาในฐานข้อมูล Local
            $userEmail = $ldapUser['mail'][0] ?? $username . '@go.buu.ac.th';
            $displayName = $ldapUser['displayname'][0] ?? $username;

            $userNormal = User::where('email', $userEmail)->first();
            $userAssessor = UserAssessor::where('email', $userEmail)->first();

            if ($userAssessor) {
                // แก้ไข: อัปเดตชื่อเฉพาะกรณีที่ใน DB ยังไม่มีชื่อ (เป็น null หรือว่าง)
                if (empty($userAssessor->name)) {
                    $userAssessor->name = $displayName;
                    $userAssessor->save();
                }

                Auth::guard('assessor_guard')->login($userAssessor);
                session(['login_type' => 'assessor']);

            } elseif ($userNormal) {
                // แก้ไข: อัปเดตชื่อเฉพาะกรณีที่ใน DB ยังไม่มีชื่อ
                if (empty($userNormal->name)) {
                    $userNormal->name = $displayName;
                    $userNormal->save();
                }

                Auth::guard('web')->login($userNormal);
                session(['login_type' => 'user']);

            } else {
                // กรณีสร้างใหม่ (ยังไงก็ต้องใช้ชื่อจาก LDAP)
                $newUser = User::create([
                    'email' => $userEmail,
                    'name' => $displayName,
                    'role' => 'user',
                    'status' => 'active',
                ]);
                Auth::guard('web')->login($newUser);
                session(['login_type' => 'user']);
            }
            return redirect('/home');
        } catch (\LdapRecord\Auth\BindException $e) {
            return back()->withErrors(['email' => 'รหัสผ่านไม่ถูกต้อง'])->withInput();
        } catch (\Exception $e) {
            logger('Error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'ระบบขัดข้อง: ' . $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'prefix' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'subject_group' => 'nullable|string|max:100',
            'faculty' => 'nullable|string|max:100',
            'course' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:10',
        ]);

        // เอา email จาก user ที่ login แทน
        $email = Auth::user()->email;

        // อัปเดตหรือสร้าง record
        User::updateOrCreate(
            ['email' => $email],
            [
                'prefix' => $request->prefix,
                'name' => $request->name,
                'subject_group' => $request->subject_group,
                'faculty' => $request->faculty,
                'course' => $request->course,
                'phone_number' => $request->phone_number,
            ]
        );

        return redirect('/home')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('excel_file');

        // โหลดไฟล์ Excel
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // ข้าม row แรก (header)
        foreach ($rows as $index => $row) {
            if ($index === 0)
                continue;
            $email = trim($row[5] ?? '');
            if ($email == '') {
                continue;
            }
            $phone = $row[6] ?? null;

            if ($phone) {
                $phone = preg_replace('/[^0-9]/', '', $phone);
            }
            User::updateOrCreate(
                ['email' => $email], // ใช้ email เป็น unique key
                [
                    'prefix' => $row[0] ?? null,
                    'name' => $row[1] ?? null,
                    'subject_group' => $row[2] ?? null,
                    'faculty' => $row[3] ?? null,
                    'course' => $row[4] ?? null,
                    'phone_number' => $phone,
                ]
            );
        }

        return redirect()->route('user')->with('success', 'นำเข้าข้อมูลสำเร็จ');
    }
    public function importassessor(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $existingInSystem = []; // เก็บรายชื่อที่ซ้ำในตาราง users

        foreach ($rows as $index => $row) {
            if ($index === 0)
                continue;

            $email = trim($row[6] ?? '');
            if ($email == '')
                continue;

            // 1. ตรวจสอบในตาราง users (ตารางหลัก)
            $userInMainTable = User::where('email', $email)->first();

            if ($userInMainTable) {
                // ห้ามแก้ไขบรรทัดนี้ตามคำขอ
                $existingInSystem[] = "{$userInMainTable->name} ({$userInMainTable->role})";
                continue; // ข้ามการ Insert/Update ของคนนี้ไป
            }

            // 2. ถ้าไม่ซ้ำในตารางหลัก ให้ทำการ Update หรือ Create ในตาราง Assessor ตามปกติ
            $phone = $row[7] ?? null;
            if ($phone) {
                $phone = preg_replace('/[^0-9]/', '', $phone);
            }

            UserAssessor::updateOrCreate(
                ['email' => $email],
                [
                    'code_assessor' => $row[0] ?? null,
                    'prefix' => $row[1] ?? null,
                    'name' => $row[2] ?? null,
                    'subject_group' => $row[3] ?? null,
                    'faculty' => $row[4] ?? null,
                    'course' => $row[5] ?? null,
                    'phone_number' => $phone,
                    'training_type' => $row[8] ?? null,
                ]
            );
        }

        // 3. จัดการการแจ้งเตือน
        if (count($existingInSystem) > 0) {
            // ปรับการจัดรูปแบบข้อความให้อ่านง่ายด้วย HTML List
            $htmlList = "<ul style='text-align: left; margin-left: 20px;'>";
            foreach ($existingInSystem as $item) {
                $htmlList .= "<li>" . $item . "</li>";
            }
            $htmlList .= "</ul>";

            return redirect()->route('assessor')->with('warning_list', $htmlList);
        }

        return redirect()->route('assessor')->with('success', 'นำเข้าข้อมูลสำเร็จ');
    }
    public function templateUser()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // HEADER
        $sheet->setCellValue('A1', 'คำนำหน้า');
        $sheet->setCellValue('B1', 'ชื่อ-สกุล');
        $sheet->setCellValue('C1', 'กลุ่มวิชา');
        $sheet->setCellValue('D1', 'คณะ');
        $sheet->setCellValue('E1', 'หลักสูตร');
        $sheet->setCellValue('F1', 'Email');
        $sheet->setCellValue('G1', 'เบอร์โทร');

        $writer = new Xlsx($spreadsheet);

        $fileName = 'users_template.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        $writer->save("php://output");
        exit;
    }
    public function templateAssessor()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // HEADER
        $sheet->setCellValue('A1', 'Code Assessor');
        $sheet->setCellValue('B1', 'คำนำหน้า');
        $sheet->setCellValue('C1', 'ชื่อ-สกุล');
        $sheet->setCellValue('D1', 'กลุ่มวิชา');
        $sheet->setCellValue('E1', 'คณะ');
        $sheet->setCellValue('F1', 'หลักสูตร');
        $sheet->setCellValue('G1', 'Email');
        $sheet->setCellValue('H1', 'เบอร์โทร');
        $sheet->setCellValue('I1', 'Training type');

        $writer = new Xlsx($spreadsheet);

        $fileName = 'assessor_template.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        $writer->save("php://output");
        exit;
    }
    public function templateFaculty()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // HEADER
        $sheet->setCellValue('A1', 'คณะ');
        $sheet->setCellValue('B1', 'ระดับการศึกษา (1-3)');
        $sheet->setCellValue('C1', 'รหัสหลักสูตร');
        $sheet->setCellValue('D1', 'ชื่อหลักสูตร');
        $sheet->setCellValue('E1', 'วิทยาเขต');

        $writer = new Xlsx($spreadsheet);

        $fileName = 'faculty_template.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        $writer->save("php://output");
        exit;
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('edituser', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'prefix' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'faculty' => 'nullable|string|max:100',
            'subject_group' => 'nullable|string|max:100',
            'course' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:50',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $user = User::findOrFail($id);
            if ($request->role === 'assessor') {
                // 1. สร้างข้อมูลใหม่ในตาราง users_assessor
                UserAssessor::updateOrCreate([
                    'code_assessor' => $request->code_assessor,
                    'prefix' => $request->assessor_prefix,
                    'name' => $request->name,
                    'subject_group' => $request->subject_group,
                    'faculty' => $request->faculty,
                    'course' => $request->course,
                    'role' => 'assessor',
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'status' => $request->status ?? 'active',
                    'assessor_type' => 'junior',
                    'training_type' => $request->training_type,
                ]);
                // 2. ลบข้อมูลจากตาราง users
                $user->delete();
                return redirect()->route('user')->with('success', 'ย้ายข้อมูลไปยังกลุ่มผู้ประเมินเรียบร้อยแล้ว');
            } else {
                // อัปเดตข้อมูลปกติในตารางเดิม
                $user->update([
                    'prefix' => $request->prefix,
                    'name' => $request->name,
                    'faculty' => $request->faculty,
                    'subject_group' => $request->subject_group,
                    'course' => $request->course,
                    'phone_number' => $request->phone_number,
                    'email' => $request->email,
                    'status' => $request->status,
                    'role' => $request->role,
                ]);

                return redirect()->route('user')->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
            }
        });
    }
    public function importFaculty(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('excel_file');

        // โหลดไฟล์ Excel
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // ข้าม row แรก (header)
        foreach ($rows as $index => $row) {
            if ($index === 0)
                continue; // ข้าม header

            $facultyName = trim($row[0] ?? '');
            $level = trim($row[1] ?? '');
            $code = trim($row[2] ?? '');
            $courseName = trim($row[3] ?? '');
            $campus = trim($row[4] ?? '');
            $name = trim($row[0] ?? '');
            if ($name === '') {
                // ข้ามแถวที่ไม่มีชื่อ
                continue;
            }
            // หาคณะก่อน ถ้าไม่มีให้สร้าง
            $faculty = Faculty::firstOrCreate(
                ['name' => $facultyName],
                ['campus' => $campus]
            );

            // เพิ่ม/อัปเดตหลักสูตร
            Courses::updateOrCreate(
                ['code' => $code],
                [
                    'faculty_id' => $faculty->id,
                    'name' => $courseName,
                    'level' => $level
                ]
            );
        }
        return redirect()->route('listfaculty')->with('success', 'นำเข้าข้อมูลสำเร็จ');
    }
    public function collectFaculty(Request $request)
    {
        $faculty = Faculty::where('name', $request->faculty)->first();
        $course = Courses::find($request->course_id);
        $user = Auth::user();

        // เพิ่มส่วนนี้เพื่อดึงข้อมูลเดิมมาแสดง
        $existingAssessment = Assessment::where('name', $user->name)
            ->where('faculty', $faculty->name)
            ->where('courses', $course->name)
            ->first();

        // ดึงข้อมูล assessor (ถ้าต้องใช้เช็คสิทธิ์ canEdit ในหน้า save)
        $courseAssessor = CourseAssessor::where('faculty_id', $faculty->id)
            ->where('course_id', $course->id)
            ->first();

        return view('save', compact('faculty', 'course', 'existingAssessment', 'courseAssessor'));
    }
    public function collect(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'courses' => 'required|string',
            'criterion' => 'required|string',
            'faculty' => 'required|string',
            'result' => 'required|string',
            'strength' => 'required|string',
            'improvement' => 'required|string',
            'score' => 'nullable|array',
            'overall' => 'required|array',
        ]);
        // บันทึกข้อมูลลงฐานข้อมูล
        Assessment::updateOrCreate(
            [
                'name' => $request->name,
                'faculty' => $request->faculty,
                'courses' => $request->courses,
            ],
            [
                // 2. ข้อมูลที่ต้องการบันทึก/อัปเดต
                'result' => $request->result,
                'criterion' => $request->criterion,
                'strength' => $request->strength,
                'improvement' => $request->improvement,
                'score' => $request->score,
                'overall' => $request->overall,
            ]
        );
        return redirect()->route('home')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function index()
    {
        $data = DB::table('faculty')
            ->leftJoin('assessment', 'faculty.name', '=', 'assessment.faculty')
            ->select(
                'faculty.id',
                'faculty.name',
                DB::raw('COUNT(assessment.id) as total_courses'),
                DB::raw("SUM(CASE WHEN assessment.result = 'เป็นไปตามเกณฑ์' THEN 1 ELSE 0 END) as passed"),
                DB::raw("SUM(CASE WHEN assessment.result = 'ไม่เป็นไปตามเกณฑ์' THEN 1 ELSE 0 END) as failed")
            )
            ->groupBy('faculty.id', 'faculty.name')
            ->get();

        return view('report', compact('data'));
    }
    public function homePage()
    {
        return view('home');
    }
    public function userPage()
    {
        return view('user');
    }
    public function listassessorPage()
    {
        return view('listassessor');
    }
    public function facultyPage()
    {
        return view('faculty ');
    }
    public function universityPage()
    {
        return view('university');
    }
    public function listnamePage()
    {
        return view('listname');
    }
    public function recordPage()
    {
        return view('record');
    }

    public function resultsPage()
    {
        $course_assessment = CourseAssessor::all();
        $users = UserAssessor::all()->keyBy('name');
        return view('results', compact('course_assessment', 'users'));
    }
    public function savePage(Request $request)
    {
        $faculty = Faculty::find($request->faculty_id);
        $course = Courses::find($request->course_id);
        $user = Auth::user();

        $courseAssessor = CourseAssessor::where('faculty_id', $request->faculty_id)
            ->where('course_id', $request->course_id)
            ->first();

        // ดึงข้อมูลเดิมจากฐานข้อมูล (ถ้ามี) โดยเช็คจาก ชื่อ, คณะ, และหลักสูตร
        $existingAssessment = Assessment::where('name', $user->name)
            ->where('faculty', $faculty->name)
            ->where('courses', $course->name)
            ->first();
        return view('save', compact('faculty', 'user', 'course', 'courseAssessor', 'existingAssessment'));
    }
    public function edituserPage()
    {
        return view('edituser');
    }
    public function userfillPage()
    {
        return view('userfill');
    }
    public function assessorPage()
    {
        return view('assessor');
    }
    public function editassessorPage($id)
    {
        $userassessor = UserAssessor::findOrFail($id);
        return view('editassessor', compact('userassessor'));
    }
    public function updateassessor(Request $request, $id)
    {
        $request->validate([
            'prefix' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'faculty' => 'nullable|string|max:100',
            'subject_group' => 'nullable|string|max:100',
            'course' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:50',
            'code_assessor' => 'nullable|string|max:100',
            'assessor_type' => 'nullable|string|max:100',
            'training_type' => 'nullable|string|max:100',
        ]);

        $user = UserAssessor::findOrFail($id);

        $user->update([
            'prefix' => $request->prefix,
            'name' => $request->name,
            'faculty' => $request->faculty,
            'subject_group' => $request->subject_group,
            'course' => $request->course,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'status' => $request->status ?? $user->status,
            'role' => $request->role ?? $user->role,
            'code_assessor' => $request->code_assessor,
            'assessor_type' => $request->assessor_type,
            'training_type' => $request->training_type,
        ]);
        return redirect()->route('assessor');
    }
    public function listfacultyPage(Request $request)
    {
        $selectedThaiYear = $request->thai_year ?? (date('Y') + 543);

        $selectedADYear = $selectedThaiYear - 543;

        $query = Faculty::whereYear('created_at', $selectedADYear);

        // filter campus
        if ($request->campus) {
            $query->where('campus', $request->campus);
        }

        $faculties = $query->get();

        return view('listfaculty', compact('faculties', 'selectedThaiYear'));
    }
    public function listcoursePage(Request $request)
    {
        $selectedThaiYear = $request->thai_year ?? (date('Y') + 543);

        $selectedADYear = $selectedThaiYear - 543;

        $query = Faculty::with('courses')->whereYear('created_at', $selectedADYear);

        // filter campus
        if ($request->campus) {
            $query->where('campus', $request->campus);
        }

        $faculties = $query->get();

        return view('listcourse', compact('faculties', 'selectedThaiYear'));
    }
    public function updateStatus(Request $request)
    {
        $course = Courses::find($request->id);
        $course->status = $request->status;
        $course->save();

        return response()->json(['success' => true]);
    }
    public function reportPage(Request $request)
    {
        $accessFile = Storage::get('report_access.json');
        $publishedRoles = $accessFile ? json_decode($accessFile, true) : [];
        $yearReport1 = $request->year_report1 ?? (date('Y') + 543);
        $yearReport2 = $request->year_report2 ?? (date('Y') + 543);
        $yearReport3 = $request->year_report3 ?? (date('Y') + 543);
        $yearReport4 = $request->year_report4 ?? (date('Y') + 543);
        $yearReport5 = $request->year_report5 ?? (date('Y') + 543);
        $yearReport6 = $request->year_report6 ?? (date('Y') + 543);
        // แปลงเป็น ค.ศ.
        $yearReport1AD = $yearReport1 - 543;
        $yearReport2AD = $yearReport2 - 543;
        $yearReport3AD = $yearReport3 - 543;
        $yearReport4AD = $yearReport4 - 543;
        $yearReport5AD = $yearReport5 - 543;
        $yearReport6AD = $yearReport6 - 543;
        $faculties = $this->getReportData($yearReport1AD);
        $assessment = Assessment::whereYear('created_at', $yearReport2AD)->get(); //2
        $assessment2 = Assessment::whereYear('created_at', $yearReport3AD)->get(); //3
        $assessment3 = Assessment::whereYear('created_at', $yearReport4AD)->get(); //4
        $assessment4 = Assessment::whereYear('created_at', $yearReport5AD)->get(); //5
        $assessment5 = Assessment::whereYear('created_at', $yearReport6AD)->get(); //6

        $ft = Faculty::with('courses')->withCount('courses')->get();
        $assess = Assessment::all();
        $totalRecords = $assess->count();
        $stats = [];
        for ($i = 0; $i < 8; $i++) {
            for ($level = 1; $level <= 5; $level++) {
                $stats[$i][$level] = 0;
            }
        }

        foreach ($assess as $item) {
            $overall = is_array($item->overall) ? $item->overall : json_decode($item->overall, true);

            if ($overall) {
                foreach ($overall as $index => $score) {
                    $level = (int) $score;
                    if ($level >= 1 && $level <= 5) {
                        $stats[$index][$level]++;
                    }
                }
            }
        }

        return view('report', compact(
            'faculties',
            'ft',
            'stats',
            'totalRecords',
            'assessment',
            'assessment2',
            'assessment3',
            'assessment4',
            'assessment5',
            'yearReport1',
            'yearReport2',
            'yearReport3',
            'yearReport4',
            'yearReport5',
            'yearReport6',
            'publishedRoles'
        ));
    }
    private function getReportData($year)
    {
        $faculties = Faculty::all()->map(function ($faculty) use ($year) {

            // จำนวนหลักสูตร
            $coursesCount = Courses::where('faculty_id', $faculty->id)
                ->whereYear('created_at', $year)
                ->count();

            // ผ่านเกณฑ์
            $totalPass = Assessment::where('faculty', $faculty->name)
                ->where('criterion', 'เป็นไปตามเกณฑ์')
                ->whereYear('created_at', $year)
                ->count();

            // ไม่ผ่านเกณฑ์
            $totalFail = Assessment::where('faculty', $faculty->name)
                ->where('criterion', 'ไม่เป็นไปตามเกณฑ์')
                ->whereYear('created_at', $year)
                ->count();

            $faculty->courses_count = $coursesCount;
            $faculty->total_pass = $totalPass;
            $faculty->total_fail = $totalFail;

            return $faculty;
        });

        return $faculties;
    }
    public function exportExcel(Request $request)
    {
        $selectedThaiYear = $request->year_report1 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = $this->getReportData($selectedADYear);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('F1:F2');
        $sheet->mergeCells('D1:E1');
        $sheet->setCellValue('A1', 'ที่');
        $sheet->setCellValue('B1', 'ส่วนงานคณะ/วิทยาลัย');
        $sheet->setCellValue('C1', 'จำนวนหลักสูตร');
        $sheet->setCellValue('D1', 'ผลการประเมินองค์ประกอบที่ 1 (การกำกับมาตรฐาน)');
        $sheet->setCellValue('F1', 'หมายเหตุ');
        $sheet->setCellValue('D2', 'เป็นไปตามเกณฑ์');
        $sheet->setCellValue('E2', 'ไม่เป็นไปตามเกณฑ์');
        $sheet->getStyle('A1:F2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:F2')->getAlignment()->setVertical('center');
        $sheet->getStyle('A1:F2')->getFont()->setBold(true);

        $row = 3;
        if ($faculties->sum('courses_count') == 0) {
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->setCellValue("A{$row}", 'ไม่มีข้อมูล');
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal('center');
        } else {
            foreach ($faculties as $index => $faculty) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $faculty->name);
                $sheet->setCellValue('C' . $row, $faculty->courses_count ?: '-');
                $sheet->setCellValue('D' . $row, $faculty->total_pass ?: '-');
                $sheet->setCellValue('E' . $row, $faculty->total_fail ?: '-');
                $row++;
            }

            $sheet->setCellValue('B' . $row, 'รวม');
            $sheet->setCellValue('C' . $row, $faculties->sum('courses_count'));
            $sheet->setCellValue('D' . $row, $faculties->sum('total_pass'));
            $sheet->setCellValue('E' . $row, $faculties->sum('total_fail'));
        }
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set(
            'Content-Type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        $response->headers->set(
            'Content-Disposition',
            'attachment;filename="report_' . $selectedThaiYear . '.xlsx"'
        );

        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
    public function exportPDF(Request $request)
    {
        $selectedThaiYear = $request->year_report1 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = $this->getReportData($selectedADYear);

        $pdf = SnappyPdf::loadView('reportpdf', [
            'faculties' => $faculties,
            'selectedThaiYear' => $selectedThaiYear
        ])
            ->setOption('encoding', 'utf-8')
            ->setOption('enable-local-file-access', true);

        return $pdf->download('report_' . $selectedThaiYear . '.pdf');
    }
    public function exportExcelReport2(Request $request)
    {
        $selectedThaiYear = $request->year_report2 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- HEADER ROW 1 ---
        $sheet->setCellValue('A1', 'ที่');
        $sheet->mergeCells('A1:A3');
        $sheet->setCellValue('B1', 'ส่วนงานคณะ/วิทยาลัย');
        $sheet->mergeCells('B1:B3');

        $groups = [
            'C' => 'ภาพรวมทั้งคณะ/วิทยาลัย',
            'H' => 'ระดับปริญญาตรี',
            'M' => 'ระดับปริญญาโท',
            'R' => 'ระดับปริญญาเอก'
        ];

        foreach ($groups as $col => $title) {
            $sheet->setCellValue($col . '1', $title);
            $endCol = chr(ord($col) + 4);
            $sheet->mergeCells("{$col}1:{$endCol}1");

            // ROW 2: จำนวนหลักสูตร & Overall Verdict
            $sheet->setCellValue($col . '2', "จำนวน\nหลักสูตร");
            $sheet->mergeCells($col . '2:' . $col . '3');

            $verdictStart = chr(ord($col) + 1);
            $verdictEnd = chr(ord($col) + 4);
            $sheet->setCellValue($verdictStart . '2', 'Overall Verdict');
            $sheet->mergeCells("{$verdictStart}2:{$verdictEnd}2");

            // ROW 3: 2, 3, 4, 5
            $headers = ['2', '3', '4', '5'];
            foreach ($headers as $i => $h) {
                $sheet->setCellValue(chr(ord($verdictStart) + $i) . '3', $h);
            }
        }

        $row = 4;
        if ($assessment->count() == 0) {
            $sheet->mergeCells("A{$row}:V{$row}");
            $sheet->setCellValue("A{$row}", 'ไม่มีข้อมูล');
        } else {
            foreach ($faculties as $index => $faculty) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $faculty->name);

                // Logic แยก Level เหมือนใน Blade
                $levels = [
                    'overall' => ['courses' => $faculty->courses, 'col' => 'C'],
                    'l1' => ['courses' => $faculty->courses->where('level', '1'), 'col' => 'H'],
                    'l2' => ['courses' => $faculty->courses->where('level', '2'), 'col' => 'M'],
                    'l3' => ['courses' => $faculty->courses->where('level', '3'), 'col' => 'R'],
                ];

                foreach ($levels as $key => $data) {
                    $courseNames = $data['courses']->pluck('name')->toArray();
                    $filteredAssess = $assessment->where('faculty', $faculty->name);

                    // ถ้าไม่ใช่ overall ให้กรองด้วย whereIn names
                    if ($key !== 'overall') {
                        $filteredAssess = $filteredAssess->whereIn('courses', $courseNames);
                    }

                    $sheet->setCellValue($data['col'] . $row, $data['courses']->count());
                    $sheet->setCellValue(chr(ord($data['col']) + 1) . $row, $filteredAssess->where('result', '2')->count() ?: '-');
                    $sheet->setCellValue(chr(ord($data['col']) + 2) . $row, $filteredAssess->where('result', '3')->count() ?: '-');
                    $sheet->setCellValue(chr(ord($data['col']) + 3) . $row, $filteredAssess->where('result', '4')->count() ?: '-');
                    $sheet->setCellValue(chr(ord($data['col']) + 4) . $row, $filteredAssess->where('result', '5')->count() ?: '-');
                }
                $row++;
            }

            // --- แถวรวม (TOTAL) ---
            $sheet->setCellValue('A' . $row, 'รวม (จำนวนหลักสูตร)');
            $sheet->mergeCells("A{$row}:B{$row}");

            $totalSections = [
                'C' => ['level' => null, 'assess' => $assessment],
                'H' => ['level' => '1', 'assess' => null],
                'M' => ['level' => '2', 'assess' => null],
                'R' => ['level' => '3', 'assess' => null],
            ];

            foreach ($totalSections as $col => $config) {
                $allNames = $faculties->flatMap->courses;
                if ($config['level']) {
                    $allNames = $allNames->where('level', $config['level']);
                }
                $namesArray = $allNames->pluck('name')->toArray();
                $currentAssess = $config['assess'] ?? $assessment->whereIn('courses', $namesArray);

                $sheet->setCellValue($col . $row, count($namesArray));
                $sheet->setCellValue(chr(ord($col) + 1) . $row, $currentAssess->where('result', '2')->count());
                $sheet->setCellValue(chr(ord($col) + 2) . $row, $currentAssess->where('result', '3')->count());
                $sheet->setCellValue(chr(ord($col) + 3) . $row, $currentAssess->where('result', '4')->count());
                $sheet->setCellValue(chr(ord($col) + 4) . $row, $currentAssess->where('result', '5')->count());
            }
            $totalRow = $row; // เก็บเลขแถวไว้ทำ Percent
            $row++;

            // --- แถวเปอร์เซ็นต์ (%) ---
            $sheet->setCellValue('A' . $row, 'เปอร์เซนต์ (%)');
            $sheet->mergeCells("A{$row}:B{$row}");

            foreach (['C', 'H', 'M', 'R'] as $col) {
                $totalCount = $sheet->getCell($col . $totalRow)->getValue();
                $sheet->setCellValue($col . $row, 100);

                for ($i = 1; $i <= 4; $i++) {
                    $val = $sheet->getCell(chr(ord($col) + $i) . $totalRow)->getValue();
                    $percent = $totalCount > 0 ? round(($val / $totalCount) * 100, 2) : 0;
                    $sheet->setCellValue(chr(ord($col) + $i) . $row, $percent);
                }
            }
        }

        // Styling เบื้องต้นให้ดูง่าย
        $sheet->getStyle('A1:V' . $row)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:V3')->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="report2_' . $selectedThaiYear . '.xlsx"');

        return $response;
    }
    public function exportPDFReport2(Request $request)
    {
        $selectedThaiYear = $request->year_report2 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $allCourseNamesByLevel = [
            '1' => $faculties->flatMap->courses->where('level', '1')->pluck('name')->toArray(),
            '2' => $faculties->flatMap->courses->where('level', '2')->pluck('name')->toArray(),
            '3' => $faculties->flatMap->courses->where('level', '3')->pluck('name')->toArray(),
        ];

        $pdf = SnappyPdf::loadView('report2pdf', [
            'faculties' => $faculties,
            'assessment' => $assessment,
            'selectedThaiYear' => $selectedThaiYear,
            'allCourseNamesByLevel' => $allCourseNamesByLevel
        ]);

        return $pdf->download('report2_' . $selectedThaiYear . '.pdf');
    }
    public function exportExcelReport3(Request $request)
    {
        $selectedThaiYear = $request->year_report3 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        // ดึง ID ของหลักสูตรที่เป็นการตรวจแบบที่ 1 (เหมือนใน Blade)
        $type1CourseIds = CourseAssessor::where('assessment_type', '1')
            ->pluck('course_id')
            ->toArray();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- การตั้งค่า Header (ปรับตามโครงสร้าง Blade) ---
        // แถวที่ 1
        $sheet->setCellValue('A1', 'ที่');
        $sheet->setCellValue('B1', 'ส่วนงานคณะ/วิทยาลัย');
        $sheet->setCellValue('C1', 'ภาพรวมทั้งคณะ/วิทยาลัย');
        $sheet->mergeCells('C1:G1');
        $sheet->setCellValue('H1', 'ระดับปริญญาตรี');
        $sheet->mergeCells('H1:L1');
        $sheet->setCellValue('M1', 'ระดับปริญญาโท');
        $sheet->mergeCells('M1:Q1');
        $sheet->setCellValue('R1', 'ระดับปริญญาเอก');
        $sheet->mergeCells('R1:V1');

        // แถวที่ 2
        $sheet->setCellValue('C2', 'จำนวนหลักสูตร');
        $sheet->mergeCells('D2:G2');
        $sheet->setCellValue('D2', 'Overall Verdict');
        $sheet->setCellValue('H2', 'จำนวนหลักสูตร');
        $sheet->mergeCells('I2:L2');
        $sheet->setCellValue('I2', 'Overall Verdict');
        $sheet->setCellValue('M2', 'จำนวนหลักสูตร');
        $sheet->mergeCells('N2:Q2');
        $sheet->setCellValue('N2', 'Overall Verdict');
        $sheet->setCellValue('R2', 'จำนวนหลักสูตร');
        $sheet->mergeCells('S2:V2');
        $sheet->setCellValue('S2', 'Overall Verdict');

        // แถวที่ 3 (หัวข้อตัวเลข 2 3 4 5)
        $subHeaders = ['2', '3', '4', '5'];
        foreach ($subHeaders as $i => $h) {
            $sheet->setCellValue(chr(68 + $i) . '3', $h); // D, E, F, G
            $sheet->setCellValue(chr(73 + $i) . '3', $h); // I, J, K, L
            $sheet->setCellValue(chr(78 + $i) . '3', $h); // N, O, P, Q
            $sheet->setCellValue(chr(83 + $i) . '3', $h); // S, T, U, V
        }

        $row = 4;
        if ($assessment->count() == 0) {
            $sheet->mergeCells("A{$row}:V{$row}");
            $sheet->setCellValue("A{$row}", 'ไม่มีข้อมูล');
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        } else {
            foreach ($faculties as $index => $faculty) {
                $coursesType1 = $faculty->courses->whereIn('id', $type1CourseIds);

                $level1Names = $coursesType1->where('level', '1')->pluck('name')->toArray();
                $level2Names = $coursesType1->where('level', '2')->pluck('name')->toArray();
                $level3Names = $coursesType1->where('level', '3')->pluck('name')->toArray();

                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $faculty->name);

                // --- ภาพรวมคณะ ---
                $sheet->setCellValue('C' . $row, $coursesType1->count() ?: '-');
                $sheet->setCellValue('D' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $coursesType1->pluck('name'))->where('result', '2')->count() ?: '-');
                $sheet->setCellValue('E' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $coursesType1->pluck('name'))->where('result', '3')->count() ?: '-');
                $sheet->setCellValue('F' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $coursesType1->pluck('name'))->where('result', '4')->count() ?: '-');
                $sheet->setCellValue('G' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $coursesType1->pluck('name'))->where('result', '5')->count() ?: '-');

                // --- ปริญญาตรี (Level 1) ---
                $sheet->setCellValue('H' . $row, count($level1Names) ?: '-');
                $sheet->setCellValue('I' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level1Names)->where('result', '2')->count() ?: '-');
                $sheet->setCellValue('J' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level1Names)->where('result', '3')->count() ?: '-');
                $sheet->setCellValue('K' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level1Names)->where('result', '4')->count() ?: '-');
                $sheet->setCellValue('L' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level1Names)->where('result', '5')->count() ?: '-');

                // --- ปริญญาโท (Level 2) ---
                $sheet->setCellValue('M' . $row, count($level2Names) ?: '-');
                $sheet->setCellValue('N' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level2Names)->where('result', '2')->count() ?: '-');
                $sheet->setCellValue('O' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level2Names)->where('result', '3')->count() ?: '-');
                $sheet->setCellValue('P' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level2Names)->where('result', '4')->count() ?: '-');
                $sheet->setCellValue('Q' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level2Names)->where('result', '5')->count() ?: '-');

                // --- ปริญญาเอก (Level 3) ---
                $sheet->setCellValue('R' . $row, count($level3Names) ?: '-');
                $sheet->setCellValue('S' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level3Names)->where('result', '2')->count() ?: '-');
                $sheet->setCellValue('T' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level3Names)->where('result', '3')->count() ?: '-');
                $sheet->setCellValue('U' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level3Names)->where('result', '4')->count() ?: '-');
                $sheet->setCellValue('V' . $row, $assessment->where('faculty', $faculty->name)->whereIn('courses', $level3Names)->where('result', '5')->count() ?: '-');

                $row++;
            }

            // --- แถวรวม (Total) ---
            $allCoursesType1 = $faculties->flatMap->courses->whereIn('id', $type1CourseIds);
            $allLevel1Names = $allCoursesType1->where('level', '1')->pluck('name')->toArray();
            $allLevel2Names = $allCoursesType1->where('level', '2')->pluck('name')->toArray();
            $allLevel3Names = $allCoursesType1->where('level', '3')->pluck('name')->toArray();
            $allCourseNames = $allCoursesType1->pluck('name')->toArray();
            $sheet->setCellValue('A' . $row, 'รวม (จำนวนหลักสูตร)');
            $sheet->mergeCells('A' . $row . ':B' . $row);
            $sheet->setCellValue('C' . $row, $allCoursesType1->count() ?: '0');
            $sheet->setCellValue('D' . $row, $assessment->whereIn('courses', $allCourseNames)->where('result', '2')->count() ?: '0');
            $sheet->setCellValue('E' . $row, $assessment->whereIn('courses', $allCourseNames)->where('result', '3')->count() ?: '0');
            $sheet->setCellValue('F' . $row, $assessment->whereIn('courses', $allCourseNames)->where('result', '4')->count() ?: '0');
            $sheet->setCellValue('G' . $row, $assessment->whereIn('courses', $allCourseNames)->where('result', '5')->count() ?: '0');
            $sheet->setCellValue('H' . $row, count($allLevel1Names) ?: '0');
            $sheet->setCellValue('I' . $row, $assessment->whereIn('courses', $allLevel1Names)->where('result', '2')->count() ?: '0');
            $sheet->setCellValue('J' . $row, $assessment->whereIn('courses', $allLevel1Names)->where('result', '3')->count() ?: '0');
            $sheet->setCellValue('K' . $row, $assessment->whereIn('courses', $allLevel1Names)->where('result', '4')->count() ?: '0');
            $sheet->setCellValue('L' . $row, $assessment->whereIn('courses', $allLevel1Names)->where('result', '5')->count() ?: '0');
            $sheet->setCellValue('M' . $row, count($allLevel2Names) ?: '0');
            $sheet->setCellValue('N' . $row, $assessment->whereIn('courses', $allLevel2Names)->where('result', '2')->count() ?: '0');
            $sheet->setCellValue('O' . $row, $assessment->whereIn('courses', $allLevel2Names)->where('result', '3')->count() ?: '0');
            $sheet->setCellValue('P' . $row, $assessment->whereIn('courses', $allLevel2Names)->where('result', '4')->count() ?: '0');
            $sheet->setCellValue('Q' . $row, $assessment->whereIn('courses', $allLevel2Names)->where('result', '5')->count() ?: '0');
            $sheet->setCellValue('R' . $row, count($allLevel3Names) ?: '0');
            $sheet->setCellValue('S' . $row, $assessment->whereIn('courses', $allLevel3Names)->where('result', '2')->count() ?: '0');
            $sheet->setCellValue('T' . $row, $assessment->whereIn('courses', $allLevel3Names)->where('result', '3')->count() ?: '0');
            $sheet->setCellValue('U' . $row, $assessment->whereIn('courses', $allLevel3Names)->where('result', '4')->count() ?: '0');
            $sheet->setCellValue('V' . $row, $assessment->whereIn('courses', $allLevel3Names)->where('result', '5')->count() ?: '0');
        }
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set(
            'Content-Type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        $response->headers->set(
            'Content-Disposition',
            'attachment;filename="report3_' . $selectedThaiYear . '.xlsx"'
        );

        return $response;
    }
    public function exportPDFReport3(Request $request)
    {
        $selectedThaiYear = $request->year_report3 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        // 1. ดึง ID หลักสูตรที่เป็นการตรวจแบบที่ 1 เท่านั้น
        $type1CourseIds = CourseAssessor::where('assessment_type', '1')
            ->pluck('course_id')
            ->toArray();

        // 2. ดึงข้อมูลการประเมินเฉพาะปีที่เลือก
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $pdf = SnappyPdf::loadView('report3pdf', [
            'faculties' => $faculties,
            'assessment' => $assessment,
            'selectedThaiYear' => $selectedThaiYear,
            'type1CourseIds' => $type1CourseIds
        ]);

        return $pdf->download('report3_' . $selectedThaiYear . '.pdf');
    }
    public function exportExcelReport4(Request $request)
    {
        $selectedThaiYear = $request->year_report4 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();
        // 1. ดึงข้อมูลพื้นฐาน
        $faculties = Faculty::with('courses')->get();

        // 2. ดึงเฉพาะ Course ID ที่เป็นประเภทการตรวจแบบที่ 2 (ตรวจแบบเต็ม 2 วัน)
        $type1CourseIds = CourseAssessor::where('assessment_type', '2')->pluck('course_id')->toArray();

        // 3. แก้ไขจุดนี้: ดึงผลประเมินเฉพาะปี (ไม่ต้องใส่ whereIn course_id ถ้าใน DB ไม่มีคอลัมน์นี้)
        // เราจะไปกรองด้วยชื่อหลักสูตรในขั้นตอนถัดไปเพื่อให้ตรงกับหน้า Blade
        $allAssessments = Assessment::whereYear('created_at', $selectedADYear)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* ----------- HEADER SECTION (3 Rows) ----------- */
        // (ส่วน Header เหมือนเดิมเป๊ะๆ)
        $sheet->setCellValue('A1', 'ที่');
        $sheet->setCellValue('B1', 'ส่วนงานคณะ/วิทยาลัย');
        $sheet->mergeCells('A1:A3');
        $sheet->mergeCells('B1:B3');

        $mainHeaders = [
            ['title' => 'ภาพรวมทั้งคณะ/วิทยาลัย', 'start' => 'C'],
            ['title' => 'ระดับปริญญาตรี', 'start' => 'H'],
            ['title' => 'ระดับปริญญาโท', 'start' => 'M'],
            ['title' => 'ระดับปริญญาเอก', 'start' => 'R'],
        ];

        foreach ($mainHeaders as $header) {
            $start = $header['start'];
            $end = chr(ord($start) + 4);
            $sheet->setCellValue($start . '1', $header['title']);
            $sheet->mergeCells($start . '1:' . $end . '1');
            $sheet->setCellValue($start . '2', "จำนวน\nหลักสูตร");
            $sheet->getStyle($start . '2')->getAlignment()->setWrapText(true);
            $ovStart = chr(ord($start) + 1);
            $sheet->setCellValue($ovStart . '2', 'Overall Verdict');
            $sheet->mergeCells($ovStart . '2:' . $end . '2');
            $results = ['2', '3', '4', '5'];
            foreach ($results as $i => $val) {
                $sheet->setCellValue(chr(ord($ovStart) + $i) . '3', $val);
            }
        }

        /* ----------- BODY SECTION ----------- */
        $row = 4;
        if ($assessment->count() == 0) {
            $sheet->mergeCells("A{$row}:V{$row}");
            $sheet->setCellValue("A{$row}", 'ไม่มีข้อมูล');
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        } else {
            foreach ($faculties as $index => $faculty) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $faculty->name);

                // กรองคอร์สของคณะนี้ที่อยู่ในประเภท Type 2
                $facultyCourses = $faculty->courses->whereIn('id', $type1CourseIds);

                $levels = [null, '1', '2', '3'];
                $startCols = ['C', 'H', 'M', 'R'];

                foreach ($levels as $i => $lvl) {
                    $currentCol = $startCols[$i];
                    $filteredCourses = $lvl ? $facultyCourses->where('level', $lvl) : $facultyCourses;
                    $countCourses = $filteredCourses->count();

                    // ใช้ชื่อหลักสูตรในการกรอง (ตรงกับใน Blade ที่ใช้ whereIn('courses', ...))
                    $names = $filteredCourses->pluck('name')->toArray();
                    $filteredAssessments = $allAssessments->where('faculty', $faculty->name)->whereIn('courses', $names);

                    $sheet->setCellValue($currentCol . $row, $countCourses > 0 ? $countCourses : '-');
                    $sheet->setCellValue(chr(ord($currentCol) + 1) . $row, $filteredAssessments->where('result', '2')->count() ?: '-');
                    $sheet->setCellValue(chr(ord($currentCol) + 2) . $row, $filteredAssessments->where('result', '3')->count() ?: '-');
                    $sheet->setCellValue(chr(ord($currentCol) + 3) . $row, $filteredAssessments->where('result', '4')->count() ?: '-');
                    $sheet->setCellValue(chr(ord($currentCol) + 4) . $row, $filteredAssessments->where('result', '5')->count() ?: '-');
                }
                $row++;
            }

            /* ----------- TOTAL ROW ----------- */
            $sheet->setCellValue('A' . $row, 'รวม (จำนวนหลักสูตร)');
            $sheet->mergeCells('A' . $row . ':B' . $row);

            $allCoursesType2 = $faculties->flatMap->courses->whereIn('id', $type1CourseIds);
            foreach ($levels as $i => $lvl) {
                $currentCol = $startCols[$i];
                $filteredTotalCourses = $lvl ? $allCoursesType2->where('level', $lvl) : $allCoursesType2;
                $totalCount = $filteredTotalCourses->count();

                $names = $filteredTotalCourses->pluck('name')->toArray();
                $filteredTotalAssessments = $allAssessments->whereIn('courses', $names);

                $sheet->setCellValue($currentCol . $row, $totalCount > 0 ? $totalCount : '0');
                $sheet->setCellValue(chr(ord($currentCol) + 1) . $row, $filteredTotalAssessments->where('result', '2')->count() ?: '0');
                $sheet->setCellValue(chr(ord($currentCol) + 2) . $row, $filteredTotalAssessments->where('result', '3')->count() ?: '0');
                $sheet->setCellValue(chr(ord($currentCol) + 4) . $row, $filteredTotalAssessments->where('result', '5')->count() ?: '0');
                $sheet->setCellValue(chr(ord($currentCol) + 3) . $row, $filteredTotalAssessments->where('result', '4')->count() ?: '0');
            }

            // จัด Format เล็กน้อย
            $sheet->getStyle('A1:V' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('A1:V' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
        /* ----------- EXPORT ----------- */
        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="report4_' . $selectedThaiYear . '.xlsx"');

        return $response;
    }
    public function exportPDFReport4(Request $request)
    {
        $selectedThaiYear = $request->year_report4 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        // 1. หา ID ของหลักสูตรที่ต้องการ
        $type1CourseIds = CourseAssessor::where('assessment_type', '2')
            ->pluck('course_id')
            ->toArray();

        // 2. ดึง "ชื่อหลักสูตร" จากตาราง Course เพื่อเอาไปใช้กรองใน Assessment
        $type1CourseNames = Courses::whereIn('id', $type1CourseIds)
            ->pluck('name')
            ->toArray();

        // 3. กรอง Assessment โดยใช้คอลัมน์ 'courses' (หรือชื่อคอลัมน์ที่เก็บชื่อหลักสูตร)
        $assessment = Assessment::whereYear('created_at', $selectedADYear)
            ->whereIn('courses', $type1CourseNames) // ใช้ชื่อในการกรองแทน ID
            ->get();
        $assessment2 = Assessment::whereYear('created_at', $selectedADYear)->get();
        $pdf = SnappyPdf::loadView('report4pdf', [
            'faculties' => $faculties,
            'assessment' => $assessment,
            'selectedThaiYear' => $selectedThaiYear,
            'type1CourseIds' => $type1CourseIds,
            'type1CourseNames' => $type1CourseNames,
            'assessment2' => $assessment2
        ]);

        return $pdf->download('report4_' . $selectedThaiYear . '.pdf');
    }
    public function exportExcelReport5(Request $request)
    {
        $selectedThaiYear = $request->year_report5 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();

        // ดึงเฉพาะ Course ID ที่เป็น Type 3
        $type1CourseIds = CourseAssessor::where('assessment_type', '3')
            ->pluck('course_id')
            ->toArray();

        // เอา whereIn('course_id', ...) ออก เพราะ assessment ไม่มีคอลัมน์ course_id
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();
        $assessment2 = Assessment::whereYear('created_at', $selectedADYear)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ที่');
        $sheet->mergeCells('A1:A3');
        $sheet->setCellValue('B1', 'ส่วนงานคณะ/วิทยาลัย');
        $sheet->mergeCells('B1:B3');

        $groups = [
            'C' => 'ภาพรวมทั้งคณะ/วิทยาลัย',
            'H' => 'ระดับปริญญาตรี',
            'M' => 'ระดับปริญญาโท',
            'R' => 'ระดับปริญญาเอก'
        ];

        foreach ($groups as $col => $title) {
            $sheet->setCellValue($col . '1', $title);
            $sheet->mergeCells($col . '1:' . chr(ord($col) + 4) . '1');

            $sheet->setCellValue($col . '2', "จำนวน\nหลักสูตร");
            $sheet->mergeCells($col . '2:' . $col . '3');

            $nextCol = chr(ord($col) + 1);
            $lastCol = chr(ord($col) + 4);
            $sheet->setCellValue($nextCol . '2', 'Overall Verdict');
            $sheet->mergeCells($nextCol . '2:' . $lastCol . '2');

            for ($i = 0; $i < 4; $i++) {
                $sheet->setCellValue(chr(ord($nextCol) + $i) . '3', $i + 2);
            }
        }

        $row = 4;
        if ($assessment2->count() == 0) {
            $sheet->mergeCells("A{$row}:V{$row}");
            $sheet->setCellValue("A{$row}", 'ไม่มีข้อมูล');
            $sheet->getStyle("A{$row}")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        } else {
            foreach ($faculties as $index => $faculty) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $faculty->name);

                $facultyCourses = $faculty->courses->whereIn('id', $type1CourseIds);

                $levels = [
                    'total' => ['count' => 'C', 'r2' => 'D', 'r3' => 'E', 'r4' => 'F', 'r5' => 'G', 'lvl' => null],
                    'grad1' => ['count' => 'H', 'r2' => 'I', 'r3' => 'J', 'r4' => 'K', 'r5' => 'L', 'lvl' => '1'],
                    'grad2' => ['count' => 'M', 'r2' => 'N', 'r3' => 'O', 'r4' => 'P', 'r5' => 'Q', 'lvl' => '2'],
                    'grad3' => ['count' => 'R', 'r2' => 'S', 'r3' => 'T', 'r4' => 'U', 'r5' => 'V', 'lvl' => '3'],
                ];

                foreach ($levels as $l) {
                    $filteredCourses = $l['lvl']
                        ? $facultyCourses->where('level', $l['lvl'])
                        : $facultyCourses;

                    $courseNames = $filteredCourses->pluck('name')->toArray();

                    $sheet->setCellValue($l['count'] . $row, $filteredCourses->count() ?: '-');

                    $sheet->setCellValue(
                        $l['r2'] . $row,
                        $assessment->where('faculty', $faculty->name)
                            ->whereIn('courses', $courseNames)
                            ->where('result', '2')
                            ->count() ?: '-'
                    );

                    $sheet->setCellValue(
                        $l['r3'] . $row,
                        $assessment->where('faculty', $faculty->name)
                            ->whereIn('courses', $courseNames)
                            ->where('result', '3')
                            ->count() ?: '-'
                    );

                    $sheet->setCellValue(
                        $l['r4'] . $row,
                        $assessment->where('faculty', $faculty->name)
                            ->whereIn('courses', $courseNames)
                            ->where('result', '4')
                            ->count() ?: '-'
                    );

                    $sheet->setCellValue(
                        $l['r5'] . $row,
                        $assessment->where('faculty', $faculty->name)
                            ->whereIn('courses', $courseNames)
                            ->where('result', '5')
                            ->count() ?: '-'
                    );
                }

                $row++;
            }

            $sheet->setCellValue('A' . $row, 'รวม (จำนวนหลักสูตร)');
            $sheet->mergeCells('A' . $row . ':B' . $row);

            $summaryCols = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V'];
            foreach ($summaryCols as $col) {
                $sheet->setCellValue($col . $row, "=SUM({$col}4:{$col}" . ($row - 1) . ")");
            }
        }

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set(
            'Content-Type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        $response->headers->set(
            'Content-Disposition',
            'attachment;filename="report5_' . $selectedThaiYear . '.xlsx"'
        );

        return $response;
    }
    public function exportPDFReport5(Request $request)
    {
        $selectedThaiYear = $request->year_report5 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();

        // ดึงเฉพาะ Course ID ที่เป็น Type 3 เพื่อเอาไปใช้กรองใน Blade
        $type1CourseIds = CourseAssessor::where('assessment_type', '3')
            ->pluck('course_id')
            ->toArray();

        // ดึง assessment ของปีนั้นมาทั้งหมด (ไม่ใช้ whereIn course_id เพราะไม่มี column นี้)
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        // assessment2 เอาไว้เช็คว่ามีข้อมูลหรือไม่ (ใช้ตัวเดียวกับ $assessment ก็ได้เพื่อประหยัด RAM)
        $assessment2 = $assessment;

        $pdf = SnappyPdf::loadView('report5pdf', [
            'faculties' => $faculties,
            'assessment' => $assessment,
            'selectedThaiYear' => $selectedThaiYear,
            'type1CourseIds' => $type1CourseIds,
            'assessment2' => $assessment2
        ]);

        return $pdf->download('report5_' . $selectedThaiYear . '.pdf');
    }
    public function exportExcelReport6(Request $request)
    {
        $selectedThaiYear = $request->year_report6 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        // ให้ตรงกับรายงานหน้าเว็บ: ใช้เฉพาะปีที่เลือก
        $assessmentData = Assessment::whereYear('created_at', $selectedADYear)->get();

        $totalRecords = $assessmentData->count();

        $stats = [];
        for ($i = 0; $i < 8; $i++) {
            for ($level = 1; $level <= 5; $level++) {
                $stats[$i][$level] = 0;
            }
        }

        foreach ($assessmentData as $item) {
            $overall = is_array($item->overall) ? $item->overall : json_decode($item->overall, true);

            if ($overall) {
                foreach ($overall as $index => $score) {
                    $level = (int) $score;
                    if ($index >= 0 && $index < 8 && $level >= 1 && $level <= 5) {
                        $stats[$index][$level]++;
                    }
                }
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('รายงานที่ 6');

        /* -------- HEADER -------- */
        $sheet->setCellValue('A1', 'Rating Scale');
        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('B1', 'Programme');
        $sheet->mergeCells('B1:E1');
        $sheet->setCellValue('F1', 'Resource');
        $sheet->mergeCells('F1:H1');
        $sheet->setCellValue('I1', 'Results');

        for ($i = 1; $i <= 8; $i++) {
            $column = chr(66 + ($i - 1)); // B ถึง I
            $sheet->setCellValue($column . '2', 'AUN-QA ' . $i);
        }

        /* -------- DATA -------- */
        $row = 3;

        if ($totalRecords == 0) {
            $sheet->setCellValue('A3', 'ไม่มีข้อมูล');
            $sheet->mergeCells('A3:I3');
        } else {
            for ($level = 5; $level >= 1; $level--) {
                $sheet->setCellValue('A' . $row, 'ระดับ ' . $level);

                for ($i = 0; $i < 8; $i++) {
                    $count = $stats[$i][$level] ?? 0;
                    $percentage = ($totalRecords > 0) ? ($count / $totalRecords) * 100 : 0;
                    $column = chr(66 + $i);

                    $sheet->setCellValue($column . $row, $count > 0 ? number_format($percentage, 0) : '-');
                }

                $row++;
            }

            $sheet->setCellValue('A' . $row, '');
            for ($i = 0; $i < 8; $i++) {
                $column = chr(66 + $i);
                $sheet->setCellValue($column . $row, '100');
            }
        }

        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="report6_' . $selectedThaiYear . '.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
    public function exportPDFReport6(Request $request)
    {
        $selectedThaiYear = $request->year_report6 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        // ให้ตรงกับหน้า report6
        $assessmentData = Assessment::whereYear('created_at', $selectedADYear)->get();

        $totalRecords = $assessmentData->count();

        $stats = [];
        for ($i = 0; $i < 8; $i++) {
            for ($level = 1; $level <= 5; $level++) {
                $stats[$i][$level] = 0;
            }
        }

        foreach ($assessmentData as $item) {
            $overall = is_array($item->overall) ? $item->overall : json_decode($item->overall, true);

            if ($overall) {
                foreach ($overall as $index => $score) {
                    $level = (int) $score;
                    if ($index >= 0 && $index < 8 && $level >= 1 && $level <= 5) {
                        $stats[$index][$level]++;
                    }
                }
            }
        }

        $pdf = SnappyPdf::loadView('report6pdf', [
            'assessmentData' => $assessmentData,
            'totalRecords' => $totalRecords,
            'stats' => $stats,
            'selectedThaiYear' => $selectedThaiYear
        ]);

        return $pdf->download('report6_' . $selectedThaiYear . '.pdf');
    }
    public function editcoursePage($facultyId)
    {
        $faculty = Faculty::findOrFail($facultyId);
        return view('editcourse', compact('faculty'));
    }
    public function coursereportPage()
    {
        $assessment = Assessment::all();
        $faculties = Faculty::with('courses')->get();
        return view('coursereport', compact('assessment', 'faculties'));
    }
    public function manageassessorPage(Request $request)
    {
        // ปีที่เลือก (พ.ศ.)
        $selectedThaiYear = $request->thai_year ?? date('Y') + 543;

        // แปลงเป็น ค.ศ.
        $selectedADYear = $selectedThaiYear - 543;

        // วิทยาเขตที่เลือก
        $campus = $request->campus;

        // ผู้ใช้ตามปี
        $users = UserAssessor::whereYear('created_at', $selectedADYear)->get();

        // คณะ + หลักสูตร
        $faculties = Faculty::with([
            'courses' => function ($query) use ($selectedADYear) {
                // โหลดข้อมูลการประเมินที่บันทึกไว้แล้วขึ้นมาด้วย
                $query->with(['courseAssessor'])->whereYear('created_at', $selectedADYear);
            }
        ])
            ->when($campus, function ($query) use ($campus) {
                $query->where('campus', $campus);
            })
            ->get();

        return view('manage-assessor', compact(
            'faculties',
            'users',
            'selectedThaiYear',
            'campus'
        ));
    }
    public function storemanageassessor(Request $request)
    {
        $subject_group = $request->subject_group;

        foreach ($request->courses as $data) {
            // ถ้ายังไม่ได้กรอกข้อมูลสำคัญ ให้ข้าม
            if (
                empty($data['chairperson']) &&
                empty($data['position']) &&
                empty($data['intern']) &&
                empty($data['assessment_date']) &&
                empty($data['secretary'])
            ) {
                continue;
            }
            CourseAssessor::updateOrCreate(
                ['course_id' => $data['course_id']],
                [
                    'user_id' => auth()->id(),
                    'faculty_id' => $data['faculty_id'] ?? null,
                    'campus' => $data['campus'] ?? null,
                    'subject_group' => $subject_group,
                    'education_level' => $data['education_level'] ?? null,
                    'assessment_type' => $data['assessment_type'] ?? null,
                    'chairperson' => $data['chairperson'] ?? null,
                    'position' => $data['position'] ?? null,
                    'intern' => $data['intern'] ?? null,
                    'assessment_date' => $data['assessment_date'] ?? null,
                    'secretary' => $data['secretary'] ?? null,
                ]
            );
        }
        return redirect()->route('manage-assessor')->with('success', 'บันทึกข้อมูลสำเร็จ');
    }
    public function tableassessorPage(Request $request)
    {
        $name = auth()->user()->name;
        // ปี พ.ศ.
        $selectedThaiYear = $request->thai_year ?? (date('Y') + 543);

        // แปลงเป็น ค.ศ.
        $selectedADYear = $selectedThaiYear - 543;

        $courseassessor = CourseAssessor::where(function ($query) use ($name) {
            $query->where('chairperson', $name)
                ->orWhere('position', $name)
                ->orWhere('intern', $name)
                ->orWhere('secretary', $name);
        })
            ->whereYear('created_at', $selectedADYear)
            ->get();
        return view('tableassessor', compact('courseassessor', 'selectedThaiYear'));
    }
    public function tableassessortosave(Request $request)
    {
        $faculty = Faculty::find($request->faculty_id);
        $course = Courses::find($request->course_id);
        $user = Auth::user();

        // 1. ดึงข้อมูลการประเมินเดิม (ถ้ามี)
        $existingAssessment = Assessment::where('name', $user->name)
            ->where('faculty', $faculty->name)
            ->where('courses', $course->name)
            ->first();

        // 2. ดึงข้อมูลผู้ประเมินหลักสูตร (เพื่อใช้เช็คสิทธิ์ canEdit ใน Blade)
        $courseAssessor = CourseAssessor::where('faculty_id', $request->faculty_id)
            ->where('course_id', $request->course_id)
            ->first();

        // ส่งตัวแปรทั้งหมดไปที่ View
        return view('save', compact('faculty', 'course', 'existingAssessment', 'courseAssessor'));
    }
    public function assessmentschedulePage(Request $request)
    {
        $selectedThaiYear = $request->thai_year ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $courses = Courses::all();
        $courseassessor = CourseAssessor::with(['course.faculty'])
            ->whereYear('created_at', $selectedADYear)
            ->get();
        return view('assessmentschedule', compact('selectedThaiYear', 'courseassessor', 'courses'));
    }
    public function logout(Request $request)
    {
        Auth::logout(); // ลบการ Login ในระบบ

        $request->session()->invalidate(); // ทำลาย Session
        $request->session()->regenerateToken(); // สร้าง CSRF Token ใหม่เพื่อความปลอดภัย

        return redirect('/login')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }
    public function updatePublishStatus(Request $request)
    {
        $allowedRoles = $request->roles; // จะได้เป็น array ['assessor', 'admin_faculty']

        return back()->with('success', 'บันทึกสถานะการเผยแพร่เรียบร้อยแล้ว');
    }
    public function updatePublish(Request $request)
    {
        // รับ array ของ roles จาก checkbox (เช่น ['admin', 'assessor'])
        $roles = $request->input('roles', []);

        // บังคับเก็บไว้ที่ storage/app/report_access.json
        $path = storage_path('app/report_access.json');
        file_put_contents($path, json_encode(array_values($roles)));

        return back()->with('success', 'ปรับปรุงการเผยแพร่เรียบร้อยแล้ว');
    }
}
