@extends('layouts.header')

@section('content')
    <form action="{{route('results.collect')}}" method="GET">
        <div class="flex flex-col items-center justify-center">
            <p class="text-[36px] mt-[24px]">ข้อมูลการประเมิน</p>
            <div class="w-[1008px] h-[68px] bg-[#FFCE00] rounded-t-[15px] border-b mt-[24px]">
                <p class="text-[32px] ml-[24px] mt-[10px]">ผู้ประเมิน</p>
            </div>
            @php
                $firstAssessment = $course_assessment->first();
                $courses = $course_assessment->pluck('course')->filter();
                // กำหนดค่าเริ่มต้นให้กับ $assessment จากรายการแรก
                $assessment = $firstAssessment;
            @endphp
            <div
                class="w-[1008px] h-auto p-3 left-[322px] shadow-[0_4px_4px_rgba(0,0,0,0.25)] rounded-b-[15px] border flex flex-col items-center justify-center">
                <div class="w-[958px] h-[50px] rounded-[14px] bg-[#D9D9D9]">
                    {{-- chairperson --}}
                    <p class="text-[24px] ml-[24px] mt-[5px]">ชื่อ : {{ $firstAssessment->chairperson }}</p>
                </div>
                <div class="w-[958px] h-[207px] bg-[#D9D9D9] rounded-[14px] mt-[14px]">
                    <p class="text-[24px] ml-[24px] py-4">หลักสูตรที่ต้องประเมิน<br>
                        คณะ :
                        {{-- คณะ --}}
                        <span>{{ $firstAssessment->course->faculty->name ?? '-' }}</span>
                        <input type="hidden" name="faculty" value="{{ $firstAssessment->course->faculty->name }}">
                        <br>
                        {{-- course_id --}}
                        @if($courses->count() > 1)
                            หลักสูตร :
                            <select name="course_id" id="courseSelect" class="bg-white rounded px-2 py-1">
                                @foreach($courses as $course)
                                    @php $assessment = $course_assessment->firstWhere('course_id', $course->id); @endphp
                                    <option value="{{ $course->id }}" data-type="{{ $assessment->assessment_type ?? '' }}">
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                            <br>
                        @else
                            @php $alone = $course_assessment->first(); @endphp
                            หลักสูตร : {{ $courses->first()->name ?? '-' }}<br>
                            <input type="hidden" name="course_id" value="{{ $courses->first()->id }}">

                            {{-- เพิ่มกรงนี้เพื่อให้ JS หาค่าเจอแม้ไม่มี Select --}}
                            <input type="hidden" id="courseSelect" data-type="{{ $alone->assessment_type ?? '' }}">
                        @endif

                        {{-- ส่วนแสดงผล --}}
                        ประเภทการประเมิน : <span id="assessmentTypeText"></span><br>
                        ตำแหน่ง : ประธานกรรมการ
                    </p>
                </div>
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
            </div>
            <button type="submit"
                class="border bg-[#FFCE00] text-[20px] rounded-[9px] box-border w-[155px] h-[37px] mt-[51px] mb-[20px] hover:bg-white">ประเมิน</button>
        </div>
    </form>
    <script>
        function updateAssessmentType() {
            const element = document.getElementById('courseSelect');
            if (!element) return; // ป้องกัน Error ถ้าหา Element ไม่เจอ

            let type = '';

            // เช็คว่าเป็น Select หรือ Input
            if (element.tagName === 'SELECT') {
                const selected = element.options[element.selectedIndex];
                type = selected ? selected.getAttribute('data-type') : '';
            } else {
                type = element.getAttribute('data-type');
            }

            let text = '-';
            if (type === '1') text = 'แบบ 1 วัน (O)';
            else if (type === '2') text = 'แบบเต็ม (F)';
            else if (type === '3') text = 'แบบ 2 วัน (ปธ.คนนอก)';

            document.getElementById('assessmentTypeText').innerText = text;
        }

        // เรียกตอนโหลดหน้า
        window.onload = updateAssessmentType;

        // ผูก Event เฉพาะเมื่อเป็น Select
        const courseSelect = document.getElementById('courseSelect');
        if (courseSelect && courseSelect.tagName === 'SELECT') {
            courseSelect.addEventListener('change', updateAssessmentType);
        }
    </script>
@endsection