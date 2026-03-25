<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'TH Sarabun New';
            font-size: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }

        h2 {
            font-weight: normal;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <h2>
        รายงานสรุปผลการตรวจประเมินภายใน ระดับหลักสูตร
        รายงานที่ 6 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน ระดับหลักสูตร
        ตามเกณฑ์ AUN-QA ปีการศึกษา {{ $selectedThaiYear }} (ร้อยละ) จำแนกตามลำดับ
    </h2>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Rating Scale</th>
                <th colspan="4">Programme</th>
                <th colspan="3">Resource</th>
                <th colspan="1">Results</th>
            </tr>
            <tr>
                <th>AUN-QA<br>1</th>
                <th>AUN-QA<br>2</th>
                <th>AUN-QA<br>3</th>
                <th>AUN-QA<br>4</th>
                <th>AUN-QA<br>5</th>
                <th>AUN-QA<br>6</th>
                <th>AUN-QA<br>7</th>
                <th>AUN-QA<br>8</th>
            </tr>
        </thead>

        <tbody>
            @if($assessmentData->count() == 0)
                <tr>
                    <td colspan="9">ไม่มีข้อมูล</td>
                </tr>
            @else
                @for ($level = 5; $level >= 1; $level--)
                    <tr>
                        <td>ระดับ {{ $level }}</td>

                        @for ($i = 0; $i < 8; $i++)
                            @php
                                $count = $stats[$i][$level] ?? 0;
                                $percentage = ($totalRecords > 0) ? ($count / $totalRecords) * 100 : 0;
                            @endphp
                            <td>
                                {{ $count > 0 ? number_format($percentage, 0) : '-' }}
                            </td>
                        @endfor
                    </tr>
                @endfor

                <tr>
                    <td>&nbsp;</td>
                    @for ($i = 0; $i < 8; $i++)
                        <td>100</td>
                    @endfor
                </tr>
            @endif
        </tbody>
    </table>

</body>
</html>