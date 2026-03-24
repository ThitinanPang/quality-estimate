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

    <span>รายงานสรุปผลการตรวจประเมินภายใน ระดับหลักสูตร <br> รายงานที่ 1 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน
        ระดับหลักสูตรองค์ประกอบที่ 1 การกำกับมาตรฐาน</span>

    <table>
        <thead>
            <tr>
                <th rowspan="2">ที่</th>
                <th rowspan="2">ส่วนงานคณะ/วิทยาลัย</th>
                <th rowspan="2">จำนวนหลักสูตร</th>
                <th colspan="2">ผลการประเมินองค์ประกอบที่ 1 <br> (การกำกับมาตรฐาน)</th>
                <th rowspan="2">หมายเหตุ</th>
            </tr>
            <tr>
                <th>เป็นไปตามเกณฑ์</th>
                <th>ไม่เป็นไปตามเกณฑ์</th>
            </tr>
        </thead>

        <tbody>
            {{-- เช็คว่าผลรวมจำนวนหลักสูตรเป็น 0 หรือไม่ --}}
            @if($faculties->sum('courses_count') == 0)
                <tr>
                    <td colspan="6" style="text-align: center;">ไม่มีข้อมูล</td>
                </tr>
            @else
                @foreach ($faculties as $index => $faculty)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $faculty->name }}</td>
                        <td>{{ $faculty->courses_count }}</td>
                        <td>{{ $faculty->total_pass }}</td>
                        <td>{{ $faculty->total_fail }}</td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="2">รวม</td>
                    <td>{{ $faculties->sum('courses_count') }}</td>
                    <td>{{ $faculties->sum('total_pass') }}</td>
                    <td>{{ $faculties->sum('total_fail') }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>