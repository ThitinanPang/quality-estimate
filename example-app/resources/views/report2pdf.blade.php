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

                    <tr>

                        <td style="text-align:center">{{ $index + 1 }}</td>

                        <td>{{ $faculty->name }}</td>

                        <td style="text-align:center">{{ $faculty->courses->count() }}</td>

                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->where('result', '2')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->where('result', '3')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->where('result', '4')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->where('result', '5')->count() ?: '-' }}</td>

                        <td style="text-align:center">{{ $faculty->courses->where('level', '1')->count() }}</td>
                        <td style="text-align:center">-</td>
                        <td style="text-align:center">-</td>
                        <td style="text-align:center">-</td>
                        <td style="text-align:center">-</td>

                        <td style="text-align:center">{{ $faculty->courses->where('level', '2')->count() }}</td>
                        <td style="text-align:center">-</td>
                        <td style="text-align:center">-</td>
                        <td style="text-align:center">-</td>
                        <td style="text-align:center">-</td>

                        <td style="text-align:center">{{ $faculty->courses->where('level', '3')->count() }}</td>
                        <td style="text-align:center">-</td>
                        <td style="text-align:center">-</td>
                        <td style="text-align:center">-</td>
                        <td style="text-align:center">-</td>

                    </tr>

                @endforeach


                <tr>

                    <td colspan="2" style="text-align:center">รวม (จำนวนหลักสูตร)</td>

                    <td style="text-align:center">
                        {{ $faculties->sum(fn($faculty) => $faculty->courses->count()) }}
                    </td>

                    <td style="text-align:center">
                        {{ $faculties->sum(fn($faculty) => $assessment->where('faculty', $faculty->name)->where('result', '2')->count()) }}
                    </td>
                    <td style="text-align:center">
                        {{ $faculties->sum(fn($faculty) => $assessment->where('faculty', $faculty->name)->where('result', '3')->count()) }}
                    </td>
                    <td style="text-align:center">
                        {{ $faculties->sum(fn($faculty) => $assessment->where('faculty', $faculty->name)->where('result', '4')->count()) }}
                    </td>
                    <td style="text-align:center">
                        {{ $faculties->sum(fn($faculty) => $assessment->where('faculty', $faculty->name)->where('result', '5')->count()) }}
                    </td>

                    <td style="text-align:center">
                        {{ $faculties->sum(fn($faculty) => $faculty->courses->where('level', '1')->count()) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td style="text-align:center">
                        {{ $faculties->sum(fn($faculty) => $faculty->courses->where('level', '2')->count()) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td style="text-align:center">
                        {{ $faculties->sum(fn($faculty) => $faculty->courses->where('level', '3')->count()) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>


                <tr>

                    <td colspan="2" style="text-align:center">เปอร์เซนต์ (%)</td>

                    <td style="text-align:center">100</td>

                    @php
                        $totalCourses = $faculties->sum(fn($faculty) => $faculty->courses->count());
                    @endphp

                    <td style="text-align:center">
                        {{ $totalCourses ? round(($assessment->where('result', '2')->count() / $totalCourses) * 100, 2) : 0 }}
                    </td>

                    <td style="text-align:center">
                        {{ $totalCourses ? round(($assessment->where('result', '3')->count() / $totalCourses) * 100, 2) : 0 }}
                    </td>

                    <td style="text-align:center">
                        {{ $totalCourses ? round(($assessment->where('result', '4')->count() / $totalCourses) * 100, 2) : 0 }}
                    </td>

                    <td style="text-align:center">
                        {{ $totalCourses ? round(($assessment->where('result', '5')->count() / $totalCourses) * 100, 2) : 0 }}
                    </td>

                    <td style="text-align:center">100</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td style="text-align:center">100</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td style="text-align:center">100</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>

            @endif

        </tbody>

    </table>

</body>

</html>