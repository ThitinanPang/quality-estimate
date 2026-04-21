@extends('layouts.header')

@section('content')
    <div class="flex flex-col items-center justify-center mt-[32px]">
        <p class="text-[28px] w-[900px] h-[40px] bg-[#FFCE00] text-center border border-b-0 rounded-t-[14px]">
            แก้ไขข้อมูลผู้ประเมิน</p>
        <form action="{{ route('updateassessor', $userassessor->id) }}" method="POST">
            @csrf
            <div
                class="border rounded-b-[39px] bg-[#DBDBDB] w-[900px] h-[900px] pl-[40px] pr-[40px] pt-[10px] overflow-y-auto">
                <p class="mt-[9px] text-[20px]">ตำแหน่ง</p>
                <p class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 capitalize text-[20px]">{{ $userassessor->role }}</p>
                <p class="mt-[9px] text-[20px]">Code Assessor</p>
                <input name="code_assessor" class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]"
                    value="{{ $userassessor->code_assessor }}"></input>
                <p class="mt-[9px] text-[20px]">คำนำหน้า</p>
                <input type="text" name="prefix" value="{{ $userassessor->prefix }}"
                    class="bg-white text-[20px] h-[25px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px] text-[20px]">ชื่อ - สกุล</p>
                <input type="text" name="name" value="{{ $userassessor->name }}"
                    class="bg-white text-[20px] h-[25px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px] text-[20px]">กลุ่มวิชา</p>
                <input type="text" name="subject_group" value="{{ $userassessor->subject_group }}"
                    class="bg-white h-[25px] text-[20px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px] text-[20px]">คณะ</p>
                <input type="text" name="faculty" value="{{ $userassessor->faculty }}"
                    class="bg-white h-[25px] text-[20px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px] text-[20px]">หลักสูตร</p>
                <input type="text" name="course" value="{{ $userassessor->course }}"
                    class="bg-white h-[25px] text-[20px] w-[800px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px] text-[20px]">Assessor Type</p>
                <select name="assessor_type" class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">
                    <option value="junior">Junior</option>
                    <option value="senior">Senior</option>
                    <option value="lead">Lead</option>
                    <option value="novice">Novice</option>
                </select>

                <p class="mt-[9px] text-[20px]">Training Type</p>
                <input type="text" name="training_type" value="{{ $userassessor->training_type }}"
                    class="bg-white h-[25px] w-[800px] text-[20px] border rounded mt-[9px] pl-3">

                <p class="mt-[9px] text-[20px]">อีเมล</p>
                <input type="email" name="email" value="{{ $userassessor->email }}"
                    class="bg-white h-[25px] w-[800px] border text-[20px] rounded mt-[9px] pl-3">

                <p class="mt-[9px] text-[20px]">เบอร์โทรศัพท์</p>
                <input type="text" name="phone_number" value="{{ $userassessor->phone_number }}"
                    class="bg-white h-[25px] w-[800px] border text-[20px] rounded mt-[9px] pl-3">

                <p class="mt-[9px] text-[20px]">status</p>
                <select name="status" class="bg-white h-[25px] text-[20px] w-[800px] border rounded mt-[9px] pl-3">
                    <option value="active" {{ $userassessor->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="retire" {{ $userassessor->status == 'retire' ? 'selected' : '' }}>Retired</option>
                    <option value="expire" {{ $userassessor->status == 'expire' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div class=" justify-center flex gap-[50px]">
                <a href="{{ route('assessor') }}"
                    class="w-[155px] h-[37px] mt-[32px] bg-[#DBDBDB] rounded-[9px] border flex items-center justify-center text-[20px]">ยกเลิก</a>
                <button type="submit" onclick="alert('แก้ไขสำเร็จ')"
                    class="w-[155px] h-[37px] mt-[32px] bg-[#FFCE00] border rounded-[9px] hover:bg-white text-[20px]">
                    บันทึก
                </button>
            </div>
        </form>
    </div>
@endsection