@extends('layouts.header')

@section('content')
    <div class="flex flex-col items-center justify-center mt-[32px]">
        <p class="text-[24px]">เพิ่มข้อมูลผู้ใช้</p>
        <div class="border rounded-[39px] bg-[#DBDBDB] w-[900px] h-[700px] mt-[32px] pl-[40px] pr-[40px] pt-[10px]">
            <p class="mt-[9px]">ตำแหน่ง</p>
            <select name="" id="" class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
                <option value="">user</option>
                <option value="">admin</option>
                <option value="">admin university</option>
            </select>
            <p class="mt-[9px]">คำนำหน้า</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">ขื่อ - สกุล</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">กลุ่มวิชา</p> 
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">คณะ</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">หลักสูตร</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">Type Assessor</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">Trainning Type</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">อีเมล</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">เบอร์โทรศัพท์</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
        </div>
        <button class="w-[155px] h-[37px] mt-[32px] bg-[#FFCE00] border rounded-[9px] hover:bg-white">บันทึก</button>
    </div>
@endsection