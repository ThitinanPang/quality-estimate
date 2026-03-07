<?php

namespace App\Http\Controllers;

use App\Models\User;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

            // ข้ามถ้า email ว่าง
            if (empty($row[4])) {
                continue;
            }

            User::updateOrCreate(
                ['email' => $row[4]], // ใช้ email เป็น unique key
                [
                    'prefix' => $row[0] ?? null,
                    'name' => $row[1] ?? null,
                    'faculty' => $row[2] ?? null,
                    'subject_group' => $row[3] ?? null,
                    'phone_number' => $row[5] ?? null,
                ]
            );
        }

        return redirect()->route('user')->with('success', 'นำเข้าข้อมูลสำเร็จ');
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
        $facultyId = $request->faculty;
        $faculty = Faculty::find($facultyId);
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
        return view('results');
    }
    public function savePage()
    {
        return view('save');
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
        $user = User::findOrFail($id);
        return view('editassessor', compact('user'));
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
        return redirect()->route('assessor');
    }
    public function listfacultyPage()
    {
        // ดึงข้อมูล faculty ทั้งหมด
        $faculties = Faculty::all();

        return view('listfaculty', compact('faculties'));
    }
    public function reportPage()
    {
        $faculties = $this->getReportData();
        return view('report', compact('faculties'));
    }
    private function getReportData()
    {
        $faculties = Faculty::all()->map(function ($faculty) {

            // จำนวนหลักสูตร
            $coursesCount = Courses::where('faculty_id', $faculty->id)->count();

            // ผ่านเกณฑ์
            $totalPass = Assessment::where('faculty', $faculty->name)
                ->where('criterion', 'เป็นไปตามเกณฑ์')
                ->count();

            // ไม่ผ่านเกณฑ์
            $totalFail = Assessment::where('faculty', $faculty->name)
                ->where('criterion', 'ไม่เป็นไปตามเกณฑ์')
                ->count();

            $faculty->courses_count = $coursesCount;
            $faculty->total_pass = $totalPass;
            $faculty->total_fail = $totalFail;

            return $faculty;
        });

        return $faculties;
    }
    public function exportExcel()
    {
        $faculties = $this->getReportData(); // ใช้ function เดิมที่คุณมี

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ที่');
        $sheet->setCellValue('B1', 'ส่วนงานคณะ/วิทยาลัย');
        $sheet->setCellValue('C1', 'จำนวนหลักสูตร');
        $sheet->setCellValue('D1', 'เป็นไปตามเกณฑ์');
        $sheet->setCellValue('E1', 'ไม่เป็นไปตามเกณฑ์');

        $row = 2;

        foreach ($faculties as $index => $faculty) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $faculty->name);
            $sheet->setCellValue('C' . $row, $faculty->courses_count ?? '-');
            $sheet->setCellValue('D' . $row, $faculty->total_pass ?: '-');
            $sheet->setCellValue('E' . $row, $faculty->total_fail ?: '-');

            $row++;
        }

        // แถวรวม
        $sheet->setCellValue('A' . $row, '');
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
            'attachment;filename="report.xlsx"'
        );

        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
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

        // กรองตามปี created_at
        $users = User::whereYear('created_at', $selectedADYear)->get();

        $faculties = Faculty::with([
            'courses' => function ($query) use ($selectedADYear) {
                $query->whereYear('created_at', $selectedADYear);
            }
        ])->get();

        return view('manage-assessor', compact('faculties', 'users', 'selectedThaiYear'));
    }
}
