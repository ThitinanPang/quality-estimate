@extends('layouts.header')

@section('content')
    {{-- รายงานที่ 1 --}}
    <div class="flex flex-col items-center justify-center ml-[110px] mt-[42px]">
        <p class="text-[36px] w-full text-left inline-flex items-center">รายงานสรุปผลการตรวจประเมินภายใน ระดับหลักสูตร
            {{-- export เป็น excel --}}
            <a href="{{ route('report.export', ['$yearReport1' => request('$yearReport1')]) }}">
                <svg class="ml-3" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19.4883 11.75H15.9895V10.0006H19.4883V11.75ZM19.4883 12.7496H15.9895V14.499H19.4883V12.7496ZM19.4883 4.50244H15.9895V6.25183H19.4883V4.50244ZM19.4883 7.2515H15.9895V9.00088H19.4883V7.2515ZM19.4883 15.4987H15.9895V17.2481H19.4883V15.4987ZM21.905 18.9475C21.805 19.4673 21.1802 19.4798 20.7629 19.4973H12.9906V21.7465H11.4386L0 19.7472V2.25573L11.5061 0.253906H12.9906V2.24572H20.4955C20.9179 2.26321 21.3827 2.23322 21.7501 2.48562C22.0075 2.85549 21.9825 3.32536 22 3.75019L21.99 16.7607C21.9775 17.488 22.0574 18.2303 21.905 18.9475ZM9.16433 15.0263C8.4746 13.6269 7.77236 12.2373 7.08507 10.8378C7.76484 9.47573 8.43464 8.10872 9.1019 6.74167C8.53458 6.76917 7.96727 6.80415 7.40248 6.84415C6.9801 7.87128 6.48777 8.87095 6.15789 9.93309C5.8505 8.93093 5.44315 7.96624 5.07079 6.98909C4.52096 7.01908 3.97113 7.05156 3.42134 7.08405C4.00112 8.36366 4.61845 9.62565 5.18074 10.9127C4.51847 12.1623 3.89868 13.4294 3.25639 14.6865C3.80368 14.7089 4.35102 14.7315 4.89831 14.7389C5.28821 13.7443 5.77302 12.7871 6.11291 11.7725C6.41781 12.8621 6.93511 13.8692 7.35999 14.9114C7.96228 14.9539 8.56204 14.9914 9.16433 15.0263ZM20.808 3.43265H12.9906V4.50244H14.9899V6.25183H12.9906V7.2515H14.9899V9.00088H12.9906V10.0006H14.9899V11.75H12.9906V12.7496H14.9899V14.499H12.9906V15.4987H14.9899V17.2481H12.9906V18.4038H20.808V3.43265Z"
                        fill="black" />
                </svg>
            </a>
            {{-- export เป็น pdf --}}
            <a href="{{ route('report.export.pdf', ['$yearReport1' => request('$yearReport1')]) }}" target="_blank" rel="noopener">
                <svg class="ml-3" width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.5 0H10.7779C11.1425 7.78764e-05 11.4922 0.144995 11.75 0.402875L16.8471 5.5C17.105 5.7578 17.2499 6.10748 17.25 6.47212V19.25C17.25 19.9793 16.9603 20.6788 16.4445 21.1945C15.9288 21.7103 15.2293 22 14.5 22H3.5C2.77065 22 2.07118 21.7103 1.55546 21.1945C1.03973 20.6788 0.75 19.9793 0.75 19.25V2.75C0.75 2.02065 1.03973 1.32118 1.55546 0.805456C2.07118 0.289731 2.77065 0 3.5 0ZM11.0625 2.0625V4.8125C11.0625 5.17717 11.2074 5.52691 11.4652 5.78477C11.7231 6.04263 12.0728 6.1875 12.4375 6.1875H15.1875L11.0625 2.0625ZM3.72687 18.7935C3.85063 19.041 4.04313 19.2651 4.32913 19.3696C4.61375 19.4727 4.89563 19.4246 5.12663 19.3284C5.56388 19.1496 5.99975 18.7289 6.39988 18.2476C6.85775 17.6962 7.339 16.973 7.80375 16.1714C8.70106 15.9058 9.61984 15.719 10.5496 15.6131C10.9621 16.1397 11.3884 16.5935 11.8009 16.9194C12.1859 17.2219 12.63 17.4735 13.0851 17.4928C13.333 17.5046 13.5783 17.4383 13.7864 17.303C13.9995 17.1641 14.1576 16.9634 14.2731 16.731C14.3969 16.4821 14.4725 16.2222 14.4629 15.9569C14.4546 15.6951 14.3576 15.444 14.1879 15.2446C13.8771 14.8734 13.3684 14.6946 12.8679 14.6052C12.2607 14.5117 11.6447 14.4886 11.0322 14.5365C10.5153 13.8052 10.0644 13.0294 9.68475 12.2182C10.0285 11.3107 10.2856 10.4528 10.3997 9.7515C10.4492 9.45175 10.4754 9.16575 10.4657 8.90725C10.4639 8.65067 10.4043 8.3978 10.2911 8.1675C10.2259 8.04042 10.1332 7.92945 10.0198 7.84264C9.90631 7.75584 9.77496 7.69537 9.63525 7.66562C9.3575 7.6065 9.0715 7.66563 8.80888 7.7715C8.2905 7.97775 8.01688 8.41775 7.91375 8.90312C7.81337 9.37062 7.85875 9.91512 7.977 10.4651C8.098 11.0234 8.30425 11.6311 8.56825 12.2458C8.14498 13.2955 7.65731 14.3182 7.108 15.3079C6.39952 15.5312 5.71651 15.8285 5.07025 16.1947C4.5615 16.4972 4.10912 16.8547 3.83687 17.2769C3.54812 17.7251 3.45875 18.2586 3.72687 18.7935Z"
                        fill="black" />
                </svg>
            </a>
        </p>
        <form method="GET">
            <div
                class="w-[236px] h-[46px] bg-[#FFCE00] rounded-[24px] absolute right-[50px] top-[170px] text-[20px] items-center flex justify-center">
                ปีการศึกษา
                <select name="year_report1" id="thai-year-1" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
            </div>
        </form>
        <p class="text-[24px] w-full text-left">รายงานที่ 1 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน ระดับหลักสูตร
            องค์ประกอบที่ 1 การกำกับมาตรฐาน </p>
    </div>
    <div class="flex items-center justify-center ml-[110px] mt-[30px] w-[1364px]">
        <table class="w-[1364px] h-[100px]">
            <thead class="bg-[#FFCE00]">
                <tr>
                    <th scope="col" rowspan="2" class="text-center align-middle px-4 border text-[20px]">ที่</th>
                    <th scope="col" rowspan="2" class="text-center align-middle px-4 border text-[20px]">
                        ส่วนงานคณะ/วิทยาลัย</th>
                    <th scope="col" rowspan="2" class="text-center align-middle px-4 border text-[20px]">
                        จำนวน<br>หลักสูตร</th>
                    <th scope="col" colspan="2" class="text-center align-middle px-4 border text-[20px]">
                        ผลการประเมินองค์ประกอบที่ 1
                        <br>(การกำกับมาตรฐาน)
                    </th>
                    <th scope="col" rowspan="2" class="text-center align-middle px-4 border text-[20px]">หมายเหตุ</th>
                </tr>
                <tr>
                    <th scope="col" class="text-center align-middle px-4 border text-[20px]">เป็นไปตามเกณฑ์</th>
                    <th scope="col" class="text-center align-middle px-4 border text-[20px]">ไม่เป็นไปตามเกณฑ์</th>
                </tr>
            </thead>
            <tbody>
                @if($faculties->sum('courses_count') == 0)
                    <tr>
                        <td colspan="6" class="border text-center text-[20px]">ไม่มีข้อมูล</td>
                    </tr>
                @else
                    @foreach ($faculties as $index => $faculty)
                        <tr>
                            <td class="text-center align-middle px-4 border text-[20px]">{{ $index + 1 }}</td>
                            <td class="text-center align-middle px-4 border text-[20px]">{{ $faculty->name }}</td>
                            {{-- จำนวนหลักสูตร --}}
                            <td class="text-center align-middle px-4 border text-[20px]">
                                {{ ($faculty->courses_count ?? 0) == 0 ? '-' : $faculty->courses_count }}
                            </td>
                            {{-- เป็นไปตามเกณฑ์ --}}
                            <td class="text-center align-middle px-4 border text-[20px]">
                                {{ $faculty->total_pass == 0 ? '-' : $faculty->total_pass }}
                            </td>
                            {{-- ไม่เป็นไปตามเกณฑ์ --}}
                            <td class="text-center align-middle px-4 border text-[20px]">
                                {{ $faculty->total_fail == 0 ? '-' : $faculty->total_fail }}
                            </td>
                            <td class="text-center align-middle px-4 border text-[20px]"></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-center align-middle px-4 border text-[20px]">รวม</td>
                        <td class="text-center align-middle px-4 border text-[20px]">
                            {{ $faculties->sum('courses_count') == 0 ? '-' : $faculties->sum('courses_count') }}
                        </td>
                        <td class="text-center align-middle px-4 border text-[20px]">
                            {{ $faculties->sum('total_pass') == 0 ? '-' : $faculties->sum('total_pass') }}
                        </td>
                        <td class="text-center align-middle px-4 border text-[20px]">
                            {{ $faculties->sum('total_fail') == 0 ? '-' : $faculties->sum('total_fail') }}
                        </td>
                        <td class="text-center align-middle px-4 border text-[20px]"></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    {{-- รายงานที่ 2 --}}
    <div class="flex">
        <p class="text-[36px] text-left items-center ml-[110px] mt-[42px] inline-flex">รายงานสรุปผลการตรวจประเมินภายใน
            ระดับหลักสูตร
            {{-- export เป็น excel --}}
            <a href="{{ route('report2.export.excel', ['year_report2' => request('year_report2')]) }}">
                <svg class="ml-3" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19.4883 11.75H15.9895V10.0006H19.4883V11.75ZM19.4883 12.7496H15.9895V14.499H19.4883V12.7496ZM19.4883 4.50244H15.9895V6.25183H19.4883V4.50244ZM19.4883 7.2515H15.9895V9.00088H19.4883V7.2515ZM19.4883 15.4987H15.9895V17.2481H19.4883V15.4987ZM21.905 18.9475C21.805 19.4673 21.1802 19.4798 20.7629 19.4973H12.9906V21.7465H11.4386L0 19.7472V2.25573L11.5061 0.253906H12.9906V2.24572H20.4955C20.9179 2.26321 21.3827 2.23322 21.7501 2.48562C22.0075 2.85549 21.9825 3.32536 22 3.75019L21.99 16.7607C21.9775 17.488 22.0574 18.2303 21.905 18.9475ZM9.16433 15.0263C8.4746 13.6269 7.77236 12.2373 7.08507 10.8378C7.76484 9.47573 8.43464 8.10872 9.1019 6.74167C8.53458 6.76917 7.96727 6.80415 7.40248 6.84415C6.9801 7.87128 6.48777 8.87095 6.15789 9.93309C5.8505 8.93093 5.44315 7.96624 5.07079 6.98909C4.52096 7.01908 3.97113 7.05156 3.42134 7.08405C4.00112 8.36366 4.61845 9.62565 5.18074 10.9127C4.51847 12.1623 3.89868 13.4294 3.25639 14.6865C3.80368 14.7089 4.35102 14.7315 4.89831 14.7389C5.28821 13.7443 5.77302 12.7871 6.11291 11.7725C6.41781 12.8621 6.93511 13.8692 7.35999 14.9114C7.96228 14.9539 8.56204 14.9914 9.16433 15.0263ZM20.808 3.43265H12.9906V4.50244H14.9899V6.25183H12.9906V7.2515H14.9899V9.00088H12.9906V10.0006H14.9899V11.75H12.9906V12.7496H14.9899V14.499H12.9906V15.4987H14.9899V17.2481H12.9906V18.4038H20.808V3.43265Z"
                        fill="black" />
                </svg>
            </a>
            {{-- export เป็น pdf --}}
            <a href="{{ route('report2.export.pdf', ['year_report2' => request('year_report2')]) }}">
                <svg class="ml-3" width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.5 0H10.7779C11.1425 7.78764e-05 11.4922 0.144995 11.75 0.402875L16.8471 5.5C17.105 5.7578 17.2499 6.10748 17.25 6.47212V19.25C17.25 19.9793 16.9603 20.6788 16.4445 21.1945C15.9288 21.7103 15.2293 22 14.5 22H3.5C2.77065 22 2.07118 21.7103 1.55546 21.1945C1.03973 20.6788 0.75 19.9793 0.75 19.25V2.75C0.75 2.02065 1.03973 1.32118 1.55546 0.805456C2.07118 0.289731 2.77065 0 3.5 0ZM11.0625 2.0625V4.8125C11.0625 5.17717 11.2074 5.52691 11.4652 5.78477C11.7231 6.04263 12.0728 6.1875 12.4375 6.1875H15.1875L11.0625 2.0625ZM3.72687 18.7935C3.85063 19.041 4.04313 19.2651 4.32913 19.3696C4.61375 19.4727 4.89563 19.4246 5.12663 19.3284C5.56388 19.1496 5.99975 18.7289 6.39988 18.2476C6.85775 17.6962 7.339 16.973 7.80375 16.1714C8.70106 15.9058 9.61984 15.719 10.5496 15.6131C10.9621 16.1397 11.3884 16.5935 11.8009 16.9194C12.1859 17.2219 12.63 17.4735 13.0851 17.4928C13.333 17.5046 13.5783 17.4383 13.7864 17.303C13.9995 17.1641 14.1576 16.9634 14.2731 16.731C14.3969 16.4821 14.4725 16.2222 14.4629 15.9569C14.4546 15.6951 14.3576 15.444 14.1879 15.2446C13.8771 14.8734 13.3684 14.6946 12.8679 14.6052C12.2607 14.5117 11.6447 14.4886 11.0322 14.5365C10.5153 13.8052 10.0644 13.0294 9.68475 12.2182C10.0285 11.3107 10.2856 10.4528 10.3997 9.7515C10.4492 9.45175 10.4754 9.16575 10.4657 8.90725C10.4639 8.65067 10.4043 8.3978 10.2911 8.1675C10.2259 8.04042 10.1332 7.92945 10.0198 7.84264C9.90631 7.75584 9.77496 7.69537 9.63525 7.66562C9.3575 7.6065 9.0715 7.66563 8.80888 7.7715C8.2905 7.97775 8.01688 8.41775 7.91375 8.90312C7.81337 9.37062 7.85875 9.91512 7.977 10.4651C8.098 11.0234 8.30425 11.6311 8.56825 12.2458C8.14498 13.2955 7.65731 14.3182 7.108 15.3079C6.39952 15.5312 5.71651 15.8285 5.07025 16.1947C4.5615 16.4972 4.10912 16.8547 3.83687 17.2769C3.54812 17.7251 3.45875 18.2586 3.72687 18.7935Z"
                        fill="black" />
                </svg>
            </a>
        </p>
        <form method="GET">
            <div
                class="w-[236px] h-[46px] bg-[#FFCE00] rounded-[24px] mt-[40px] ml-[290px] text-[20px] items-center flex justify-center">
                ปีการศึกษา
                <select name="year_report2" id="thai-year-2" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
            </div>
        </form>
    </div>
    <p class="text-[24px] w-full text-left ml-[110px] mt-2">รายงานที่ 2 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน
        ระดับหลักสูตร
        ตามเกณฑ์ AUN-QA Version 4.0 <br> (Overall Verdict) ประจำปีการศึกษา {{ $yearReport2 }}
    </p>
    <div class="flex pl-[110px] w-full pt-[10px] ">
        <div class="w-[1364px] overflow-auto overflow-y-hidden py-3">
            <table class="w-[1364px]">
                <thead class="bg-[#FFCE00]">
                    <tr>
                        <th rowspan="3" class="px-4 border text-[24px]">ที่</th>
                        <th rowspan="3" class="px-4 border text-[24px] whitespace-nowrap">ส่วนงานคณะ/วิทยาลัย</th>
                        <th colspan="5" class="px-4 border text-[20px]">ภาพรวมทั้งคณะ/วิทยาลัย</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาตรี</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาโท</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาเอก</th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                    </tr>
                    <tr>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                    </tr>
                </thead>
                <tbody>
                    @if($assessment->count() == 0)
                        <tr>
                            <td colspan="22" class="border text-center text-[24px]">ไม่มีข้อมูล</td>
                        </tr>
                    @else
                        @foreach ($faculties as $index => $faculty)
                            @php
                                $level1CourseNames = $faculty->courses->where('level', '1')->pluck('name')->toArray();
                                $level1Assessments = $assessment->where('faculty', $faculty->name)->whereIn('courses', $level1CourseNames);
                                $level2CourseNames = $faculty->courses->where('level', '2')->pluck('name')->toArray();
                                $level2Assessments = $assessment->where('faculty', $faculty->name)->whereIn('courses', $level2CourseNames);
                                $level3CourseNames = $faculty->courses->where('level', '3')->pluck('name')->toArray();
                                $level3Assessments = $assessment->where('faculty', $faculty->name)->whereIn('courses', $level3CourseNames);
                                // 
                                $allLevel1CourseNames = $faculties->flatMap->courses->where('level', '1')->pluck('name')->toArray();
                                $allLevel2CourseNames = $faculties->flatMap->courses->where('level', '2')->pluck('name')->toArray();
                                $allLevel3CourseNames = $faculties->flatMap->courses->where('level', '3')->pluck('name')->toArray();
                                $totalLevel1Assessments = $assessment->whereIn('courses', $allLevel1CourseNames);
                                $totalLevel2Assessments = $assessment->whereIn('courses', $allLevel2CourseNames);
                                $totalLevel3Assessments = $assessment->whereIn('courses', $allLevel3CourseNames);
                            @endphp
                            <tr>
                                <td class="px-4 border text-[24px] text-center">{{ $index + 1  }}</td>
                                <td class="px-4 border text-[24px] whitespace-nowrap">{{ $faculty->name }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $faculty->courses->count() }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','2')->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','3')->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','4')->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','5')->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    {{ $faculty->courses->where('level', '1')->count() }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '5')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    {{ $faculty->courses->where('level', '2')->count() }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '5')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    {{ $faculty->courses->where('level', '3')->count() }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '5')->count() ?: '-' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="px-4 border text-[24px] text-center">รวม (จำนวนหลักสูตร)</td>
                            <td class="px-4 border text-[24px] text-center">
                                {{ $faculties->sum(fn($f) => $f->courses->count()) }}
                            </td>
                            {{-- รวมผลประเมินแยกตาม Result (ทุก Level รวมกัน) --}}
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '5')->count() ?: '0' }}</td>

                            {{-- รวม Level 1 --}}
                            <td class="px-4 border text-[24px] text-center">{{ count($allLevel1CourseNames) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '5')->count() ?: '0' }}</td>

                            {{-- รวม Level 2 --}}
                            <td class="px-4 border text-[24px] text-center">{{ count($allLevel2CourseNames) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '5')->count() ?: '0' }}</td>

                            {{-- รวม Level 3 --}}
                            <td class="px-4 border text-[24px] text-center">{{ count($allLevel3CourseNames) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '5')->count() ?: '0' }}</td>
                        </tr>
                        <tr>
                            @php
                                $grandTotalCourses = $faculties->sum(fn($f) => $f->courses->count());
                                $totalL1Courses = $faculties->sum(fn($f) => $f->courses->where('level', '1')->count());
                                $totalL2Courses = $faculties->sum(fn($f) => $f->courses->where('level', '2')->count());
                                $totalL3Courses = $faculties->sum(fn($f) => $f->courses->where('level', '3')->count());
                                $allL1Names = $faculties->flatMap->courses->where('level', '1')->pluck('name')->toArray();
                                $allL2Names = $faculties->flatMap->courses->where('level', '2')->pluck('name')->toArray();
                                $allL3Names = $faculties->flatMap->courses->where('level', '3')->pluck('name')->toArray();
                                $assessL1 = $assessment->whereIn('courses', $allL1Names);
                                $assessL2 = $assessment->whereIn('courses', $allL2Names);
                                $assessL3 = $assessment->whereIn('courses', $allL3Names);
                                $calcPercent = function($count, $total) {
                                    return $total > 0 ? round(($count / $total) * 100, 2) : 0;
                                };
                            @endphp
                            <td colspan="2" class="px-4 border text-[24px] text-center">เปอร์เซนต์ (%)</td>
                            {{-- ภาพรวมทั้งหมด --}}
                            <td class="px-4 border text-[24px] text-center">100</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessment->where('result','2')->count(), $grandTotalCourses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessment->where('result','3')->count(), $grandTotalCourses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessment->where('result','4')->count(), $grandTotalCourses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessment->where('result','5')->count(), $grandTotalCourses) }}</td>

                            {{-- เปอร์เซ็นต์ Level 1 --}}
                            <td class="px-4 border text-[24px] text-center">100</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL1->where('result','2')->count(), $totalL1Courses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL1->where('result','3')->count(), $totalL1Courses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL1->where('result','4')->count(), $totalL1Courses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL1->where('result','5')->count(), $totalL1Courses) }}</td>

                            {{-- เปอร์เซ็นต์ Level 2 --}}
                            <td class="px-4 border text-[24px] text-center">100</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL2->where('result','2')->count(), $totalL2Courses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL2->where('result','3')->count(), $totalL2Courses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL2->where('result','4')->count(), $totalL2Courses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL2->where('result','5')->count(), $totalL2Courses) }}</td>

                            {{-- เปอร์เซ็นต์ Level 3 --}}
                            <td class="px-4 border text-[24px] text-center">100</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL3->where('result','2')->count(), $totalL3Courses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL3->where('result','3')->count(), $totalL3Courses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL3->where('result','4')->count(), $totalL3Courses) }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $calcPercent($assessL3->where('result','5')->count(), $totalL3Courses) }}</td>
                        </tr>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {{-- รายงานที่ 3 --}}
    <div class="flex">
        <p class="text-[36px] text-left items-center ml-[110px] mt-[42px] inline-flex">รายงานสรุปผลการตรวจประเมินภายใน
            ระดับหลักสูตร
            {{-- export เป็น excel --}}
            <a href="{{ route('report3.export.excel', ['year_report3' => request('year_report3')]) }}">
                <svg class="ml-3" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19.4883 11.75H15.9895V10.0006H19.4883V11.75ZM19.4883 12.7496H15.9895V14.499H19.4883V12.7496ZM19.4883 4.50244H15.9895V6.25183H19.4883V4.50244ZM19.4883 7.2515H15.9895V9.00088H19.4883V7.2515ZM19.4883 15.4987H15.9895V17.2481H19.4883V15.4987ZM21.905 18.9475C21.805 19.4673 21.1802 19.4798 20.7629 19.4973H12.9906V21.7465H11.4386L0 19.7472V2.25573L11.5061 0.253906H12.9906V2.24572H20.4955C20.9179 2.26321 21.3827 2.23322 21.7501 2.48562C22.0075 2.85549 21.9825 3.32536 22 3.75019L21.99 16.7607C21.9775 17.488 22.0574 18.2303 21.905 18.9475ZM9.16433 15.0263C8.4746 13.6269 7.77236 12.2373 7.08507 10.8378C7.76484 9.47573 8.43464 8.10872 9.1019 6.74167C8.53458 6.76917 7.96727 6.80415 7.40248 6.84415C6.9801 7.87128 6.48777 8.87095 6.15789 9.93309C5.8505 8.93093 5.44315 7.96624 5.07079 6.98909C4.52096 7.01908 3.97113 7.05156 3.42134 7.08405C4.00112 8.36366 4.61845 9.62565 5.18074 10.9127C4.51847 12.1623 3.89868 13.4294 3.25639 14.6865C3.80368 14.7089 4.35102 14.7315 4.89831 14.7389C5.28821 13.7443 5.77302 12.7871 6.11291 11.7725C6.41781 12.8621 6.93511 13.8692 7.35999 14.9114C7.96228 14.9539 8.56204 14.9914 9.16433 15.0263ZM20.808 3.43265H12.9906V4.50244H14.9899V6.25183H12.9906V7.2515H14.9899V9.00088H12.9906V10.0006H14.9899V11.75H12.9906V12.7496H14.9899V14.499H12.9906V15.4987H14.9899V17.2481H12.9906V18.4038H20.808V3.43265Z"
                        fill="black" />
                </svg>
            </a>
            {{-- export เป็น pdf --}}
            <a href="{{ route('report3.export.pdf', ['year_report3' => request('year_report3')]) }}">
                <svg class="ml-3" width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.5 0H10.7779C11.1425 7.78764e-05 11.4922 0.144995 11.75 0.402875L16.8471 5.5C17.105 5.7578 17.2499 6.10748 17.25 6.47212V19.25C17.25 19.9793 16.9603 20.6788 16.4445 21.1945C15.9288 21.7103 15.2293 22 14.5 22H3.5C2.77065 22 2.07118 21.7103 1.55546 21.1945C1.03973 20.6788 0.75 19.9793 0.75 19.25V2.75C0.75 2.02065 1.03973 1.32118 1.55546 0.805456C2.07118 0.289731 2.77065 0 3.5 0ZM11.0625 2.0625V4.8125C11.0625 5.17717 11.2074 5.52691 11.4652 5.78477C11.7231 6.04263 12.0728 6.1875 12.4375 6.1875H15.1875L11.0625 2.0625ZM3.72687 18.7935C3.85063 19.041 4.04313 19.2651 4.32913 19.3696C4.61375 19.4727 4.89563 19.4246 5.12663 19.3284C5.56388 19.1496 5.99975 18.7289 6.39988 18.2476C6.85775 17.6962 7.339 16.973 7.80375 16.1714C8.70106 15.9058 9.61984 15.719 10.5496 15.6131C10.9621 16.1397 11.3884 16.5935 11.8009 16.9194C12.1859 17.2219 12.63 17.4735 13.0851 17.4928C13.333 17.5046 13.5783 17.4383 13.7864 17.303C13.9995 17.1641 14.1576 16.9634 14.2731 16.731C14.3969 16.4821 14.4725 16.2222 14.4629 15.9569C14.4546 15.6951 14.3576 15.444 14.1879 15.2446C13.8771 14.8734 13.3684 14.6946 12.8679 14.6052C12.2607 14.5117 11.6447 14.4886 11.0322 14.5365C10.5153 13.8052 10.0644 13.0294 9.68475 12.2182C10.0285 11.3107 10.2856 10.4528 10.3997 9.7515C10.4492 9.45175 10.4754 9.16575 10.4657 8.90725C10.4639 8.65067 10.4043 8.3978 10.2911 8.1675C10.2259 8.04042 10.1332 7.92945 10.0198 7.84264C9.90631 7.75584 9.77496 7.69537 9.63525 7.66562C9.3575 7.6065 9.0715 7.66563 8.80888 7.7715C8.2905 7.97775 8.01688 8.41775 7.91375 8.90312C7.81337 9.37062 7.85875 9.91512 7.977 10.4651C8.098 11.0234 8.30425 11.6311 8.56825 12.2458C8.14498 13.2955 7.65731 14.3182 7.108 15.3079C6.39952 15.5312 5.71651 15.8285 5.07025 16.1947C4.5615 16.4972 4.10912 16.8547 3.83687 17.2769C3.54812 17.7251 3.45875 18.2586 3.72687 18.7935Z"
                        fill="black" />
                </svg>
            </a>
        </p>
        <form method="GET">
            <div
                class="w-[236px] h-[46px] bg-[#FFCE00] rounded-[24px] mt-[40px] ml-[290px] text-[20px] items-center flex justify-center">
                ปีการศึกษา
                <select name="year_report3" id="thai-year-3" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
            </div>
        </form>
    </div>
    @php
        $type1CourseIds = \App\Models\CourseAssessor::where('assessment_type', '1')->pluck('course_id')->toArray();
        $totalType1All = $faculties->flatMap->courses->whereIn('id', $type1CourseIds)->count();
    @endphp
    <p class="text-[24px] w-full text-left ml-[110px] mt-2">รายงานที่ 3 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน
        ระดับหลักสูตร
        ตามเกณฑ์ AUN-QA Version 4.0 (Overall Verdict) <br> ประจำปีการศึกษา {{ $yearReport3 }} (ตรวจประเมินแบบหนึ่งวัน จำนวน {{ $totalType1All > 0 ? $totalType1All : '-' }} หลักสูตร)
    </p>
    <div class="flex pl-[110px] w-full pt-[10px]">
        <div class="w-[1364px] overflow-auto overflow-y-hidden py-3">
            <table class="w-[1364px]">
                <thead class="bg-[#FFCE00]">
                    <tr>
                        <th rowspan="3" class="px-4 border text-[24px]">ที่</th>
                        <th rowspan="3" class="px-4 border text-[24px] whitespace-nowrap">ส่วนงานคณะ/วิทยาลัย</th>
                        <th colspan="5" class="px-4 border text-[20px]">ภาพรวมทั้งคณะ/วิทยาลัย</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาตรี</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาโท</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาเอก</th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                    </tr>
                    <tr>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                    </tr>
                </thead>
                <tbody>
                    @if($assessment2->count() == 0)
                        <tr>
                            <td colspan="22" class="border text-center text-[24px]">ไม่มีข้อมูล</td>
                        </tr>
                    @else
                        @foreach ($faculties as $index => $faculty)
                            @php
                                $level1CourseNames = $faculty->courses->where('level', '1')->pluck('name')->toArray();
                                $level1Assessments = $assessment->where('faculty', $faculty->name)->whereIn('courses', $level1CourseNames);
                                $level2CourseNames = $faculty->courses->where('level', '2')->pluck('name')->toArray();
                                $level2Assessments = $assessment->where('faculty', $faculty->name)->whereIn('courses', $level2CourseNames);
                                $level3CourseNames = $faculty->courses->where('level', '3')->pluck('name')->toArray();
                                $level3Assessments = $assessment->where('faculty', $faculty->name)->whereIn('courses', $level3CourseNames);
                                // 
                                $allLevel1CourseNames = $faculties->flatMap->courses->where('level', '1')->pluck('name')->toArray();
                                $allLevel2CourseNames = $faculties->flatMap->courses->where('level', '2')->pluck('name')->toArray();
                                $allLevel3CourseNames = $faculties->flatMap->courses->where('level', '3')->pluck('name')->toArray();
                                $totalLevel1Assessments = $assessment->whereIn('courses', $allLevel1CourseNames);
                                $totalLevel2Assessments = $assessment->whereIn('courses', $allLevel2CourseNames);
                                $totalLevel3Assessments = $assessment->whereIn('courses', $allLevel3CourseNames);
                                //
                                $type1CourseIds = \App\Models\CourseAssessor::where('assessment_type', '1')->pluck('course_id')->toArray();
                                $level1CourseNames = $faculty->courses->where('level', '1')->whereIn('id', $type1CourseIds)->pluck('name')->toArray();
                            @endphp
                            <tr>
                                <td class="px-4 border text-[24px] text-center">{{ $index + 1  }}</td>
                                <td class="px-4 border text-[24px] whitespace-nowrap">{{ $faculty->name }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countType1 = $faculty->courses->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countType1 > 0 ? $countType1 : '-' }}
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','2', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','3', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','4', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','5', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countLevel1Type1 = $faculty->courses->where('level', '1')->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countLevel1Type1 > 0 ? $countLevel1Type1 : '-' }}    
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '5')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countL2 = $faculty->courses->where('level', '2')->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countL2 > 0 ? $countL2 : '-' }}
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '5')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countL3 = $faculty->courses->where('level', '3')->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countL3 > 0 ? $countL3 : '-' }}
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '5')->count() ?: '-' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="px-4 border text-[24px] text-center">รวม (จำนวนหลักสูตร)</td>
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalType1 = $faculties->flatMap->courses->whereIn('id', $type1CourseIds)->count();
                                @endphp
                                {{ $totalType1 > 0 ? $totalType1 : '0' }}
                            </td>
                            {{-- รวมผลประเมินแยกตาม Result (ทุก Level รวมกัน) --}}
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '2', $type1CourseIds)->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '3', $type1CourseIds)->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '4', $type1CourseIds)->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '5', $type1CourseIds)->count() ?: '0' }}</td>

                            {{-- รวม Level 1 --}}
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalL1 = $faculties->flatMap->courses
                                        ->where('level', '1')
                                        ->whereIn('id', $type1CourseIds)
                                        ->count();
                                @endphp
                                {{ $totalL1 > 0 ? $totalL1 : '0' }}
                            </td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '5')->count() ?: '0' }}</td>

                            {{-- รวม Level 2 --}}
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalL2 = $faculties->flatMap->courses
                                        ->where('level', '2')
                                        ->whereIn('id', $type1CourseIds)
                                        ->count();
                                @endphp
                                {{ $totalL2 > 0 ? $totalL2 : '0' }}
                            </td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '5')->count() ?: '0' }}</td>

                            {{-- รวม Level 3 --}}
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalL3 = $faculties->flatMap->courses
                                        ->where('level', '3')
                                        ->whereIn('id', $type1CourseIds)
                                        ->count();
                                @endphp
                                {{ $totalL3 > 0 ? $totalL3 : '0' }}
                            </td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '5')->count() ?: '0' }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {{-- รายงานที่ 4 --}}
    <div class="flex">
        <p class="text-[36px] text-left items-center ml-[110px] mt-[42px] inline-flex">รายงานสรุปผลการตรวจประเมินภายใน
            ระดับหลักสูตร
            {{-- export เป็น excel --}}
            <a href="{{ route('report4.export.excel', ['year_report4' => request('year_report4')]) }}">
                <svg class="ml-3" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19.4883 11.75H15.9895V10.0006H19.4883V11.75ZM19.4883 12.7496H15.9895V14.499H19.4883V12.7496ZM19.4883 4.50244H15.9895V6.25183H19.4883V4.50244ZM19.4883 7.2515H15.9895V9.00088H19.4883V7.2515ZM19.4883 15.4987H15.9895V17.2481H19.4883V15.4987ZM21.905 18.9475C21.805 19.4673 21.1802 19.4798 20.7629 19.4973H12.9906V21.7465H11.4386L0 19.7472V2.25573L11.5061 0.253906H12.9906V2.24572H20.4955C20.9179 2.26321 21.3827 2.23322 21.7501 2.48562C22.0075 2.85549 21.9825 3.32536 22 3.75019L21.99 16.7607C21.9775 17.488 22.0574 18.2303 21.905 18.9475ZM9.16433 15.0263C8.4746 13.6269 7.77236 12.2373 7.08507 10.8378C7.76484 9.47573 8.43464 8.10872 9.1019 6.74167C8.53458 6.76917 7.96727 6.80415 7.40248 6.84415C6.9801 7.87128 6.48777 8.87095 6.15789 9.93309C5.8505 8.93093 5.44315 7.96624 5.07079 6.98909C4.52096 7.01908 3.97113 7.05156 3.42134 7.08405C4.00112 8.36366 4.61845 9.62565 5.18074 10.9127C4.51847 12.1623 3.89868 13.4294 3.25639 14.6865C3.80368 14.7089 4.35102 14.7315 4.89831 14.7389C5.28821 13.7443 5.77302 12.7871 6.11291 11.7725C6.41781 12.8621 6.93511 13.8692 7.35999 14.9114C7.96228 14.9539 8.56204 14.9914 9.16433 15.0263ZM20.808 3.43265H12.9906V4.50244H14.9899V6.25183H12.9906V7.2515H14.9899V9.00088H12.9906V10.0006H14.9899V11.75H12.9906V12.7496H14.9899V14.499H12.9906V15.4987H14.9899V17.2481H12.9906V18.4038H20.808V3.43265Z"
                        fill="black" />
                </svg>
            </a>
            {{-- export เป็น pdf --}}
            <a href="{{ route('report4.export.pdf', ['year_report4' => request('year_report4')]) }}">
                <svg class="ml-3" width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.5 0H10.7779C11.1425 7.78764e-05 11.4922 0.144995 11.75 0.402875L16.8471 5.5C17.105 5.7578 17.2499 6.10748 17.25 6.47212V19.25C17.25 19.9793 16.9603 20.6788 16.4445 21.1945C15.9288 21.7103 15.2293 22 14.5 22H3.5C2.77065 22 2.07118 21.7103 1.55546 21.1945C1.03973 20.6788 0.75 19.9793 0.75 19.25V2.75C0.75 2.02065 1.03973 1.32118 1.55546 0.805456C2.07118 0.289731 2.77065 0 3.5 0ZM11.0625 2.0625V4.8125C11.0625 5.17717 11.2074 5.52691 11.4652 5.78477C11.7231 6.04263 12.0728 6.1875 12.4375 6.1875H15.1875L11.0625 2.0625ZM3.72687 18.7935C3.85063 19.041 4.04313 19.2651 4.32913 19.3696C4.61375 19.4727 4.89563 19.4246 5.12663 19.3284C5.56388 19.1496 5.99975 18.7289 6.39988 18.2476C6.85775 17.6962 7.339 16.973 7.80375 16.1714C8.70106 15.9058 9.61984 15.719 10.5496 15.6131C10.9621 16.1397 11.3884 16.5935 11.8009 16.9194C12.1859 17.2219 12.63 17.4735 13.0851 17.4928C13.333 17.5046 13.5783 17.4383 13.7864 17.303C13.9995 17.1641 14.1576 16.9634 14.2731 16.731C14.3969 16.4821 14.4725 16.2222 14.4629 15.9569C14.4546 15.6951 14.3576 15.444 14.1879 15.2446C13.8771 14.8734 13.3684 14.6946 12.8679 14.6052C12.2607 14.5117 11.6447 14.4886 11.0322 14.5365C10.5153 13.8052 10.0644 13.0294 9.68475 12.2182C10.0285 11.3107 10.2856 10.4528 10.3997 9.7515C10.4492 9.45175 10.4754 9.16575 10.4657 8.90725C10.4639 8.65067 10.4043 8.3978 10.2911 8.1675C10.2259 8.04042 10.1332 7.92945 10.0198 7.84264C9.90631 7.75584 9.77496 7.69537 9.63525 7.66562C9.3575 7.6065 9.0715 7.66563 8.80888 7.7715C8.2905 7.97775 8.01688 8.41775 7.91375 8.90312C7.81337 9.37062 7.85875 9.91512 7.977 10.4651C8.098 11.0234 8.30425 11.6311 8.56825 12.2458C8.14498 13.2955 7.65731 14.3182 7.108 15.3079C6.39952 15.5312 5.71651 15.8285 5.07025 16.1947C4.5615 16.4972 4.10912 16.8547 3.83687 17.2769C3.54812 17.7251 3.45875 18.2586 3.72687 18.7935Z"
                        fill="black" />
                </svg>
            </a>
        </p>
        <form method="GET">
            <div
                class="w-[236px] h-[46px] bg-[#FFCE00] rounded-[24px] mt-[40px] ml-[290px] text-[20px] items-center flex justify-center">
                ปีการศึกษา
                <select name="year_report4" id="thai-year-4" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
            </div>
        </form>
    </div>
    @php
        $type1CourseIds = \App\Models\CourseAssessor::where('assessment_type', '2')->pluck('course_id')->toArray();
        $totalType1All = $faculties->flatMap->courses->whereIn('id', $type1CourseIds)->count();
    @endphp
    <p class="text-[24px] w-full text-left ml-[110px] mt-2">รายงานที่ 4 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน
        ระดับหลักสูตร
        ตามเกณฑ์ AUN-QA Version 4.0 (Overall Verdict) <br> ประจำปีการศึกษา {{ $yearReport3 }} (ตรวจประเมินแบบเต็ม (2วัน) ประธานกรรมการเป็นบุคลภายใน จำนวน {{ $totalType1All > 0 ? $totalType1All : '-' }} หลักสูตร)
    </p>
    <div class="flex pl-[110px] w-full pt-[10px]">
        <div class="w-[1364px] overflow-auto overflow-y-hidden py-3">
            <table class="w-[1364px]">
                <thead class="bg-[#FFCE00]">
                    <tr>
                        <th rowspan="3" class="px-4 border text-[24px]">ที่</th>
                        <th rowspan="3" class="px-4 border text-[24px] whitespace-nowrap">ส่วนงานคณะ/วิทยาลัย</th>
                        <th colspan="5" class="px-4 border text-[20px]">ภาพรวมทั้งคณะ/วิทยาลัย</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาตรี</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาโท</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาเอก</th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                    </tr>
                    <tr>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                    </tr>
                </thead>
                <tbody>
                    @if($assessment3->count() == 0)
                        <tr>
                            <td colspan="22" class="border text-center text-[24px]">ไม่มีข้อมูล</td>
                        </tr>
                    @else
                        @foreach ($faculties as $index => $faculty)
                            @php
                                $type1CourseIds = \App\Models\CourseAssessor::where('assessment_type', '2')->pluck('course_id')->toArray();
                                $assessment = $assessment->whereIn('course_id', $type1CourseIds);
                                $allLevel1CourseNames = $faculties->flatMap->courses->where('level', '1')->pluck('name')->toArray();
                                $allLevel2CourseNames = $faculties->flatMap->courses->where('level', '2')->pluck('name')->toArray();
                                $allLevel3CourseNames = $faculties->flatMap->courses->where('level', '3')->pluck('name')->toArray();
                                $totalLevel1Assessments = $assessment->whereIn('courses', $allLevel1CourseNames);
                                $totalLevel2Assessments = $assessment->whereIn('courses', $allLevel2CourseNames);
                                $totalLevel3Assessments = $assessment->whereIn('courses', $allLevel3CourseNames);
                                $totalType1All = $faculties->flatMap->courses->whereIn('id', $type1CourseIds)->count();
                            @endphp
                            <tr>
                                <td class="px-4 border text-[24px] text-center">{{ $index + 1  }}</td>
                                <td class="px-4 border text-[24px] whitespace-nowrap">{{ $faculty->name }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countType1 = $faculty->courses->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countType1 > 0 ? $countType1 : '-' }}
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','2', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','3', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','4', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','5', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countLevel1Type1 = $faculty->courses->where('level', '1')->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countLevel1Type1 > 0 ? $countLevel1Type1 : '-' }}    
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '5')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countL2 = $faculty->courses->where('level', '2')->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countL2 > 0 ? $countL2 : '-' }}
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '5')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countL3 = $faculty->courses->where('level', '3')->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countL3 > 0 ? $countL3 : '-' }}
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '5')->count() ?: '-' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="px-4 border text-[24px] text-center">รวม (จำนวนหลักสูตร)</td>
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalType1 = $faculties->flatMap->courses->whereIn('id', $type1CourseIds)->count();
                                @endphp
                                {{ $totalType1 > 0 ? $totalType1 : '0' }}
                            </td>
                            {{-- รวมผลประเมินแยกตาม Result (ทุก Level รวมกัน) --}}
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '2', $type1CourseIds)->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '3', $type1CourseIds)->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '4', $type1CourseIds)->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '5', $type1CourseIds)->count() ?: '0' }}</td>

                            {{-- รวม Level 1 --}}
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalL1 = $faculties->flatMap->courses
                                        ->where('level', '1')
                                        ->whereIn('id', $type1CourseIds)
                                        ->count();
                                @endphp
                                {{ $totalL1 > 0 ? $totalL1 : '0' }}
                            </td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '5')->count() ?: '0' }}</td>

                            {{-- รวม Level 2 --}}
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalL2 = $faculties->flatMap->courses
                                        ->where('level', '2')
                                        ->whereIn('id', $type1CourseIds)
                                        ->count();
                                @endphp
                                {{ $totalL2 > 0 ? $totalL2 : '0' }}
                            </td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '5')->count() ?: '0' }}</td>

                            {{-- รวม Level 3 --}}
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalL3 = $faculties->flatMap->courses
                                        ->where('level', '3')
                                        ->whereIn('id', $type1CourseIds)
                                        ->count();
                                @endphp
                                {{ $totalL3 > 0 ? $totalL3 : '0' }}
                            </td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '5')->count() ?: '0' }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {{-- รายงานที่ 5 --}}
    <div class="flex">
        <p class="text-[36px] text-left items-center ml-[110px] mt-[42px] inline-flex">รายงานสรุปผลการตรวจประเมินภายใน
            ระดับหลักสูตร
            {{-- export เป็น excel --}}
            <a href="{{ route('report5.export.excel', ['year_report5' => request('year_report5')]) }}">
                <svg class="ml-3" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19.4883 11.75H15.9895V10.0006H19.4883V11.75ZM19.4883 12.7496H15.9895V14.499H19.4883V12.7496ZM19.4883 4.50244H15.9895V6.25183H19.4883V4.50244ZM19.4883 7.2515H15.9895V9.00088H19.4883V7.2515ZM19.4883 15.4987H15.9895V17.2481H19.4883V15.4987ZM21.905 18.9475C21.805 19.4673 21.1802 19.4798 20.7629 19.4973H12.9906V21.7465H11.4386L0 19.7472V2.25573L11.5061 0.253906H12.9906V2.24572H20.4955C20.9179 2.26321 21.3827 2.23322 21.7501 2.48562C22.0075 2.85549 21.9825 3.32536 22 3.75019L21.99 16.7607C21.9775 17.488 22.0574 18.2303 21.905 18.9475ZM9.16433 15.0263C8.4746 13.6269 7.77236 12.2373 7.08507 10.8378C7.76484 9.47573 8.43464 8.10872 9.1019 6.74167C8.53458 6.76917 7.96727 6.80415 7.40248 6.84415C6.9801 7.87128 6.48777 8.87095 6.15789 9.93309C5.8505 8.93093 5.44315 7.96624 5.07079 6.98909C4.52096 7.01908 3.97113 7.05156 3.42134 7.08405C4.00112 8.36366 4.61845 9.62565 5.18074 10.9127C4.51847 12.1623 3.89868 13.4294 3.25639 14.6865C3.80368 14.7089 4.35102 14.7315 4.89831 14.7389C5.28821 13.7443 5.77302 12.7871 6.11291 11.7725C6.41781 12.8621 6.93511 13.8692 7.35999 14.9114C7.96228 14.9539 8.56204 14.9914 9.16433 15.0263ZM20.808 3.43265H12.9906V4.50244H14.9899V6.25183H12.9906V7.2515H14.9899V9.00088H12.9906V10.0006H14.9899V11.75H12.9906V12.7496H14.9899V14.499H12.9906V15.4987H14.9899V17.2481H12.9906V18.4038H20.808V3.43265Z"
                        fill="black" />
                </svg>
            </a>
            {{-- export เป็น pdf --}}
            <a href="{{ route('report5.export.pdf', ['year_report5' => request('year_report5')]) }}">
                <svg class="ml-3" width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.5 0H10.7779C11.1425 7.78764e-05 11.4922 0.144995 11.75 0.402875L16.8471 5.5C17.105 5.7578 17.2499 6.10748 17.25 6.47212V19.25C17.25 19.9793 16.9603 20.6788 16.4445 21.1945C15.9288 21.7103 15.2293 22 14.5 22H3.5C2.77065 22 2.07118 21.7103 1.55546 21.1945C1.03973 20.6788 0.75 19.9793 0.75 19.25V2.75C0.75 2.02065 1.03973 1.32118 1.55546 0.805456C2.07118 0.289731 2.77065 0 3.5 0ZM11.0625 2.0625V4.8125C11.0625 5.17717 11.2074 5.52691 11.4652 5.78477C11.7231 6.04263 12.0728 6.1875 12.4375 6.1875H15.1875L11.0625 2.0625ZM3.72687 18.7935C3.85063 19.041 4.04313 19.2651 4.32913 19.3696C4.61375 19.4727 4.89563 19.4246 5.12663 19.3284C5.56388 19.1496 5.99975 18.7289 6.39988 18.2476C6.85775 17.6962 7.339 16.973 7.80375 16.1714C8.70106 15.9058 9.61984 15.719 10.5496 15.6131C10.9621 16.1397 11.3884 16.5935 11.8009 16.9194C12.1859 17.2219 12.63 17.4735 13.0851 17.4928C13.333 17.5046 13.5783 17.4383 13.7864 17.303C13.9995 17.1641 14.1576 16.9634 14.2731 16.731C14.3969 16.4821 14.4725 16.2222 14.4629 15.9569C14.4546 15.6951 14.3576 15.444 14.1879 15.2446C13.8771 14.8734 13.3684 14.6946 12.8679 14.6052C12.2607 14.5117 11.6447 14.4886 11.0322 14.5365C10.5153 13.8052 10.0644 13.0294 9.68475 12.2182C10.0285 11.3107 10.2856 10.4528 10.3997 9.7515C10.4492 9.45175 10.4754 9.16575 10.4657 8.90725C10.4639 8.65067 10.4043 8.3978 10.2911 8.1675C10.2259 8.04042 10.1332 7.92945 10.0198 7.84264C9.90631 7.75584 9.77496 7.69537 9.63525 7.66562C9.3575 7.6065 9.0715 7.66563 8.80888 7.7715C8.2905 7.97775 8.01688 8.41775 7.91375 8.90312C7.81337 9.37062 7.85875 9.91512 7.977 10.4651C8.098 11.0234 8.30425 11.6311 8.56825 12.2458C8.14498 13.2955 7.65731 14.3182 7.108 15.3079C6.39952 15.5312 5.71651 15.8285 5.07025 16.1947C4.5615 16.4972 4.10912 16.8547 3.83687 17.2769C3.54812 17.7251 3.45875 18.2586 3.72687 18.7935Z"
                        fill="black" />
                </svg>
            </a>
        </p>
        <form method="GET">
            <div
                class="w-[236px] h-[46px] bg-[#FFCE00] rounded-[24px] mt-[40px] ml-[290px] text-[20px] items-center flex justify-center">
                ปีการศึกษา
                <select name="year_report5" id="thai-year-5" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
            </div>
        </form>
    </div>
    @php
        $type1CourseIds = \App\Models\CourseAssessor::where('assessment_type', '3')->pluck('course_id')->toArray();
        $totalType1All = $faculties->flatMap->courses->whereIn('id', $type1CourseIds)->count();
    @endphp
    <p class="text-[24px] w-full text-left ml-[110px] mt-2">รายงานที่ 5 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน
        ระดับหลักสูตร ตามเกณฑ์ AUN-QA Version 4.0 (Overall Verdict) <br> ประจำปีการศึกษา {{ $yearReport3 }}
        (ตรวจประเมินแบบเต็ม (2วัน) ประธานกรรมการเป็นผู้ทรงคุณวุฒิภายนอกขึ้นทะเบียนรายชื่อ<br>ที่ประชุมอธิการบดีแห่งประเทศไทย(ทปอ.)
        จำนวน {{ $totalType1All > 0 ? $totalType1All : '0' }} หลักสูตร)
    </p>
    <div class="flex pl-[110px] w-full pt-[10px]">
        <div class="w-[1364px] overflow-auto overflow-y-hidden py-3">
            <table class="w-[1364px]">
                <thead class="bg-[#FFCE00]">
                    <tr>
                        <th rowspan="3" class="px-4 border text-[24px]">ที่</th>
                        <th rowspan="3" class="px-4 border text-[24px] whitespace-nowrap">ส่วนงานคณะ/วิทยาลัย</th>
                        <th colspan="5" class="px-4 border text-[20px]">ภาพรวมทั้งคณะ/วิทยาลัย</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาตรี</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาโท</th>
                        <th colspan="5" class="px-4 border text-[20px]">ระดับปริญญาเอก</th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                        <th rowspan="2" class="px-4 border text-[24px]">จำนวน<br>หลักสูตร</th>
                        <th colspan="4" class="px-4 border text-[24px]">Overall Verdict</th>
                    </tr>
                    <tr>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                        <th class="px-4 border text-[24px]">2</th>
                        <th class="px-4 border text-[24px]">3</th>
                        <th class="px-4 border text-[24px]">4</th>
                        <th class="px-4 border text-[24px]">5</th>
                    </tr>
                </thead>
                <tbody>
                    @if($assessment4->count() == 0)
                        <tr>
                            <td colspan="22" class="border text-center text-[24px]">ไม่มีข้อมูล</td>
                        </tr>
                    @else
                        @foreach ($faculties as $index => $faculty)
                                @php
                                    // 1. ดึง ID ของหลักสูตรที่เป็น Type 3 มาก่อน
                                    $type1CourseIds = \App\Models\CourseAssessor::where('assessment_type', '3')
                                                        ->pluck('course_id')
                                                        ->toArray();

                                    // 2. กรองตัวแปร $assessment ให้เหลือเฉพาะ Type 3 ทันที (สำคัญมาก!)
                                    $assessment = $assessment->whereIn('course_id', $type1CourseIds);

                                    // 3. หลังจากนั้นค่อยคำนวณตัวแปรอื่นๆ ข้อมูลที่ได้จะเป็น Type 3 ทั้งหมดแล้ว
                                    $totalType1All = $faculties->flatMap->courses->whereIn('id', $type1CourseIds)->count();

                                    $allLevel1CourseNames = $faculties->flatMap->courses->where('level', '1')->pluck('name')->toArray();
                                    $allLevel2CourseNames = $faculties->flatMap->courses->where('level', '2')->pluck('name')->toArray();
                                    $allLevel3CourseNames = $faculties->flatMap->courses->where('level', '3')->pluck('name')->toArray();

                                    // ตอนนี้ตัวแปรพวกนี้จะไม่มี Type 1 ปนแล้ว เพราะ $assessment ถูกกรองไปแล้วในข้อ 2
                                    $totalLevel1Assessments = $assessment->whereIn('courses', $allLevel1CourseNames);
                                    $totalLevel2Assessments = $assessment->whereIn('courses', $allLevel2CourseNames);
                                    $totalLevel3Assessments = $assessment->whereIn('courses', $allLevel3CourseNames);
                                @endphp                           
                            <tr>
                                <td class="px-4 border text-[24px] text-center">{{ $index + 1  }}</td>
                                <td class="px-4 border text-[24px] whitespace-nowrap">{{ $faculty->name }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countType1 = $faculty->courses->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countType1 > 0 ? $countType1 : '-' }}
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','2', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','3', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','4', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ ($assessment->where('faculty', $faculty->name)->where('result','5', $type1CourseIds)->count()) ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countLevel1Type1 = $faculty->courses->where('level', '1')->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countLevel1Type1 > 0 ? $countLevel1Type1 : '-' }}    
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level1Assessments->where('result', '5')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countL2 = $faculty->courses->where('level', '2')->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countL2 > 0 ? $countL2 : '-' }}
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level2Assessments->where('result', '5')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">
                                    @php
                                        $countL3 = $faculty->courses->where('level', '3')->whereIn('id', $type1CourseIds)->count();
                                    @endphp
                                    {{ $countL3 > 0 ? $countL3 : '-' }}
                                </td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '2')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '3')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '4')->count() ?: '-' }}</td>
                                <td class="px-4 border text-[24px] text-center">{{ $level3Assessments->where('result', '5')->count() ?: '-' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="px-4 border text-[24px] text-center">รวม (จำนวนหลักสูตร)</td>
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalType1 = $faculties->flatMap->courses->whereIn('id', $type1CourseIds)->count();
                                @endphp
                                {{ $totalType1 > 0 ? $totalType1 : '0' }}
                            </td>
                            {{-- รวมผลประเมินแยกตาม Result (ทุก Level รวมกัน) --}}
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '2', $type1CourseIds)->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '3', $type1CourseIds)->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '4', $type1CourseIds)->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $assessment->where('result', '5', $type1CourseIds)->count() ?: '0' }}</td>

                            {{-- รวม Level 1 --}}
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalL1 = $faculties->flatMap->courses
                                        ->where('level', '1')
                                        ->whereIn('id', $type1CourseIds)
                                        ->count();
                                @endphp
                                {{ $totalL1 > 0 ? $totalL1 : '0' }}
                            </td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel1Assessments->where('result', '5')->count() ?: '0' }}</td>

                            {{-- รวม Level 2 --}}
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalL2 = $faculties->flatMap->courses
                                        ->where('level', '2')
                                        ->whereIn('id', $type1CourseIds)
                                        ->count();
                                @endphp
                                {{ $totalL2 > 0 ? $totalL2 : '0' }}
                            </td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel2Assessments->where('result', '5')->count() ?: '0' }}</td>

                            {{-- รวม Level 3 --}}
                            <td class="px-4 border text-[24px] text-center">
                                @php
                                    $totalL3 = $faculties->flatMap->courses
                                        ->where('level', '3')
                                        ->whereIn('id', $type1CourseIds)
                                        ->count();
                                @endphp
                                {{ $totalL3 > 0 ? $totalL3 : '0' }}
                            </td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '2')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '3')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '4')->count() ?: '0' }}</td>
                            <td class="px-4 border text-[24px] text-center">{{ $totalLevel3Assessments->where('result', '5')->count() ?: '0' }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {{-- รายงานที่ 6 --}}
    <div class="flex">
        <p class="text-[36px] text-left items-center ml-[110px] mt-[42px] inline-flex">รายงานสรุปผลการตรวจประเมินภายใน
            ระดับหลักสูตร
            {{-- export เป็น excel --}}
            <a href="{{ route('report6.export.excel', ['year_report6' => $yearReport6])}}">
                <svg class="ml-3" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19.4883 11.75H15.9895V10.0006H19.4883V11.75ZM19.4883 12.7496H15.9895V14.499H19.4883V12.7496ZM19.4883 4.50244H15.9895V6.25183H19.4883V4.50244ZM19.4883 7.2515H15.9895V9.00088H19.4883V7.2515ZM19.4883 15.4987H15.9895V17.2481H19.4883V15.4987ZM21.905 18.9475C21.805 19.4673 21.1802 19.4798 20.7629 19.4973H12.9906V21.7465H11.4386L0 19.7472V2.25573L11.5061 0.253906H12.9906V2.24572H20.4955C20.9179 2.26321 21.3827 2.23322 21.7501 2.48562C22.0075 2.85549 21.9825 3.32536 22 3.75019L21.99 16.7607C21.9775 17.488 22.0574 18.2303 21.905 18.9475ZM9.16433 15.0263C8.4746 13.6269 7.77236 12.2373 7.08507 10.8378C7.76484 9.47573 8.43464 8.10872 9.1019 6.74167C8.53458 6.76917 7.96727 6.80415 7.40248 6.84415C6.9801 7.87128 6.48777 8.87095 6.15789 9.93309C5.8505 8.93093 5.44315 7.96624 5.07079 6.98909C4.52096 7.01908 3.97113 7.05156 3.42134 7.08405C4.00112 8.36366 4.61845 9.62565 5.18074 10.9127C4.51847 12.1623 3.89868 13.4294 3.25639 14.6865C3.80368 14.7089 4.35102 14.7315 4.89831 14.7389C5.28821 13.7443 5.77302 12.7871 6.11291 11.7725C6.41781 12.8621 6.93511 13.8692 7.35999 14.9114C7.96228 14.9539 8.56204 14.9914 9.16433 15.0263ZM20.808 3.43265H12.9906V4.50244H14.9899V6.25183H12.9906V7.2515H14.9899V9.00088H12.9906V10.0006H14.9899V11.75H12.9906V12.7496H14.9899V14.499H12.9906V15.4987H14.9899V17.2481H12.9906V18.4038H20.808V3.43265Z"
                        fill="black" />
                </svg>
            </a>
            {{-- export เป็น pdf --}}
            <a href="{{ route('report6.export.pdf', ['year_report6' => request('year_report6')]) }}">
                <svg class="ml-3" width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.5 0H10.7779C11.1425 7.78764e-05 11.4922 0.144995 11.75 0.402875L16.8471 5.5C17.105 5.7578 17.2499 6.10748 17.25 6.47212V19.25C17.25 19.9793 16.9603 20.6788 16.4445 21.1945C15.9288 21.7103 15.2293 22 14.5 22H3.5C2.77065 22 2.07118 21.7103 1.55546 21.1945C1.03973 20.6788 0.75 19.9793 0.75 19.25V2.75C0.75 2.02065 1.03973 1.32118 1.55546 0.805456C2.07118 0.289731 2.77065 0 3.5 0ZM11.0625 2.0625V4.8125C11.0625 5.17717 11.2074 5.52691 11.4652 5.78477C11.7231 6.04263 12.0728 6.1875 12.4375 6.1875H15.1875L11.0625 2.0625ZM3.72687 18.7935C3.85063 19.041 4.04313 19.2651 4.32913 19.3696C4.61375 19.4727 4.89563 19.4246 5.12663 19.3284C5.56388 19.1496 5.99975 18.7289 6.39988 18.2476C6.85775 17.6962 7.339 16.973 7.80375 16.1714C8.70106 15.9058 9.61984 15.719 10.5496 15.6131C10.9621 16.1397 11.3884 16.5935 11.8009 16.9194C12.1859 17.2219 12.63 17.4735 13.0851 17.4928C13.333 17.5046 13.5783 17.4383 13.7864 17.303C13.9995 17.1641 14.1576 16.9634 14.2731 16.731C14.3969 16.4821 14.4725 16.2222 14.4629 15.9569C14.4546 15.6951 14.3576 15.444 14.1879 15.2446C13.8771 14.8734 13.3684 14.6946 12.8679 14.6052C12.2607 14.5117 11.6447 14.4886 11.0322 14.5365C10.5153 13.8052 10.0644 13.0294 9.68475 12.2182C10.0285 11.3107 10.2856 10.4528 10.3997 9.7515C10.4492 9.45175 10.4754 9.16575 10.4657 8.90725C10.4639 8.65067 10.4043 8.3978 10.2911 8.1675C10.2259 8.04042 10.1332 7.92945 10.0198 7.84264C9.90631 7.75584 9.77496 7.69537 9.63525 7.66562C9.3575 7.6065 9.0715 7.66563 8.80888 7.7715C8.2905 7.97775 8.01688 8.41775 7.91375 8.90312C7.81337 9.37062 7.85875 9.91512 7.977 10.4651C8.098 11.0234 8.30425 11.6311 8.56825 12.2458C8.14498 13.2955 7.65731 14.3182 7.108 15.3079C6.39952 15.5312 5.71651 15.8285 5.07025 16.1947C4.5615 16.4972 4.10912 16.8547 3.83687 17.2769C3.54812 17.7251 3.45875 18.2586 3.72687 18.7935Z"
                        fill="black" />
                </svg>
            </a>
        </p>
        <form method="GET">
            <div
                class="w-[236px] h-[46px] bg-[#FFCE00] rounded-[24px] mt-[40px] ml-[290px] text-[20px] items-center flex justify-center">
                ปีการศึกษา
                <select name="year_report6" id="thai-year-6" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
            </div>
        </form>
    </div>
    <p class="text-[24px] w-full text-left ml-[110px] mt-2">รายงานที่ 6 ผลการตรวจประเมินการประกันคุณภาพการศึกษาภายใน
        ระดับหลักสูตร ตามเกณฑ์ AUN-QA ปีการศึกษา {{ $yearReport3 }} (ร้อยละ) <br> จำแนกตามลำดับ
    </p>
    <div class="flex pl-[110px] w-full pt-[10px]">
        <table class="w-[1364px]">
            <thead class="bg-[#FFCE00]">
                <tr>
                    <th rowspan="2" class="px-4 border text-[24px]">Rating Scale</th>
                    <th colspan="4" class="px-4 border text-[24px]">Programme</th>
                    <th colspan="3" class="px-4 border text-[24px]">Resouce</th>
                    <th colspan="1" class="px-4 border text-[24px]">Results</th>
                </tr>
                <tr>
                    <th class="px-4 border text-[24px]">AUN-QA<br>1</th>
                    <th class="px-4 border text-[24px]">AUN-QA<br>2</th>
                    <th class="px-4 border text-[24px]">AUN-QA<br>3</th>
                    <th class="px-4 border text-[24px]">AUN-QA<br>4</th>
                    <th class="px-4 border text-[24px]">AUN-QA<br>5</th>
                    <th class="px-4 border text-[24px]">AUN-QA<br>6</th>
                    <th class="px-4 border text-[24px]">AUN-QA<br>7</th>
                    <th class="px-4 border text-[24px]">AUN-QA<br>8</th>
                </tr>
            </thead>
            <tbody>
                @if($assessment5->count() == 0)
                    <tr>
                        <td colspan="22" class="border text-center text-[24px]">ไม่มีข้อมูล</td>
                    </tr>
                @else
                    <tr>
                        <td class="px-4 border text-[24px] text-center">ระดับ 5</td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                    </tr>
                    <tr>
                        <td class="px-4 border text-[24px] text-center">ระดับ 4</td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                    </tr>
                    <tr>
                        <td class="px-4 border text-[24px] text-center">ระดับ 3</td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                    </tr>
                    <tr>
                        <td class="px-4 border text-[24px] text-center">ระดับ 2</td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                    </tr>
                        <tr>
                        <td class="px-4 border text-[24px] text-center">ระดับ 1</td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                    </tr>
                    <tr>
                        <td class="px-4 border text-[24px]">&nbsp;</td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                        <td class="px-4 border text-[24px]"></td>
                    </tr>
                @endif                
            </tbody>
        </table>
    </div>
    <div class="h-[50px]"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const selectElement = document.getElementById('thai-year-1');

            const currentYear = new Date().getFullYear();
            const currentThaiYear = currentYear + 543;

            const startYear = currentThaiYear - 2;
            const endYear = currentThaiYear;

            const selectedYear = {{ $yearReport1 }};

            for (let i = endYear; i >= startYear; i--) {

                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;

                if (i === selectedYear) {
                    option.selected = true;
                }

                selectElement.appendChild(option);
            }
            // รายงาน2
            const selectElement2 = document.getElementById('thai-year-2');

            const selectedYear2 = {{ $yearReport2 }};

            for (let i = endYear; i >= startYear; i--) {

                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;

                if (i === selectedYear2) {
                    option.selected = true;
                }

                selectElement2.appendChild(option);
            }
            // รายงาน 3
            const selectElement3 = document.getElementById('thai-year-3');

            const selectedYear3 = {{ $yearReport3 }};

            for (let i = endYear; i >= startYear; i--) {

                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;

                if (i === selectedYear3) {
                    option.selected = true;
                }

                selectElement3.appendChild(option);
            }
            // รายงาน 4
            const selectElement4 = document.getElementById('thai-year-4');

            const selectedYear4 = {{ $yearReport4 }};

            for (let i = endYear; i >= startYear; i--) {

                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;

                if (i === selectedYear4) {
                    option.selected = true;
                }

                selectElement4.appendChild(option);
            }
            // รายงาน 5
            const selectElement5 = document.getElementById('thai-year-5');

            const selectedYear5 = {{ $yearReport5 }};

            for (let i = endYear; i >= startYear; i--) {

                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;

                if (i === selectedYear5) {
                    option.selected = true;
                }

                selectElement5.appendChild(option);
            }
            // รายงาน 6
            const selectElement6 = document.getElementById('thai-year-6');

            const selectedYear6 = {{ $yearReport6 }};

            for (let i = endYear; i >= startYear; i--) {

                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;

                if (i === selectedYear6) {
                    option.selected = true;
                }

                selectElement6.appendChild(option);
            }
        });
    </script>
@endsection