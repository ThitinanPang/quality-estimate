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

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }

    public function checkLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required|min:6',
        ], [
            'email.email' => 'รูปแบบ email ไม่ถูกต้อง',
            'email.required' => 'กรุณากรอก email',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
        ]);

        $username = explode('@', $request->email)[0];
        $ldapUsername = 'BUU\\' . $username;
        $ldapPassword = $request->password;

        try {
            // สร้าง connection ใหม่ด้วย username/password ที่ผู้ใช้กรอก
            $connection = new Connection([
                'hosts' => [config('app.ldap_host_2')],
                'base_dn' => config('app.ldap_base_dn'),
                'username' => $ldapUsername,
                'password' => $ldapPassword,
                'port' => config('app.ldap_port'),
            ]);

            $connection->connect();
            $connection->auth()->bind();

            // ดึงข้อมูลผู้ใช้จาก LDAP
            $ldapUser = $connection->query()->where('samaccountname', '=', $username)->first();
            $email = $ldapUser['mail'][0] ?? $request->email;
            $existingUser = User::where('email', $email)->first();

            // สร้างหรืออัปเดต user ใน DB
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'role' => $existingUser->role ?? 'user', // ถ้ามี user เดิม ใช้ role เดิม, ถ้าไม่มีกำหนด default
                    'status' => 'active',
                ]
            );

            // ทำให้ Laravel Auth รู้จักผู้ใช้
            Auth::login($user);

            // ถ้า role ยังว่าง ให้ปิดการเข้าใช้งาน
            if (empty($user->role)) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'บัญชีนี้ไม่มีสิทธิ์เข้าใช้งาน',
                ]);
            }

            // redirect ตาม role
            if ($user->role == 'admin') {
                return redirect('/home')->with('success', 'เข้าสู่ระบบสำเร็จ');
            } elseif ($user->role == 'user') {
                return redirect('/home')->with('success', 'เข้าสู่ระบบสำเร็จ');
            } elseif ($user->role == 'admin university') {
                return redirect('/home')->with('success', 'เข้าสู่ระบบสำเร็จ');
            }

            logger('LDAP bind success: ' . $ldapUsername);

        } catch (\LdapRecord\Auth\BindException $e) {
            logger('LDAP bind failed: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
            ]);
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

        // โหลดไฟล์ Excel
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        // ข้าม row แรก (header)
        foreach ($rows as $index => $row) {
            if ($index === 0)
                continue;
            $email = trim($row[6] ?? '');
            if ($email == '') {
                continue;
            }
            $phone = $row[7] ?? null;

            if ($phone) {
                $phone = preg_replace('/[^0-9]/', '', $phone);
            }
            UserAssessor::updateOrCreate(
                ['email' => $email], // ใช้ email เป็น unique key
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
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('edituser', compact('user'));
    }

    // อัปเดตข้อมูล
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

        $user = User::findOrFail($id);

        $user->update([
            'prefix' => $request->prefix,
            'name' => $request->name,
            'faculty' => $request->faculty,
            'subject_group' => $request->subject_group,
            'course' => $request->course,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'status' => $request->status ?? $user->status,
            'role' => $request->role,
        ]);

        return redirect()->route('user')->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
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
        $faculty = Faculty::find($request->faculty);
        return view('save', compact('faculty'));
    }
    public function collect(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
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
            // [
            //     'name' => $request->name,
            //     'result' => $request->result,
            // ],
            [
                'name' => $request->name,
                'result' => $request->result,
                'faculty' => $request->faculty,
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
        $faculties = Faculty::orderBy('name', 'ASC')->get();
        $users = User::all()->keyBy('name');
        return view('results', compact('course_assessment', 'faculties', 'users'));
    }
    public function savePage(Request $request)
    {
        $faculty = Faculty::find($request->faculty);
        return view('save', compact('faculty'));
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
    public function listfacultyPage()
    {
        // ดึงข้อมูล faculty ทั้งหมด
        $faculties = Faculty::all();

        return view('listfaculty', compact('faculties'));
    }
    public function reportPage(Request $request)
    {

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
        // query ข้อมูล
        $faculties = $this->getReportData($yearReport1AD);

        $assessment = Assessment::whereYear('created_at', $yearReport2AD)->get();
        $assessment2 = Assessment::whereYear('created_at', $yearReport3AD)->get();
        $assessment3 = Assessment::whereYear('created_at', $yearReport4AD)->get();
        $assessment4 = Assessment::whereYear('created_at', $yearReport5AD)->get();
        $assessment5 = Assessment::whereYear('created_at', $yearReport6AD)->get();

        $ft = Faculty::with('courses')->withCount('courses')->get();

        return view('report', compact(
            'faculties',
            'ft',
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
        $selectedThaiYear = $request->thai_year ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = $this->getReportData($selectedADYear);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ที่');
        $sheet->setCellValue('B1', 'ส่วนงานคณะ/วิทยาลัย');
        $sheet->setCellValue('C1', 'จำนวนหลักสูตร');
        $sheet->setCellValue('D1', 'เป็นไปตามเกณฑ์');
        $sheet->setCellValue('E1', 'ไม่เป็นไปตามเกณฑ์');

        $row = 2;

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
        $selectedThaiYear = $request->thai_year ?? (date('Y') + 543);
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

        // HEADER ROW 1
        $sheet->setCellValue('A1', 'ที่');
        $sheet->setCellValue('B1', 'คณะ/วิทยาลัย');

        $sheet->setCellValue('C1', 'ภาพรวม');
        $sheet->mergeCells('C1:G1');

        $sheet->setCellValue('H1', 'ปริญญาตรี');
        $sheet->mergeCells('H1:L1');

        $sheet->setCellValue('M1', 'ปริญญาโท');
        $sheet->mergeCells('M1:Q1');

        $sheet->setCellValue('R1', 'ปริญญาเอก');
        $sheet->mergeCells('R1:V1');

        // HEADER ROW 2
        $sheet->setCellValue('C2', 'จำนวน');
        $sheet->mergeCells('D2:G2');

        $sheet->setCellValue('H2', 'จำนวน');
        $sheet->mergeCells('I2:L2');

        $sheet->setCellValue('M2', 'จำนวน');
        $sheet->mergeCells('N2:Q2');

        $sheet->setCellValue('R2', 'จำนวน');
        $sheet->mergeCells('S2:V2');

        // HEADER ROW 3
        $headers = ['2', '3', '4', '5'];

        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(68 + $i) . '3', $h);
            $sheet->setCellValue(chr(73 + $i) . '3', $h);
            $sheet->setCellValue(chr(78 + $i) . '3', $h);
            $sheet->setCellValue(chr(83 + $i) . '3', $h);
        }

        $row = 4;

        foreach ($faculties as $index => $faculty) {

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $faculty->name);

            $totalCourses = $faculty->courses->count();

            $sheet->setCellValue('C' . $row, $totalCourses);

            $sheet->setCellValue('D' . $row, $assessment->where('faculty', $faculty->name)->where('result', '2')->count());
            $sheet->setCellValue('E' . $row, $assessment->where('faculty', $faculty->name)->where('result', '3')->count());
            $sheet->setCellValue('F' . $row, $assessment->where('faculty', $faculty->name)->where('result', '4')->count());
            $sheet->setCellValue('G' . $row, $assessment->where('faculty', $faculty->name)->where('result', '5')->count());

            $sheet->setCellValue('H' . $row, $faculty->courses->where('level', '1')->count());
            $sheet->setCellValue('M' . $row, $faculty->courses->where('level', '2')->count());
            $sheet->setCellValue('R' . $row, $faculty->courses->where('level', '3')->count());

            $row++;
        }

        /* ----------- รวม ----------- */

        $totalCourses = $faculties->sum(fn($f) => $f->courses->count());
        $total2 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '2')->count());
        $total3 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '3')->count());
        $total4 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '4')->count());
        $total5 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '5')->count());

        $sheet->setCellValue('A' . $row, 'รวม');
        $sheet->mergeCells('A' . $row . ':B' . $row);

        $sheet->setCellValue('C' . $row, $totalCourses);
        $sheet->setCellValue('D' . $row, $total2);
        $sheet->setCellValue('E' . $row, $total3);
        $sheet->setCellValue('F' . $row, $total4);
        $sheet->setCellValue('G' . $row, $total5);

        $row++;

        /* ----------- เปอร์เซนต์ ----------- */

        $sheet->setCellValue('A' . $row, 'เปอร์เซนต์ (%)');
        $sheet->mergeCells('A' . $row . ':B' . $row);

        $sheet->setCellValue('C' . $row, 100);

        $sheet->setCellValue('D' . $row, $totalCourses ? round(($total2 / $totalCourses) * 100, 2) : 0);
        $sheet->setCellValue('E' . $row, $totalCourses ? round(($total3 / $totalCourses) * 100, 2) : 0);
        $sheet->setCellValue('F' . $row, $totalCourses ? round(($total4 / $totalCourses) * 100, 2) : 0);
        $sheet->setCellValue('G' . $row, $totalCourses ? round(($total5 / $totalCourses) * 100, 2) : 0);

        /* ----------- export ----------- */

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
            'attachment;filename="report2_' . $selectedThaiYear . '.xlsx"'
        );

        return $response;
    }
    public function exportPDFReport2(Request $request)
    {
        $selectedThaiYear = $request->year_report2 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $pdf = SnappyPdf::loadView('report2pdf', [
            'faculties' => $faculties,
            'assessment' => $assessment,
            'selectedThaiYear' => $selectedThaiYear
        ]);

        return $pdf->download('report2_' . $selectedThaiYear . '.pdf');
    }
    public function exportExcelReport3(Request $request)
    {
        $selectedThaiYear = $request->year_report3 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // HEADER ROW 1
        $sheet->setCellValue('A1', 'ที่');
        $sheet->setCellValue('B1', 'คณะ/วิทยาลัย');

        $sheet->setCellValue('C1', 'ภาพรวม');
        $sheet->mergeCells('C1:G1');

        $sheet->setCellValue('H1', 'ปริญญาตรี');
        $sheet->mergeCells('H1:L1');

        $sheet->setCellValue('M1', 'ปริญญาโท');
        $sheet->mergeCells('M1:Q1');

        $sheet->setCellValue('R1', 'ปริญญาเอก');
        $sheet->mergeCells('R1:V1');

        // HEADER ROW 2
        $sheet->setCellValue('C2', 'จำนวน');
        $sheet->mergeCells('D2:G2');

        $sheet->setCellValue('H2', 'จำนวน');
        $sheet->mergeCells('I2:L2');

        $sheet->setCellValue('M2', 'จำนวน');
        $sheet->mergeCells('N2:Q2');

        $sheet->setCellValue('R2', 'จำนวน');
        $sheet->mergeCells('S2:V2');

        // HEADER ROW 3
        $headers = ['2', '3', '4', '5'];

        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(68 + $i) . '3', $h);
            $sheet->setCellValue(chr(73 + $i) . '3', $h);
            $sheet->setCellValue(chr(78 + $i) . '3', $h);
            $sheet->setCellValue(chr(83 + $i) . '3', $h);
        }

        $row = 4;

        foreach ($faculties as $index => $faculty) {

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $faculty->name);

            $totalCourses = $faculty->courses->count();

            $sheet->setCellValue('C' . $row, $totalCourses);

            $sheet->setCellValue('D' . $row, $assessment->where('faculty', $faculty->name)->where('result', '2')->count());
            $sheet->setCellValue('E' . $row, $assessment->where('faculty', $faculty->name)->where('result', '3')->count());
            $sheet->setCellValue('F' . $row, $assessment->where('faculty', $faculty->name)->where('result', '4')->count());
            $sheet->setCellValue('G' . $row, $assessment->where('faculty', $faculty->name)->where('result', '5')->count());

            $sheet->setCellValue('H' . $row, $faculty->courses->where('level', '1')->count());
            $sheet->setCellValue('M' . $row, $faculty->courses->where('level', '2')->count());
            $sheet->setCellValue('R' . $row, $faculty->courses->where('level', '3')->count());

            $row++;
        }

        /* ----------- รวม ----------- */

        $totalCourses = $faculties->sum(fn($f) => $f->courses->count());
        $total2 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '2')->count());
        $total3 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '3')->count());
        $total4 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '4')->count());
        $total5 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '5')->count());

        $sheet->setCellValue('A' . $row, 'รวม');
        $sheet->mergeCells('A' . $row . ':B' . $row);

        $sheet->setCellValue('C' . $row, $totalCourses);
        $sheet->setCellValue('D' . $row, $total2);
        $sheet->setCellValue('E' . $row, $total3);
        $sheet->setCellValue('F' . $row, $total4);
        $sheet->setCellValue('G' . $row, $total5);

        $row++;

        /* ----------- export ----------- */

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
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $pdf = SnappyPdf::loadView('report3pdf', [
            'faculties' => $faculties,
            'assessment' => $assessment,
            'selectedThaiYear' => $selectedThaiYear
        ]);

        return $pdf->download('report3_' . $selectedThaiYear . '.pdf');
    }
    public function exportExcelReport4(Request $request)
    {
        $selectedThaiYear = $request->year_report4 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // HEADER ROW 1
        $sheet->setCellValue('A1', 'ที่');
        $sheet->setCellValue('B1', 'คณะ/วิทยาลัย');

        $sheet->setCellValue('C1', 'ภาพรวม');
        $sheet->mergeCells('C1:G1');

        $sheet->setCellValue('H1', 'ปริญญาตรี');
        $sheet->mergeCells('H1:L1');

        $sheet->setCellValue('M1', 'ปริญญาโท');
        $sheet->mergeCells('M1:Q1');

        $sheet->setCellValue('R1', 'ปริญญาเอก');
        $sheet->mergeCells('R1:V1');

        // HEADER ROW 2
        $sheet->setCellValue('C2', 'จำนวน');
        $sheet->mergeCells('D2:G2');

        $sheet->setCellValue('H2', 'จำนวน');
        $sheet->mergeCells('I2:L2');

        $sheet->setCellValue('M2', 'จำนวน');
        $sheet->mergeCells('N2:Q2');

        $sheet->setCellValue('R2', 'จำนวน');
        $sheet->mergeCells('S2:V2');

        // HEADER ROW 3
        $headers = ['2', '3', '4', '5'];

        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(68 + $i) . '3', $h);
            $sheet->setCellValue(chr(73 + $i) . '3', $h);
            $sheet->setCellValue(chr(78 + $i) . '3', $h);
            $sheet->setCellValue(chr(83 + $i) . '3', $h);
        }

        $row = 4;

        foreach ($faculties as $index => $faculty) {

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $faculty->name);

            $totalCourses = $faculty->courses->count();

            $sheet->setCellValue('C' . $row, $totalCourses);

            $sheet->setCellValue('D' . $row, $assessment->where('faculty', $faculty->name)->where('result', '2')->count());
            $sheet->setCellValue('E' . $row, $assessment->where('faculty', $faculty->name)->where('result', '3')->count());
            $sheet->setCellValue('F' . $row, $assessment->where('faculty', $faculty->name)->where('result', '4')->count());
            $sheet->setCellValue('G' . $row, $assessment->where('faculty', $faculty->name)->where('result', '5')->count());

            $sheet->setCellValue('H' . $row, $faculty->courses->where('level', '1')->count());
            $sheet->setCellValue('M' . $row, $faculty->courses->where('level', '2')->count());
            $sheet->setCellValue('R' . $row, $faculty->courses->where('level', '3')->count());

            $row++;
        }

        /* ----------- รวม ----------- */

        $totalCourses = $faculties->sum(fn($f) => $f->courses->count());
        $total2 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '2')->count());
        $total3 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '3')->count());
        $total4 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '4')->count());
        $total5 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '5')->count());

        $sheet->setCellValue('A' . $row, 'รวม');
        $sheet->mergeCells('A' . $row . ':B' . $row);

        $sheet->setCellValue('C' . $row, $totalCourses);
        $sheet->setCellValue('D' . $row, $total2);
        $sheet->setCellValue('E' . $row, $total3);
        $sheet->setCellValue('F' . $row, $total4);
        $sheet->setCellValue('G' . $row, $total5);

        $row++;

        /* ----------- export ----------- */

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
            'attachment;filename="report4_' . $selectedThaiYear . '.xlsx"'
        );

        return $response;
    }
    public function exportPDFReport4(Request $request)
    {
        $selectedThaiYear = $request->year_report4 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $pdf = SnappyPdf::loadView('report4pdf', [
            'faculties' => $faculties,
            'assessment' => $assessment,
            'selectedThaiYear' => $selectedThaiYear
        ]);

        return $pdf->download('report4_' . $selectedThaiYear . '.pdf');
    }
    public function exportExcelReport5(Request $request)
    {
        $selectedThaiYear = $request->year_report5 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // HEADER ROW 1
        $sheet->setCellValue('A1', 'ที่');
        $sheet->setCellValue('B1', 'คณะ/วิทยาลัย');

        $sheet->setCellValue('C1', 'ภาพรวม');
        $sheet->mergeCells('C1:G1');

        $sheet->setCellValue('H1', 'ปริญญาตรี');
        $sheet->mergeCells('H1:L1');

        $sheet->setCellValue('M1', 'ปริญญาโท');
        $sheet->mergeCells('M1:Q1');

        $sheet->setCellValue('R1', 'ปริญญาเอก');
        $sheet->mergeCells('R1:V1');

        // HEADER ROW 2
        $sheet->setCellValue('C2', 'จำนวน');
        $sheet->mergeCells('D2:G2');

        $sheet->setCellValue('H2', 'จำนวน');
        $sheet->mergeCells('I2:L2');

        $sheet->setCellValue('M2', 'จำนวน');
        $sheet->mergeCells('N2:Q2');

        $sheet->setCellValue('R2', 'จำนวน');
        $sheet->mergeCells('S2:V2');

        // HEADER ROW 3
        $headers = ['2', '3', '4', '5'];

        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(68 + $i) . '3', $h);
            $sheet->setCellValue(chr(73 + $i) . '3', $h);
            $sheet->setCellValue(chr(78 + $i) . '3', $h);
            $sheet->setCellValue(chr(83 + $i) . '3', $h);
        }

        $row = 4;

        foreach ($faculties as $index => $faculty) {

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $faculty->name);

            $totalCourses = $faculty->courses->count();

            $sheet->setCellValue('C' . $row, $totalCourses);

            $sheet->setCellValue('D' . $row, $assessment->where('faculty', $faculty->name)->where('result', '2')->count());
            $sheet->setCellValue('E' . $row, $assessment->where('faculty', $faculty->name)->where('result', '3')->count());
            $sheet->setCellValue('F' . $row, $assessment->where('faculty', $faculty->name)->where('result', '4')->count());
            $sheet->setCellValue('G' . $row, $assessment->where('faculty', $faculty->name)->where('result', '5')->count());

            $sheet->setCellValue('H' . $row, $faculty->courses->where('level', '1')->count());
            $sheet->setCellValue('M' . $row, $faculty->courses->where('level', '2')->count());
            $sheet->setCellValue('R' . $row, $faculty->courses->where('level', '3')->count());

            $row++;
        }

        /* ----------- รวม ----------- */

        $totalCourses = $faculties->sum(fn($f) => $f->courses->count());
        $total2 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '2')->count());
        $total3 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '3')->count());
        $total4 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '4')->count());
        $total5 = $faculties->sum(fn($f) => $assessment->where('faculty', $f->name)->where('result', '5')->count());

        $sheet->setCellValue('A' . $row, 'รวม');
        $sheet->mergeCells('A' . $row . ':B' . $row);

        $sheet->setCellValue('C' . $row, $totalCourses);
        $sheet->setCellValue('D' . $row, $total2);
        $sheet->setCellValue('E' . $row, $total3);
        $sheet->setCellValue('F' . $row, $total4);
        $sheet->setCellValue('G' . $row, $total5);

        $row++;

        /* ----------- export ----------- */

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
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $pdf = SnappyPdf::loadView('report5pdf', [
            'faculties' => $faculties,
            'assessment' => $assessment,
            'selectedThaiYear' => $selectedThaiYear
        ]);

        return $pdf->download('report5_' . $selectedThaiYear . '.pdf');
    }
    public function exportExcelReport6(Request $request)
    {
        $selectedThaiYear = $request->year_report6 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* -------- HEADER ROW 1 -------- */

        $sheet->setCellValue('A1', 'Rating Scale');
        $sheet->mergeCells('A1:A2');

        $sheet->setCellValue('B1', 'Programme');
        $sheet->mergeCells('B1:E1');

        $sheet->setCellValue('F1', 'Resource');
        $sheet->mergeCells('F1:H1');

        $sheet->setCellValue('I1', 'Results');


        /* -------- HEADER ROW 2 -------- */

        $sheet->setCellValue('B2', 'AUN-QA 1');
        $sheet->setCellValue('C2', 'AUN-QA 2');
        $sheet->setCellValue('D2', 'AUN-QA 3');
        $sheet->setCellValue('E2', 'AUN-QA 4');
        $sheet->setCellValue('F2', 'AUN-QA 5');
        $sheet->setCellValue('G2', 'AUN-QA 6');
        $sheet->setCellValue('H2', 'AUN-QA 7');
        $sheet->setCellValue('I2', 'AUN-QA 8');


        /* -------- DATA -------- */

        $levels = [5, 4, 3, 2, 1];
        $row = 3;

        foreach ($levels as $level) {

            $sheet->setCellValue('A' . $row, 'ระดับ ' . $level);

            for ($i = 1; $i <= 8; $i++) {

                $count = $assessment
                    ->where('aun_qa', $i)
                    ->where('result', $level)
                    ->count();

                $column = chr(65 + $i);

                $sheet->setCellValue($column . $row, $count);
            }

            $row++;
        }


        /* -------- EXPORT -------- */

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
            'attachment;filename="report6_' . $selectedThaiYear . '.xlsx"'
        );

        return $response;
    }
    public function exportPDFReport6(Request $request)
    {
        $selectedThaiYear = $request->year_report6 ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;

        $faculties = Faculty::with('courses')->get();
        $assessment = Assessment::whereYear('created_at', $selectedADYear)->get();

        $pdf = SnappyPdf::loadView('report6pdf', [
            'faculties' => $faculties,
            'assessment' => $assessment,
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
                $query->whereYear('created_at', $selectedADYear);
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
            CourseAssessor::create([
                'course_id' => $data['course_id'],
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
            ]);
        }
        return redirect()->route('manage-assessor')->with('success', 'บันทึกข้อมูลสำเร็จ');
    }
}
