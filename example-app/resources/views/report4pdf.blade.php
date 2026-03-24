<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: 'TH Sarabun New';
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid black;
            padding: 6px;
        }
    </style>

</head>

<body>
    @php
        $type1CourseIds = \App\Models\CourseAssessor::where('assessment_type', '2')->pluck('course_id')->toArray();
        $totalType1All = $faculties->flatMap->courses->whereIn('id', $type1CourseIds)->count();
    @endphp
    <h2>
        รายงานสรุปผลการตรวจประเมินภายใน ระดับหลักสูตร
        รายงานที่ 4 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน ระดับหลักสูตร ตามเกณฑ์ AUN-QA Version 4.0<br>
        (Overall Verdict) ประจำปีการศึกษา {{ $selectedThaiYear }} (ตรวจประเมินแบบเต็ม (2วัน) ประธานกรรมการเป็นบุคลภายใน
        จำนวน {{ $totalType1All > 0 ? $totalType1All : '0' }} หลักสูตร)
    </h2>

    <table>

        <thead>

            <tr>
                <th rowspan="3">ที่</th>
                <th rowspan="3">ส่วนงานคณะ/วิทยาลัย</th>

                <th colspan="5">ภาพรวมทั้งคณะ/วิทยาลัย</th>
                <th colspan="5">ระดับปริญญาตรี</th>
                <th colspan="5">ระดับปริญญาโท</th>
                <th colspan="5">ระดับปริญญาเอก</th>
            </tr>

            <tr>
                <th rowspan="2">จำนวน<br>หลักสูตร</th>
                <th colspan="4">Overall Verdict</th>

                <th rowspan="2">จำนวน<br>หลักสูตร</th>
                <th colspan="4">Overall Verdict</th>

                <th rowspan="2">จำนวน<br>หลักสูตร</th>
                <th colspan="4">Overall Verdict</th>

                <th rowspan="2">จำนวน<br>หลักสูตร</th>
                <th colspan="4">Overall Verdict</th>
            </tr>

            <tr>

                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>

                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>

                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>

                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>

            </tr>

        </thead>

        <tbody>
            @if($assessment2->count() == 0)
                <tr>
                    <td colspan="22" style="text-align:center;">ไม่มีข้อมูล</td>
                </tr>
            @else
                @foreach ($faculties as $index => $faculty)
                    @php
                        // กรองหลักสูตรของคณะนี้ที่อยู่ในกลุ่ม Type 2
                        $facultyCourses = $faculty->courses->whereIn('id', $type1CourseIds);
                        $courseNames = $facultyCourses->pluck('name')->toArray();

                        // กรองผลการประเมินเฉพาะของคณะนี้
                        $facultyAssessments = $assessment->where('faculty', $faculty->name);
                    @endphp
                    <tr>
                        <td style="text-align:center">{{ $index + 1 }}</td>
                        <td>{{ $faculty->name }}</td>

                        <td style="text-align:center">{{ $facultyCourses->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $facultyAssessments->where('result', '2')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $facultyAssessments->where('result', '3')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $facultyAssessments->where('result', '4')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $facultyAssessments->where('result', '5')->count() ?: '-' }}</td>

                        @php $l1Courses = $facultyCourses->where('level', '1'); @endphp
                        <td style="text-align:center">{{ $l1Courses->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l1Courses->pluck('name'))->where('result', '2')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l1Courses->pluck('name'))->where('result', '3')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l1Courses->pluck('name'))->where('result', '4')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l1Courses->pluck('name'))->where('result', '5')->count() ?: '-' }}
                        </td>

                        @php $l2Courses = $facultyCourses->where('level', '2'); @endphp
                        <td style="text-align:center">{{ $l2Courses->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l2Courses->pluck('name'))->where('result', '2')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l2Courses->pluck('name'))->where('result', '3')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l2Courses->pluck('name'))->where('result', '4')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l2Courses->pluck('name'))->where('result', '5')->count() ?: '-' }}
                        </td>

                        @php $l3Courses = $facultyCourses->where('level', '3'); @endphp
                        <td style="text-align:center">{{ $l3Courses->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l3Courses->pluck('name'))->where('result', '2')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l3Courses->pluck('name'))->where('result', '3')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l3Courses->pluck('name'))->where('result', '4')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $facultyAssessments->whereIn('courses', $l3Courses->pluck('name'))->where('result', '5')->count() ?: '-' }}
                        </td>
                    </tr>
                @endforeach
                {{-- เริ่มแถวรวม --}}
                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td colspan="2" style="text-align:center">รวม (จำนวนหลักสูตร)</td>

                    @php
                        // เตรียมข้อมูลสำหรับคำนวณยอดรวมทั้งสถาบัน
                        $totalCoursesAll = $faculties->flatMap(fn($f) => $f->courses->whereIn('id', $type1CourseIds));
                        $totalL1All = $totalCoursesAll->where('level', '1');
                        $totalL2All = $totalCoursesAll->where('level', '2');
                        $totalL3All = $totalCoursesAll->where('level', '3');
                    @endphp

                    {{-- รวมภาพรวมทั้งคณะ --}}
                    <td style="text-align:center">{{ $totalCoursesAll->count() ?: '0' }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '2')->count() ?: '0' }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '3')->count() ?: '0' }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '4')->count() ?: '0' }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '5')->count() ?: '0' }}</td>

                    {{-- รวมระดับ ป.ตรี --}}
                    <td style="text-align:center">{{ $totalL1All->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL1All->pluck('name'))->where('result', '2')->count() ?: '0' }}
                    </td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL1All->pluck('name'))->where('result', '3')->count() ?: '0' }}
                    </td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL1All->pluck('name'))->where('result', '4')->count() ?: '0' }}
                    </td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL1All->pluck('name'))->where('result', '5')->count() ?: '0' }}
                    </td>

                    {{-- รวมระดับ ป.โท --}}
                    <td style="text-align:center">{{ $totalL2All->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL2All->pluck('name'))->where('result', '2')->count() ?: '0' }}
                    </td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL2All->pluck('name'))->where('result', '3')->count() ?: '0' }}
                    </td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL2All->pluck('name'))->where('result', '4')->count() ?: '0' }}
                    </td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL2All->pluck('name'))->where('result', '5')->count() ?: '0' }}
                    </td>

                    {{-- รวมระดับ ป.เอก --}}
                    <td style="text-align:center">{{ $totalL3All->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL3All->pluck('name'))->where('result', '2')->count() ?: '0' }}
                    </td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL3All->pluck('name'))->where('result', '3')->count() ?: '0' }}
                    </td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL3All->pluck('name'))->where('result', '4')->count() ?: '0' }}
                    </td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $totalL3All->pluck('name'))->where('result', '5')->count() ?: '0' }}
                    </td>
                </tr>
            @endif
        </tbody>

    </table>

</body>

</html>