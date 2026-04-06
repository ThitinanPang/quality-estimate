@extends('layouts.header')

@section('content')
    <form id="assessmentForm" action="{{route('results.collect')}}" method="GET">
        <input type="hidden" id="assessmentTypeInput">
        <div class="flex flex-col items-center justify-center">
            <p class="text-[36px] mt-[24px]">ข้อมูลการประเมิน</p>
            
            <div class="w-[1008px] h-[68px] bg-[#FFCE00] rounded-t-[15px] border-b mt-[24px]">
                <p class="text-[32px] ml-[24px] mt-[10px]">ผู้ประเมิน</p>
            </div>

            @php
                $firstAssessment = $course_assessment->first();
                $courses = $course_assessment->pluck('course')->filter();
            @endphp

            <div class="w-[1008px] h-auto p-3 shadow-[0_4px_4px_rgba(0,0,0,0.25)] rounded-b-[15px] border flex flex-col items-center justify-center gap-4">
                
                {{-- 1. ส่วนประธาน (ข้อมูลคงที่จาก Assessment แรก) --}}
                <div class="w-[958px] h-[50px] rounded-[14px] bg-[#D9D9D9]">
                    <p class="text-[24px] ml-[24px] mt-[5px]">ชื่อประธาน : <span id="chairpersonText">{{ $firstAssessment->chairperson }}</span></p>
                </div>

                {{-- 2. ส่วนข้อมูลหลักสูตรและคณะ (เปลี่ยนตาม JS) --}}
                <div class="w-[958px] h-auto min-h-[180px] bg-[#D9D9D9] rounded-[14px] p-6">
                    <p class="text-[24px]">
                        หลักสูตรที่ต้องประเมิน<br>
                        คณะ : <span id="facultyText"></span><br>
                        <input type="hidden" name="faculty" id="facultyInput">
                        @if($courses->count() > 1)
                            หลักสูตร : 
                            <select name="course_id" id="courseSelect" class="bg-white rounded px-2 py-1">
                                @foreach($courses as $course)
                                    @php 
                                        $asmt = $course_assessment->firstWhere('course_id', $course->id); 
                                        $pUser = $users[$asmt->position] ?? null;
                                        $iUser = $users[$asmt->intern] ?? null;
                                    @endphp
                                    <option value="{{ $course->id }}" 
                                        data-chair="{{ $asmt->chairperson ?? '-' }}"
                                        data-type="{{ $asmt->assessment_type ?? '' }}"
                                        data-faculty="{{ $asmt->course->faculty->name ?? '-' }}"
                                        data-p-name="{{ $asmt->position ?? '-' }}"
                                        data-p-email="{{ $pUser->email ?? '-' }}"
                                        data-p-phone="{{ $pUser->phone_number ?? '-' }}"
                                        data-i-name="{{ $asmt->intern ?? '-' }}"
                                        data-i-email="{{ $iUser->email ?? '-' }}"
                                        data-i-phone="{{ $iUser->phone_number ?? '-' }}"
                                    >
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            @php 
                                $alone = $course_assessment->first(); 
                                $pUser = $users[$alone->position] ?? null;
                                $iUser = $users[$alone->intern] ?? null;
                            @endphp
                            หลักสูตร : {{ $courses->first()->name ?? '-' }}
                            <input type="hidden" name="course_id" value="{{ $courses->first()->id }}">
                            <input type="hidden" id="courseSelect" 
                                data-chair="{{ $alone->chairperson ?? '-' }}"
                                data-type="{{ $alone->assessment_type ?? '' }}"
                                data-faculty="{{ $alone->course->faculty->name ?? '-' }}"
                                data-p-name="{{ $alone->position ?? '-' }}"
                                data-p-email="{{ $pUser->email ?? '-' }}"
                                data-p-phone="{{ $pUser->phone_number ?? '-' }}"
                                data-i-name="{{ $alone->intern ?? '-' }}"
                                data-i-email="{{ $iUser->email ?? '-' }}"
                                data-i-phone="{{ $iUser->phone_number ?? '-' }}"
                            >
                        @endif
                        <br>
                        ประเภทการประเมิน : <span id="assessmentTypeText"></span><br>
                        ตำแหน่ง : ประธานกรรมการ
                    </p>
                </div>

                {{-- 3. กล่องกรรมการ (เปลี่ยนตาม JS) --}}
                <div class="w-[958px] h-auto min-h-[150px] bg-[#D9D9D9] rounded-[14px] p-6">
                    <p class="text-[24px]">กรรมการ<br>
                        ชื่อ : <span id="pNameText"></span><br>
                        อีเมล : <span id="pEmailText"></span><br>
                        เบอร์โทร : <span id="pPhoneText"></span>
                    </p>
                </div>

                {{-- 4. กล่องผู้ฝึกประสบการณ์ (เปลี่ยนตาม JS) --}}
                <div class="w-[958px] h-auto min-h-[150px] bg-[#D9D9D9] rounded-[14px] p-6">
                    <p class="text-[24px]">ผู้ฝึกประสบการณ์<br>
                        ชื่อ : <span id="iNameText"></span><br>
                        อีเมล : <span id="iEmailText"></span><br>
                        เบอร์โทร : <span id="iPhoneText"></span>
                    </p>
                </div>

            </div>

            <button type="submit" class="border bg-[#FFCE00] text-[20px] rounded-[9px] w-[155px] h-[37px] mt-[51px] mb-[20px] hover:bg-white transition-colors">
                ประเมิน
            </button>
        </div>
    </form>
