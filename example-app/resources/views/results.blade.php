@extends('layouts.header')

@section('content')
    <div class="flex flex-col items-center justify-center">
        <p class="text-[36px] mt-[24px]">ข้อมูลการประเมิน</p>
        <div
            class="w-[796px] h-[511px] left-[322px] mt-[24px] bg-[#DBDBDB] shadow-[0_4px_4px_rgba(0,0,0,0.25)] rounded-[15px] p-[46px] space-y-2">
            <div class="flex gap-4">
                <p class="text-[24px]">ชื่อ - นามสกุล :</p>
                <p class="text-[24px]">{{session('user_name')}}</p>
            </div>
            <div class="flex gap-4">
                <p class="text-[24px]">คณะ :</p>
                <select name="" id="" class="bg-white border rounded w-[223px] h-[30px]"></select>
            </div>
            <p class="text-[24px]">หลักสูตร :</p>
            <div class="flex gap-4">
                <p class="text-[24px]">ตำแหน่ง :</p>
                <p class="text-[24px] ml-[100px]">ประเภทการตรวจ :</p>
            </div>
            <div class="flex gap-4">
                <p class="text-[24px]">กรรมการ :</p>
                <p class="text-[24px] ml-[10px]">ผู้ฝึกประสบการณ์ :</p>
            </div>
            <div class="flex gap-4">
                <p class="text-[24px]">เบอร์โทรศัพท์ :</p>
                <p class="text-[24px] ml-[10px]">เบอร์โทรศัพท์ :</p>
            </div>
            <div class="flex gap-4">
                <p class="text-[24px]">อีเมล :</p>
                <p class="text-[24px] ml-[10px]">อีเมล :</p>
            </div>
        </div>
        <button type="button" class="border bg-[#FFCE00] text-[20px] rounded-[9px] box-border w-[155px] h-[37px] mt-[51px] hover:bg-white">ปรเมิน</button>
    </div>
@endsection