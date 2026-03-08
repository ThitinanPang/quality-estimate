@extends('layouts.header')

@section('content')
    <div class="flex flex-col items-center justify-center ml-[110px] mt-[42px]">
        <p class="text-[36px] w-full text-left inline-flex items-center">รายงานสรุปผลการตรวจประเมินภายใน ระดับหลักสูตร
            {{-- export เป็น excel --}}
            <a href="{{ route('report.export',['thai_year'=>request('thai_year')]) }}">
                <svg class="ml-3" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19.4883 11.75H15.9895V10.0006H19.4883V11.75ZM19.4883 12.7496H15.9895V14.499H19.4883V12.7496ZM19.4883 4.50244H15.9895V6.25183H19.4883V4.50244ZM19.4883 7.2515H15.9895V9.00088H19.4883V7.2515ZM19.4883 15.4987H15.9895V17.2481H19.4883V15.4987ZM21.905 18.9475C21.805 19.4673 21.1802 19.4798 20.7629 19.4973H12.9906V21.7465H11.4386L0 19.7472V2.25573L11.5061 0.253906H12.9906V2.24572H20.4955C20.9179 2.26321 21.3827 2.23322 21.7501 2.48562C22.0075 2.85549 21.9825 3.32536 22 3.75019L21.99 16.7607C21.9775 17.488 22.0574 18.2303 21.905 18.9475ZM9.16433 15.0263C8.4746 13.6269 7.77236 12.2373 7.08507 10.8378C7.76484 9.47573 8.43464 8.10872 9.1019 6.74167C8.53458 6.76917 7.96727 6.80415 7.40248 6.84415C6.9801 7.87128 6.48777 8.87095 6.15789 9.93309C5.8505 8.93093 5.44315 7.96624 5.07079 6.98909C4.52096 7.01908 3.97113 7.05156 3.42134 7.08405C4.00112 8.36366 4.61845 9.62565 5.18074 10.9127C4.51847 12.1623 3.89868 13.4294 3.25639 14.6865C3.80368 14.7089 4.35102 14.7315 4.89831 14.7389C5.28821 13.7443 5.77302 12.7871 6.11291 11.7725C6.41781 12.8621 6.93511 13.8692 7.35999 14.9114C7.96228 14.9539 8.56204 14.9914 9.16433 15.0263ZM20.808 3.43265H12.9906V4.50244H14.9899V6.25183H12.9906V7.2515H14.9899V9.00088H12.9906V10.0006H14.9899V11.75H12.9906V12.7496H14.9899V14.499H12.9906V15.4987H14.9899V17.2481H12.9906V18.4038H20.808V3.43265Z"
                        fill="black" />
                </svg>
            </a>
            {{-- export เป็น pdf --}}
            <a href="{{ route('report.export.pdf',['thai_year'=>request('thai_year')]) }}" target="_blank" rel="noopener">
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
                <select name="thai_year" id="thai-year" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
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
            </tbody>
        </table>
    </div>
    <div class="h-[50px]"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const selectElement = document.getElementById('thai-year');

            const currentYear = new Date().getFullYear();
            const currentThaiYear = currentYear + 543;

            const startYear = currentThaiYear - 2;
            const endYear = currentThaiYear;

            const selectedYear = {{ $selectedThaiYear }};

            for (let i = endYear; i >= startYear; i--) {

                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;

                if (i === selectedYear) {
                    option.selected = true;
                }

                selectElement.appendChild(option);
            }

        });
    </script>
@endsection