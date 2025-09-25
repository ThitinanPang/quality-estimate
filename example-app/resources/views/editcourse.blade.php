@extends('layouts.header')

@section('content')
    <div class="flex flex-col items-center justify-center mt-[50px]">
        <div class="w-[511px] h-[45px] rounded-t-[14px] border-b bg-[#FFCE00] items-center justify-center flex pl-3 pr-3">
            <p class="text-[24px] mr-auto">เพิ่มข้อมูลหลักสูตร</p>
        </div>
        <div class="w-[511px] h-[550px] rounded-b-[14px] bg-[#DBDBDB] flex flex-col items-center justify-center">
            <p class="text-[20px] mr-[345px]">คณะ/วิทยาลัย</p>
            <input type="text" readonly value="{{ $faculty->name }}"  class="w-[461px] h-[45px] rounded-[8px] bg-white mt-3 pl-3">
            <p class="text-[20px] mr-[390px] mt-2">กลุ่มวิชา</p>
            <input type="text" readonly name="" id="" class="w-[461px] h-[45px] rounded-[8px] bg-white mt-3 pl-3">
            <p class="text-[20px] mr-[335px] mt-2">ระดับการศึกษา</p>
            <input type="text" name="" id="" class="w-[461px] h-[45px] rounded-[8px] bg-white mt-3 pl-3">
            <p class="text-[20px] mr-[390px] mt-2">หลักสูตร</p>
            <input type="text" name="" id="" class="w-[461px] h-[45px] rounded-[8px] bg-white mt-3 pl-3">
            <p class="text-[20px] mr-[400px] mt-2">ปี พ.ศ.</p>
            <input type="text" name="" id="" class="w-[461px] h-[45px] rounded-[8px] bg-white mt-3 pl-3">
            <button class="bg-[#FFCE00] w-[149px] h-[37px] rounded-[9px] text-[20px] border ml-[315px] mt-3">บันทึก</button>
        </div>
    </div>
@endsection