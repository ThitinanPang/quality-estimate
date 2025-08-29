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
        <div class="w-[1032px] h-[213px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[32px] p-[30px]">
            <p></p>
            <select name="" id=""></select>
        </div>
    </div>
@endsection