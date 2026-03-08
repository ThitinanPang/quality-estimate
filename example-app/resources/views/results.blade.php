@extends('layouts.header')

@section('content')
    <form action="{{route('results.collect')}}" method="GET">
        
        <div class="flex flex-col items-center justify-center">
            <p class="text-[36px] mt-[24px]">ข้อมูลการประเมิน</p>
            <div class="w-[1008px] h-[68px] bg-[#FFCE00] rounded-t-[15px] border-b mt-[24px]">
                <p class="text-[32px] ml-[24px] mt-[10px]">ผู้ประเมิน</p>
            </div>
            <div
                class="w-[1008px] h-[750px] left-[322px] shadow-[0_4px_4px_rgba(0,0,0,0.25)] rounded-b-[15px] border flex flex-col items-center justify-center">
                @foreach ($course_assessment as $assessment)
                    <div class="w-[958px] h-[50px] rounded-[14px] bg-[#D9D9D9]">
                        {{-- chairperson --}}
                        <p class="text-[24px] ml-[24px] mt-[5px]">ชื่อ : {{ $assessment->chairperson }}</p>
                    </div>
                @endforeach
                <div class="w-[958px] h-[207px] bg-[#D9D9D9] rounded-[14px] mt-[14px]">
                    <p class="text-[24px] ml-[24px] py-4">หลักสูตรที่ต้องประเมิน<br>
                        คณะ :
                        {{-- faculty_id' --}}
                        <select name="faculty" id="" class="bg-white rounded">
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                        <br>
                        @foreach ($course_assessment as $assessment)
                            {{-- course_id --}}
                            หลักสูตร : {{ $assessment->course->name ?? '-' }}<br>
                            {{-- assessment_type --}}
                            ประเภทการประเมิน :
                            @if ($assessment->assessment_type=='1')
                            แบบ 1 วัน (O)<br>
                            @elseif($assessment->assessment_type=='2')
                            แบบเต็ม (F)
                            @elseif($assessment->assessment_type=='3')
                            แบบ 2 วัน (ปธ.คนนอก)
                            @endif
                            ตำแหน่ง : ประธานกรรมการ
                        @endforeach
                    </p>
                </div>
                @foreach ($course_assessment as $assessment)
                    @php
                        $positionUser = $users[$assessment->position] ?? null;
                        $internUser = $users[$assessment->intern] ?? null;
                    @endphp
                    <div class="w-[958px] h-[207px] bg-[#D9D9D9] rounded-[14px] mt-[14px]">
                        <p class="text-[24px] ml-[24px] py-4">กรรมการ<br>
                            {{-- position --}}
                            ชื่อ : {{ $assessment->position }}<br>
                            {{-- email ของ position --}}
                            อีเมล : {{ $positionUser->email ?? '-' }}<br>
                            {{-- phone_number ของ position --}}
                            เบอร์โทร : {{ $positionUser->phone_number ?? '-' }}</p>
                    </div>
                    <div class="w-[958px] h-[207px] bg-[#D9D9D9] rounded-[14px] mt-[14px]">
                        <p class="text-[24px] ml-[24px] py-4">ผู้ฝึกประสบการณ์<br>
                            {{-- intern --}}
                            ชื่อ : {{ $assessment->intern }}<br>
                            {{-- email ของ intern --}}
                            อีเมล : {{ $internUser->email ?? '-' }}<br>
                            {{-- phone_number ของ intern --}}
                            เบอร์โทร : {{ $internUser->phone_number ?? '-' }}</p>
                    </div>

                @endforeach
            </div>
            <button type="submit"
                class="border bg-[#FFCE00] text-[20px] rounded-[9px] box-border w-[155px] h-[37px] mt-[51px] mb-[20px] hover:bg-white">ประเมิน</button>
        </div>
    </form>
@endsection