@extends('layouts.header')

@section('content')
    <div class="flex items-center h-[40px] mt-[28px]">
        <p class="text-[32px] ml-[85px]">โปรดใส่หัวข้อ</p>
        <svg class="ml-[20px]" width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M13.4568 2.92634L15.0032 1.44934C15.3256 1.14162 15.7628 0.96875 16.2187 0.96875C16.6747 0.96875 17.1119 1.14162 17.4342 1.44934C17.7566 1.75706 17.9377 2.17441 17.9377 2.60959C17.9377 3.04477 17.7566 3.46212 17.4342 3.76984L7.70017 13.0615C7.21555 13.5238 6.61792 13.8636 5.96125 14.0502L3.5 14.7502L4.23333 12.4008C4.42884 11.774 4.78483 11.2036 5.26917 10.741L13.4568 2.92634ZM13.4568 2.92634L15.875 5.23459M14.5 11.2502V15.4065C14.5 15.9286 14.2827 16.4294 13.8959 16.7986C13.5091 17.1678 12.9845 17.3752 12.4375 17.3752H2.8125C2.26549 17.3752 1.74089 17.1678 1.35409 16.7986C0.967298 16.4294 0.75 15.9286 0.75 15.4065V6.21897C0.75 5.69682 0.967298 5.19606 1.35409 4.82685C1.74089 4.45764 2.26549 4.25022 2.8125 4.25022H7.16667"
                stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <p class=" absolute text-[24px] left-[986px]">ปีการศึกษา</p>
        <div class="absolute w-[113px] h-[36px] left-[1095px] top-[161px] 
                 bg-[#FFCE00] rounded-[18px] ml-2">
            <select name="thai-year" id="thai-year"
                class="w-[113px] h-[36px] rounded-[18px] flex items-center justify-center text-center text-[20px]"></select>
        </div>
    </div>
    <div class="w-[1130px] h-[265px] 
             bg-[#DBDBDB] border border-black 
             shadow-[0_4px_4px_rgba(0,0,0,0.25)] 
             rounded-[12px] box-border mt-2 ml-[85px] items-center p-[30px]">
        <p class="text-[24px]">ชื่อ - นามสกุล</p>
        <input type="text" class="bg-white w-[1070px] h-[30px] rounded border">
        <p class="text-[24px]">คณะ</p>
        <input type="text" class="bg-white w-[1070px] h-[30px] rounded border">
        <p class="text-[24px]">หลักสูตร</p>
        <input type="text" class="bg-white w-[1070px] h-[30px] rounded border">
    </div>
    <div class="w-[1130px] h-[240px] 
             bg-[#DBDBDB] border border-black 
             shadow-[0_4px_4px_rgba(0,0,0,0.25)] 
             rounded-[12px] box-border mt-10 ml-[85px] items-center p-[30px]">
        <p class="text-[24px]">ส่วนที่ 1 การกำกับมาตรฐาน</p>
        <select name="" id="" class="bg-white w-[1070px] h-[30px] rounded border"></select>
        <input type="text" class="bg-white w-[1070px] h-[30px] rounded border mt-3">
        <p class="text-[24px]">ส่วนที่ 2 ผลตรวจการประเมินตามเกณฑ์ AUN-QA</p>
        <select name="" id="" class="bg-white w-[1070px] h-[30px] rounded border"></select>
    </div>
    <div class="w-[1130px] h-[150px] 
             bg-[#DBDBDB] border border-black 
             shadow-[0_4px_4px_rgba(0,0,0,0.25)] 
             rounded-[12px] box-border mt-10 ml-[85px] items-center p-[30px]">
        <p class="text-[24px]">จุดแข็ง (Strengths)</p>
        <input type="text" class="bg-white w-[1070px] h-[30px] rounded border mt-3">
    </div>
    <p class="text-[32px] ml-[85px] mt-5">AUN-QA 1_Expected Learning Outcomes</p>
    <div class="w-[1130px] h-[150px] 
             bg-[#DBDBDB] border border-black 
             shadow-[0_4px_4px_rgba(0,0,0,0.25)] 
             rounded-[12px] box-border mt-10 ml-[85px] items-center p-[30px]">
        <p class="text-[24px]">AUN-QA 1_Overall Opinion</p>
        <select name="" id="" class="w-[73px] h-[42px] bg-white rounded border mt-3"></select>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectElement = document.getElementById('thai-year');
            const currentYear = new Date().getFullYear();
            const currentThaiYear = currentYear + 543;

            // กำหนดช่วงปีที่ต้องการให้แสดง
            const startYear = currentThaiYear - 2;
            const endYear = currentThaiYear;

            for (let i = endYear; i >= startYear; i--) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `${i}`;
                selectElement.appendChild(option);
            }

            // ตั้งค่าให้ปีปัจจุบันเป็นตัวเลือกเริ่มต้น
            selectElement.value = currentThaiYear;
        });
    </script>
@endsection