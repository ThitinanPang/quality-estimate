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
        รายงานที่ 2 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน ระดับหลักสูตร ตามเกณฑ์ AUN-QA Version 4.0<br>
        (Overall Verdict) ประจำปีการศึกษา {{ $selectedThaiYear }}
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
            @if($assessment->count() == 0)
                <tr>
                    <td colspan="22" style="text-align:center;">ไม่มีข้อมูล</td>
                </tr>
            @else
                @foreach ($faculties as $index => $faculty)
                    @php
                        $l1Names = $faculty->courses->where('level', '1')->pluck('name')->toArray();
                        $l2Names = $faculty->courses->where('level', '2')->pluck('name')->toArray();
                        $l3Names = $faculty->courses->where('level', '3')->pluck('name')->toArray();

                        $l1Assess = $assessment->where('faculty', $faculty->name)->whereIn('courses', $l1Names);
                        $l2Assess = $assessment->where('faculty', $faculty->name)->whereIn('courses', $l2Names);
                        $l3Assess = $assessment->where('faculty', $faculty->name)->whereIn('courses', $l3Names);

                        $facAssess = $assessment->where('faculty', $faculty->name);
                    @endphp
                    <tr>
                        <td style="text-align:center">{{ $index + 1 }}</td>
                        <td>{{ $faculty->name }}</td>
                        <td style="text-align:center">{{ $faculty->courses->count() }}</td>
                        <td style="text-align:center">{{ $facAssess->where('result', '2')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $facAssess->where('result', '3')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $facAssess->where('result', '4')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $facAssess->where('result', '5')->count() ?: '-' }}</td>

                        <td style="text-align:center">{{ count($l1Names) }}</td>
                        <td style="text-align:center">{{ $l1Assess->where('result', '2')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $l1Assess->where('result', '3')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $l1Assess->where('result', '4')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $l1Assess->where('result', '5')->count() ?: '-' }}</td>

                        <td style="text-align:center">{{ count($l2Names) }}</td>
                        <td style="text-align:center">{{ $l2Assess->where('result', '2')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $l2Assess->where('result', '3')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $l2Assess->where('result', '4')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $l2Assess->where('result', '5')->count() ?: '-' }}</td>

                        <td style="text-align:center">{{ count($l3Names) }}</td>
                        <td style="text-align:center">{{ $l3Assess->where('result', '2')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $l3Assess->where('result', '3')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $l3Assess->where('result', '4')->count() ?: '-' }}</td>
                        <td style="text-align:center">{{ $l3Assess->where('result', '5')->count() ?: '-' }}</td>
                    </tr>
                @endforeach

                @php
                    // คำนวณยอดรวม (Grand Totals)
                    $totalCourses = $faculties->sum(fn($f) => $f->courses->count());
                    $totalL1Courses = count($allCourseNamesByLevel['1']);
                    $totalL2Courses = count($allCourseNamesByLevel['2']);
                    $totalL3Courses = count($allCourseNamesByLevel['3']);

                    $allL1 = $assessment->whereIn('courses', $allCourseNamesByLevel['1']);
                    $allL2 = $assessment->whereIn('courses', $allCourseNamesByLevel['2']);
                    $allL3 = $assessment->whereIn('courses', $allCourseNamesByLevel['3']);

                    // Helper Function สำหรับคำนวณ %
                    $calcPercent = function ($count, $total) {
                        return $total > 0 ? round(($count / $total) * 100, 2) : 0;
                    };
                @endphp

                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td colspan="2" style="text-align:center">รวม (จำนวนหลักสูตร)</td>
                    <td style="text-align:center">{{ $totalCourses }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '2')->count() }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '3')->count() }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '4')->count() }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '5')->count() }}</td>

                    <td style="text-align:center">{{ $totalL1Courses }}</td>
                    <td style="text-align:center">{{ $allL1->where('result', '2')->count() }}</td>
                    <td style="text-align:center">{{ $allL1->where('result', '3')->count() }}</td>
                    <td style="text-align:center">{{ $allL1->where('result', '4')->count() }}</td>
                    <td style="text-align:center">{{ $allL1->where('result', '5')->count() }}</td>

                    <td style="text-align:center">{{ $totalL2Courses }}</td>
                    <td style="text-align:center">{{ $allL2->where('result', '2')->count() }}</td>
                    <td style="text-align:center">{{ $allL2->where('result', '3')->count() }}</td>
                    <td style="text-align:center">{{ $allL2->where('result', '4')->count() }}</td>
                    <td style="text-align:center">{{ $allL2->where('result', '5')->count() }}</td>

                    <td style="text-align:center">{{ $totalL3Courses }}</td>
                    <td style="text-align:center">{{ $allL3->where('result', '2')->count() }}</td>
                    <td style="text-align:center">{{ $allL3->where('result', '3')->count() }}</td>
                    <td style="text-align:center">{{ $allL3->where('result', '4')->count() }}</td>
                    <td style="text-align:center">{{ $allL3->where('result', '5')->count() }}</td>
                </tr>

                <tr style="background-color: #f9f9f9;">
                    <td colspan="2" style="text-align:center">เปอร์เซนต์ (%)</td>
                    <td style="text-align:center">100</td>
                    <td style="text-align:center">
                        {{ $calcPercent($assessment->where('result', '2')->count(), $totalCourses) }}</td>
                    <td style="text-align:center">
                        {{ $calcPercent($assessment->where('result', '3')->count(), $totalCourses) }}</td>
                    <td style="text-align:center">
                        {{ $calcPercent($assessment->where('result', '4')->count(), $totalCourses) }}</td>
                    <td style="text-align:center">
                        {{ $calcPercent($assessment->where('result', '5')->count(), $totalCourses) }}</td>

                    <td style="text-align:center">100</td>
                    <td style="text-align:center">{{ $calcPercent($allL1->where('result', '2')->count(), $totalL1Courses) }}
                    </td>
                    <td style="text-align:center">{{ $calcPercent($allL1->where('result', '3')->count(), $totalL1Courses) }}
                    </td>
                    <td style="text-align:center">{{ $calcPercent($allL1->where('result', '4')->count(), $totalL1Courses) }}
                    </td>
                    <td style="text-align:center">{{ $calcPercent($allL1->where('result', '5')->count(), $totalL1Courses) }}
                    </td>

                    <td style="text-align:center">100</td>
                    <td style="text-align:center">{{ $calcPercent($allL2->where('result', '2')->count(), $totalL2Courses) }}
                    </td>
                    <td style="text-align:center">{{ $calcPercent($allL2->where('result', '3')->count(), $totalL2Courses) }}
                    </td>
                    <td style="text-align:center">{{ $calcPercent($allL2->where('result', '4')->count(), $totalL2Courses) }}
                    </td>
                    <td style="text-align:center">{{ $calcPercent($allL2->where('result', '5')->count(), $totalL2Courses) }}
                    </td>

                    <td style="text-align:center">100</td>
                    <td style="text-align:center">{{ $calcPercent($allL3->where('result', '2')->count(), $totalL3Courses) }}
                    </td>
                    <td style="text-align:center">{{ $calcPercent($allL3->where('result', '3')->count(), $totalL3Courses) }}
                    </td>
                    <td style="text-align:center">{{ $calcPercent($allL3->where('result', '4')->count(), $totalL3Courses) }}
                    </td>
                    <td style="text-align:center">{{ $calcPercent($allL3->where('result', '5')->count(), $totalL3Courses) }}
                    </td>
                </tr>
            @endif
        </tbody>

    </table>

</body>

</html>