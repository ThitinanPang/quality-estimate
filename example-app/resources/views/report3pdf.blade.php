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

    <h2>
        รายงานสรุปผลการตรวจประเมินภายใน ระดับหลักสูตร
        รายงานที่ 3 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน ระดับหลักสูตร ตามเกณฑ์ AUN-QA Version 4.0<br>
        (Overall Verdict) ประจำปีการศึกษา {{ $selectedThaiYear }} (ตรวจประเมินแบบหนึ่งวัน จำนวน
        {{ $faculties->sum(fn($faculty) => $faculty->courses->count()) }} หลักสูตร)
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

            @php
                // เตรียม ID หลักสูตรที่เป็น Type 1 ทั้งหมดเพื่อใช้ในแถวรวมท้ายตาราง
                $allType1Courses = $faculties->flatMap->courses->whereIn('id', $type1CourseIds);
            @endphp

            @if($assessment->count() == 0)
                <tr>
                    <td colspan="22" style="text-align:center;">ไม่มีข้อมูล</td>
                </tr>
            @else
                @foreach ($faculties as $index => $faculty)
                    @php
                        // กรองหลักสูตรเฉพาะ Type 1 ของคณะนี้
                        $coursesType1 = $faculty->courses->whereIn('id', $type1CourseIds);

                        // แยกรายชื่อตาม Level เพื่อไปเช็คใน $assessment
                        $level1Names = $coursesType1->where('level', '1')->pluck('name')->toArray();
                        $level2Names = $coursesType1->where('level', '2')->pluck('name')->toArray();
                        $level3Names = $coursesType1->where('level', '3')->pluck('name')->toArray();
                        $allNames = $coursesType1->pluck('name')->toArray();
                    @endphp
                    <tr>
                        <td style="text-align:center">{{ $index + 1 }}</td>
                        <td style="white-space: nowrap;">{{ $faculty->name }}</td>

                        <td style="text-align:center; background-color: #f9f9f9;">{{ $coursesType1->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $allNames)->where('result', '2')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $allNames)->where('result', '3')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $allNames)->where('result', '4')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $allNames)->where('result', '5')->count() ?: '-' }}
                        </td>

                        <td style="text-align:center; background-color: #f9f9f9;">{{ count($level1Names) ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level1Names)->where('result', '2')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level1Names)->where('result', '3')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level1Names)->where('result', '4')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level1Names)->where('result', '5')->count() ?: '-' }}
                        </td>

                        <td style="text-align:center; background-color: #f9f9f9;">{{ count($level2Names) ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level2Names)->where('result', '2')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level2Names)->where('result', '3')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level2Names)->where('result', '4')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level2Names)->where('result', '5')->count() ?: '-' }}
                        </td>

                        <td style="text-align:center; background-color: #f9f9f9;">{{ count($level3Names) ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level3Names)->where('result', '2')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level3Names)->where('result', '3')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level3Names)->where('result', '4')->count() ?: '-' }}
                        </td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $level3Names)->where('result', '5')->count() ?: '-' }}
                        </td>
                    </tr>
                @endforeach

                @php
                    $allL1Names = $allType1Courses->where('level', '1')->pluck('name')->toArray();
                    $allL2Names = $allType1Courses->where('level', '2')->pluck('name')->toArray();
                    $allL3Names = $allType1Courses->where('level', '3')->pluck('name')->toArray();
                    $allType1Names = $allType1Courses->pluck('name')->toArray();
                @endphp
                <tr style="background-color: #eee; font-weight: bold;">
                    <td colspan="2" style="text-align:center">รวม (จำนวนหลักสูตร)</td>

                    <td style="text-align:center">{{ $allType1Courses->count() }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allType1Names)->where('result', '2')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allType1Names)->where('result', '3')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allType1Names)->where('result', '4')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allType1Names)->where('result', '5')->count() ?: '0' }}</td>

                    <td style="text-align:center">{{ count($allL1Names) }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL1Names)->where('result', '2')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL1Names)->where('result', '3')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL1Names)->where('result', '4')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL1Names)->where('result', '5')->count() ?: '0' }}</td>

                    <td style="text-align:center">{{ count($allL2Names) }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL2Names)->where('result', '2')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL2Names)->where('result', '3')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL2Names)->where('result', '4')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL2Names)->where('result', '5')->count() ?: '0' }}</td>

                    <td style="text-align:center">{{ count($allL3Names) }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL3Names)->where('result', '2')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL3Names)->where('result', '3')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL3Names)->where('result', '4')->count() ?: '0' }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('courses', $allL3Names)->where('result', '5')->count() ?: '0' }}</td>
                </tr>
            @endif

        </tbody>

    </table>

</body>

</html>