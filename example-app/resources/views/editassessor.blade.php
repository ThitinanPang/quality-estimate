@extends('layouts.header')

@section('content')
    <div class="flex flex-col items-center justify-center mt-[32px]">
        <p class="text-[24px] w-[900px] h-[40px] bg-[#FFCE00] text-center border border-b-0 rounded-t-[14px]">
            แก้ไขข้อมูลผู้ประเมิน</p>
        <form action="{{ route('updateassessor', $userassessor->id) }}" method="POST">
            @csrf
            <div
                class="border rounded-b-[39px] bg-[#DBDBDB] w-[900px] h-[780px] pl-[40px] pr-[40px] pt-[10px] overflow-y-auto">
                <p class="mt-[9px]">ตำแหน่ง</p>
                <select name="role" class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
                    <option value="user" {{ $userassessor->role == 'user' ? 'selected' : '' }}>user</option>
                    <option value="admin" {{ $userassessor->role == 'admin' ? 'selected' : '' }}>admin</option>
                    <option value="admin university" {{ $userassessor->role == 'admin university' ? 'selected' : '' }}>admin university</option>
                </select>

                <p class="mt-[9px]">คำนำหน้า</p>
                <input type="text" name="prefix" value="{{ $userassessor->prefix }}"
                    class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px]">ชื่อ - สกุล</p>
                <input type="text" name="name" value="{{ $userassessor->name }}"
                    class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px]">กลุ่มวิชา</p>
                <input type="text" name="subject_group" value="{{ $userassessor->subject_group }}"
                    class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px]">คณะ</p>
                <input type="text" name="faculty" value="{{ $userassessor->faculty }}"
                    class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px]">หลักสูตร</p>
                <input type="text" name="course" value="{{ $userassessor->course }}"
                    class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px]">Assessor Type</p>
                <select name="role" class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
                    <option value="junior">junior</option>
                    <option value=""></option>
                    <option value=""></option>
                </select>

                <p class="mt-[9px]">Training Type</p>
                <input type="text" name="Training Type" value=""
                    class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px]">อีเมล</p>
                <input type="email" name="email" value="{{ $userassessor->email }}"
                    class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px]">เบอร์โทรศัพท์</p>
                <input type="text" name="phone_number" value="{{ $userassessor->phone_number }}"
                    class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px]">status</p>
                <select name="status" class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
                    <option value="active" {{ $userassessor->status == 'active' ? 'selected' : '' }}>active</option>
                    <option value="inactive" {{ $userassessor->status == 'inactive' ? 'selected' : '' }}>inactive</option>
                </select>
            </div>
            <div class=" justify-center flex gap-[50px]">
                <button onclick="{{ route('assessor') }}"
                    class="w-[155px] h-[37px] mt-[32px] bg-[#DBDBDB] rounded-[9px] border">ยกเลิก</button>
                <button type="submit" onclick="alert('แก้ไขสำเร็จ')"
                    class="w-[155px] h-[37px] mt-[32px] bg-[#FFCE00] border rounded-[9px] hover:bg-white">
                    บันทึก
                </button>
            </div>
        </form>
    </div>
@endsection