<script>
    function updateAllData() {
        const element = document.getElementById('courseSelect');
        if (!element) return;

        let ds = {};
        if (element.tagName === 'SELECT') {
            const selected = element.options[element.selectedIndex];
            ds = selected ? selected.dataset : {};
        } else {
            ds = element.dataset;
        }

        // อัปเดตข้อความบนหน้าจอ
        document.getElementById('chairpersonText').innerText = ds.chair || '-';
        const types = { '1': 'แบบ 1 วัน (O)', '2': 'แบบเต็ม (F)', '3': 'แบบ 2 วัน (ปธ.คนนอก)' };
        document.getElementById('assessmentTypeText').innerText = types[ds.type] || '-';
        
        // --- ส่วนสำคัญ: เก็บค่าเข้า Input เพื่อใช้เช็คตอนกดปุ่ม ---
        document.getElementById('assessmentTypeInput').value = ds.type || '';
        document.getElementById('facultyText').innerText = ds.faculty || '-';
        document.getElementById('facultyInput').value = ds.faculty || '-';

        document.getElementById('pNameText').innerText = ds.pName || '-';
        document.getElementById('pEmailText').innerText = ds.pEmail || '-';
        document.getElementById('pPhoneText').innerText = ds.pPhone || '-';
        document.getElementById('iNameText').innerText = ds.iName || '-';
        document.getElementById('iEmailText').innerText = ds.iEmail || '-';
        document.getElementById('iPhoneText').innerText = ds.iPhone || '-';
    }

    // ฟังก์ชันเช็คสิทธิ์ตอนกด Submit
    document.getElementById('assessmentForm').addEventListener('submit', function(e) {
        // 1. ดึงค่า Assessment Type ที่เลือกอยู่
        const type = document.getElementById('assessmentTypeInput').value;
        
        // 2. ดึงชื่อคน Login ปัจจุบัน (Blade จะ Render เป็น String)
        const currentUser = "{{ auth()->user()->name }}".trim();
        
        // 3. ดึงชื่อจากหน้าจอมาเช็ค
        const chairName = document.getElementById('chairpersonText').innerText.trim();
        const positionName = document.getElementById('pNameText').innerText.trim();

        // 4. เช็ค Logic
        if (type === '1' || type === '2') {
            if (currentUser !== chairName) {
                e.preventDefault(); // สั่งไม่ให้ส่งฟอร์ม
                alert("เฉพาะ 'ประธาน' (" + chairName + ") เท่านั้นที่มีสิทธิ์ประเมิน");
                return false;
            }
        } 
        else if (type === '3') {
            if (currentUser !== positionName) {
                e.preventDefault(); // สั่งไม่ให้ส่งฟอร์ม
                alert("เนื่องจากประธานเป็นคนนอก ในรูปแบบนี้เฉพาะ 'กรรมการ' (" + positionName + ") เท่านั้นที่มีสิทธิ์ประเมิน");
                return false;
            }
        }
    });

    // เรียกทำงานเมื่อโหลดหน้า
    window.onload = updateAllData;
    
    // ตรวจสอบ Event Change สำหรับ Select
    const courseSelect = document.getElementById('courseSelect');
    if (courseSelect && courseSelect.tagName === 'SELECT') {
        courseSelect.addEventListener('change', updateAllData);
    }
</script>
@endsection