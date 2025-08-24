@extends('layouts.header')

@section('content')
    <div class="flex flex-col items-center justify-center">
        <p class="text-[36px] mt-[24px]">ข้อมูลการประเมิน</p>
        <div class="w-[796px] h-[511px] left-[322px] mt-[24px] bg-[#DBDBDB] shadow-[0_4px_4px_rgba(0,0,0,0.25)] rounded-[15px] p-[32px]">
            <p class="text-[24px]">ชื่อ - นามสกุล :</p>
            <p class="text-[24px]">{{session('user_name')}}</p>
            <p class="text-[24px]">คณะ :</p>
            <select name="" id="" class="bg-white border rounded w-[223px] h-[30px]"></select>
            <p class="text-[24px]">หลักสูตร :</p>
            <p class="text-[24px]">ตำแหน่ง :</p>
            <p class="text-[24px]">ประเภทการตรวจ :</p>
            <p></p>
        </div>
    </div>
@endsection