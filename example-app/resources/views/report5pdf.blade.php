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
        $type1CourseIds = \App\Models\CourseAssessor::where('assessment_type', '3')->pluck('course_id')->toArray();
        $totalType1All = $faculties->flatMap->courses->whereIn('id', $type1CourseIds)->count();
    @endphp
    <h2>
        รายงานสรุปผลการตรวจประเมินภายใน ระดับหลักสูตร
        รายงานที่ 5 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน ระดับหลักสูตร ตามเกณฑ์ AUN-QA Version 4.0<br>
        (Overall Verdict) ประจำปีการศึกษา {{ $selectedThaiYear }} (ตรวจประเมินแบบเต็ม (2วัน)
        ประธานกรรมการเป็นผู้ทรงคุณวุฒิภายนอก<br>
        ขึ้นทะเบียนรายชื่อ ที่ประชุมอธิการบดีแห่งประเทศไทย(ทปอ.) จำนวน
        {{ $totalType1All > 0 ? $totalType1All : '0' }} หลักสูตร)
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
                        // กรองหลักสูตรของคณะเฉพาะที่เป็น Type 3 (ตามที่ส่งมาจาก Controller)
                        $facultyCourses = $faculty->courses->whereIn('id', $type1CourseIds);

                        // แยกกลุ่มตาม Level
                        $l1Ids = $facultyCourses->where('level', '1')->pluck('id')->toArray();
                        $l2Ids = $facultyCourses->where('level', '2')->pluck('id')->toArray();
                        $l3Ids = $facultyCourses->where('level', '3')->pluck('id')->toArray();
                    @endphp
                    <tr>
                        <td style="text-align:center">{{ $index + 1 }}</td>
                        <td style="white-space: nowrap;">{{ $faculty->name }}</td>

                        {{-- ภาพรวมทั้งคณะ --}}
                        <td style="text-align:center">{{ $facultyCourses->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->where('result', '2')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->where('result', '3')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->where('result', '4')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->where('faculty', $faculty->name)->where('result', '5')->count() ?: '-' }}</td>

                        {{-- ระดับปริญญาตรี (Level 1) --}}
                        <td style="text-align:center">{{ count($l1Ids) ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l1Ids)->where('result', '2')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l1Ids)->where('result', '3')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l1Ids)->where('result', '4')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l1Ids)->where('result', '5')->count() ?: '-' }}</td>

                        {{-- ระดับปริญญาโท (Level 2) --}}
                        <td style="text-align:center">{{ count($l2Ids) ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l2Ids)->where('result', '2')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l2Ids)->where('result', '3')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l2Ids)->where('result', '4')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l2Ids)->where('result', '5')->count() ?: '-' }}</td>

                        {{-- ระดับปริญญาเอก (Level 3) --}}
                        <td style="text-align:center">{{ count($l3Ids) ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l3Ids)->where('result', '2')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l3Ids)->where('result', '3')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l3Ids)->where('result', '4')->count() ?: '-' }}</td>
                        <td style="text-align:center">
                            {{ $assessment->whereIn('course_id', $l3Ids)->where('result', '5')->count() ?: '-' }}</td>
                    </tr>
                @endforeach

                {{-- แถวสรุปรวม (Footer) --}}
                @php
                    // ดึง ID ทั้งหมดแยกตาม Level เพื่อใช้คำนวณแถวสุดท้าย
                    $allType1Courses = $faculties->flatMap->courses->whereIn('id', $type1CourseIds);
                    $allL1Ids = $allType1Courses->where('level', '1')->pluck('id')->toArray();
                    $allL2Ids = $allType1Courses->where('level', '2')->pluck('id')->toArray();
                    $allL3Ids = $allType1Courses->where('level', '3')->pluck('id')->toArray();
                @endphp
                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td colspan="2" style="text-align:center">รวม (จำนวนหลักสูตร)</td>

                    {{-- รวมภาพรวม --}}
                    <td style="text-align:center">{{ $assessment->count() }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '2')->count() }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '3')->count() }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '4')->count() }}</td>
                    <td style="text-align:center">{{ $assessment->where('result', '5')->count() }}</td>

                    {{-- รวม ป.ตรี --}}
                    <td style="text-align:center">{{ count($allL1Ids) }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL1Ids)->where('result', '2')->count() }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL1Ids)->where('result', '3')->count() }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL1Ids)->where('result', '4')->count() }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL1Ids)->where('result', '5')->count() }}</td>

                    {{-- รวม ป.โท --}}
                    <td style="text-align:center">{{ count($allL2Ids) }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL2Ids)->where('result', '2')->count() }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL2Ids)->where('result', '3')->count() }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL2Ids)->where('result', '4')->count() }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL2Ids)->where('result', '5')->count() }}</td>

                    {{-- รวม ป.เอก --}}
                    <td style="text-align:center">{{ count($allL3Ids) }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL3Ids)->where('result', '2')->count() }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL3Ids)->where('result', '3')->count() }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL3Ids)->where('result', '4')->count() }}</td>
                    <td style="text-align:center">
                        {{ $assessment->whereIn('course_id', $allL3Ids)->where('result', '5')->count() }}</td>
                </tr>
            @endif
        </tbody>

    </table>

</body>

</html>