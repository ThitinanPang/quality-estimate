@extends('layouts.header')

@section('content')
    <div class="flex flex-col items-center justify-center">
        <div class="w-[1032px] h-[265px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[74px] pl-[40px] pt-[10px]">
            <p class="text-[24px]">ชื่อ - นามสกุล</p>
            <input type="text" class="bg-[#BEBEBE] w-[937px] h-[30px] rounded border mt-2">
            <p class="text-[24px]">คณะ</p>
            <input type="text" class="bg-[#BEBEBE] w-[937px] h-[30px] rounded border mt-2">
            <p class="text-[24px]">หลักสูตร</p>
            <input type="text" class="bg-[#BEBEBE] w-[937px] h-[30px] rounded border mt-2">
        </div>
        <div class="w-[1032px] h-[213px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[32px] pl-[40px] pt-[10px]">
            <p class="text-[24px]">ส่วนที่ 1 การกำกับมาตรฐาน</p>
            <select name="" id="" class="w-[918px] h-[42px] border rounded-[5px] bg-white mt-[9px]"></select>
            <p class="text-[24px] mt-[9px]">ส่วนที่ 2 ผลการตรวจประเมินตามเกณฑ์ AUN-QA</p>
            <select name="" id="" class="w-[918px] h-[42px] border rounded-[5px] bg-white mt-[9px]"></select>
        </div>
        <div class="w-[1040px] h-[220px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[32px] pl-[40px] pt-[10px]">
            <p class="text-[24px]">Strength</p>
            <select name="" id="" class="w-[918px] h-[42px] border rounded-[5px] bg-white mt-[9px]"></select>
            <p class="text-[24px] mt-[9px]">Area for Improvement</p>
            <select name="" id="" class="w-[918px] h-[42px] border rounded-[5px] bg-white mt-[9px]"></select>
        </div>
        <p class="text-[24px] mr-[600px] mt-[32px]">AUN-QA 1_Expected Learning Outcomes</p>
        <div class="w-[1040px] h-[126px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[32px] pl-[40px] pt-[10px]">
            <p class="text-[24px]">AUN-QA 1_Overall Opinion</p>
            <select name="" id="" class="w-[78px] h-[42px] border rounded-[5px] bg-white mt-[9px]"></select>
        </div>
        <div
            class="w-[1040px] h-[679px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[32px] pl-[40px] pr-[40px] pt-[10px]">
            @for ($i = 1; $i <= 5; $i++)
                <p class="text-[24px] mt-[30px]">AUN-QA 1.{{$i}}</p>
                <div class="flex justify-between">
                    @for ($j = 1; $j <= 7; $j++)
                        <label class="flex flex-col items-center">
                            <p class="text-[20px]">{{$j}}</p>
                            <input type="radio" name="onlyone_{{$i}}" value="{{$j}}" class="w-[23px] h-[22px] mt-1">
                        </label>
                    @endfor
                </div>
            @endfor
        </div>
        <button type="button" onclick="" class="w-[155px] h-[37px] mt-[32px] ml-[900px] bg-[#FFCE00] border rounded-[9px]">บันทึก</button>
    </div>
@endsection