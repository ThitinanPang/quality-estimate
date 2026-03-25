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
                        // 1. กรองหลักสูตรที่เป็น Type 3 ของคณะนี้
                        $facultyCourses = $faculty->courses->whereIn('id', $type1CourseIds);

                        // 2. ดึง "ชื่อหลักสูตร" แยกตามระดับ (เหมือนใน Excel)
                        $l1Names = $facultyCourses->where('level', '1')->pluck('name')->toArray();
                        $l2Names = $facultyCourses->where('level', '2')->pluck('name')->toArray();
                        $l3Names = $facultyCourses->where('level', '3')->pluck('name')->toArray();
                        $allNames = $facultyCourses->pluck('name')->toArray(); // ทั้งคณะ
                    @endphp
                    <tr>
                        <td style="text-align:center">{{ $index + 1 }}</td>
                        <td style="white-space: nowrap;">{{ $faculty->name }}</td>

                        {{-- ภาพรวมทั้งคณะ --}}
                        <td style="text-align:center">{{ $facultyCourses->count() ?: '-' }}</td>
                        @for ($i = 2; $i <= 5; $i++)
                            <td style="text-align:center">
                                {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $allNames)->where('result', (string) $i)->count() ?: '-' }}
                            </td>
                        @endfor

                        {{-- ระดับปริญญาตรี (Level 1) --}}
                        <td style="text-align:center">{{ count($l1Names) ?: '-' }}</td>
                        @for ($i = 2; $i <= 5; $i++)
                            <td style="text-align:center">
                                {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $l1Names)->where('result', (string) $i)->count() ?: '-' }}
                            </td>
                        @endfor

                        {{-- ระดับปริญญาโท (Level 2) --}}
                        <td style="text-align:center">{{ count($l2Names) ?: '-' }}</td>
                        @for ($i = 2; $i <= 5; $i++)
                            <td style="text-align:center">
                                {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $l2Names)->where('result', (string) $i)->count() ?: '-' }}
                            </td>
                        @endfor

                        {{-- ระดับปริญญาเอก (Level 3) --}}
                        <td style="text-align:center">{{ count($l3Names) ?: '-' }}</td>
                        @for ($i = 2; $i <= 5; $i++)
                            <td style="text-align:center">
                                {{ $assessment->where('faculty', $faculty->name)->whereIn('courses', $l3Names)->where('result', (string) $i)->count() ?: '-' }}
                            </td>
                        @endfor
                    </tr>
                @endforeach

                {{-- แถวสรุปรวม (Footer) --}}
                @php
                    // 1. ดึงรายชื่อหลักสูตร Type 3 ของทุกคณะแยกตามระดับ (ดึงเป็น Name เพื่อใช้กรอง Collection)
                    $allL1Names = $faculties->flatMap->courses->where('level', '1')->whereIn('id', $type1CourseIds)->pluck('name')->toArray();
                    $allL2Names = $faculties->flatMap->courses->where('level', '2')->whereIn('id', $type1CourseIds)->pluck('name')->toArray();
                    $allL3Names = $faculties->flatMap->courses->where('level', '3')->whereIn('id', $type1CourseIds)->pluck('name')->toArray();

                    // 2. รวมรายชื่อหลักสูตรทั้งหมด
                    $allType1Names = array_merge($allL1Names, $allL2Names, $allL3Names);

                    // 3. กรอง Assessment เฉพาะหลักสูตรที่อยู่ในรายการ Type 3 เท่านั้น
                    $summaryOverall = $assessment->whereIn('courses', $allType1Names);
                    $summaryL1 = $assessment->whereIn('courses', $allL1Names);
                    $summaryL2 = $assessment->whereIn('courses', $allL2Names);
                    $summaryL3 = $assessment->whereIn('courses', $allL3Names);
                @endphp

                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td colspan="2" style="text-align:center">รวม (จำนวนหลักสูตร)</td>

                    {{-- รวมภาพรวมทั้งมหาวิทยาลัย --}}
                    <td style="text-align:center">{{ count($allType1Names) }}</td>
                    @for ($i = 2; $i <= 5; $i++)
                        <td style="text-align:center">{{ $summaryOverall->where('result', (string) $i)->count() }}</td>
                    @endfor

                    {{-- รวม ป.ตรี (Level 1) --}}
                    <td style="text-align:center">{{ count($allL1Names) }}</td>
                    @for ($i = 2; $i <= 5; $i++)
                        <td style="text-align:center">{{ $summaryL1->where('result', (string) $i)->count() }}</td>
                    @endfor

                    {{-- รวม ป.โท (Level 2) --}}
                    <td style="text-align:center">{{ count($allL2Names) }}</td>
                    @for ($i = 2; $i <= 5; $i++)
                        <td style="text-align:center">{{ $summaryL2->where('result', (string) $i)->count() }}</td>
                    @endfor

                    {{-- รวม ป.เอก (Level 3) --}}
                    <td style="text-align:center">{{ count($allL3Names) }}</td>
                    @for ($i = 2; $i <= 5; $i++)
                        <td style="text-align:center">{{ $summaryL3->where('result', (string) $i)->count() }}</td>
                    @endfor
                </tr>
            @endif
        </tbody>

    </table>

</body>

</html>