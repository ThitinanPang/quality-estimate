@extends('layouts.header')

@section('content')
    <div class="{{ auth()->user()->role == 'admin' ? 'pointer-events-none opacity-50' : '' }}">
        <form action="{{route('save.collect')}}" method="POST">
            @csrf
            <div class="flex flex-col items-center justify-center">
                <div
                    class="w-[1032px] h-[265px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[74px] pl-[40px] pt-[10px]">
                    <p class="text-[24px]">ชื่อ - นามสกุล</p>
                    <input type="text" readonly name="name" value="{{Auth::user()->name}}"
                        class="bg-[#BEBEBE] w-[937px] h-[30px] rounded border mt-2 pl-3">
                    <p class="text-[24px]">คณะ</p>
                    <input type="text" readonly name="faculty" value="{{ $faculty->name }}"
                        class="bg-[#BEBEBE] w-[937px] h-[30px] rounded border mt-2 pl-3">
                    <p class="text-[24px]">หลักสูตร</p>
                    <input type="text" readonly name="courses" value="{{$course->name}}"
                        class="bg-[#BEBEBE] w-[937px] h-[30px] rounded border mt-2 pl-3">
                </div>
                <div
                    class="w-[1032px] h-[213px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[32px] pl-[40px] pt-[10px]">
                    <p class="text-[24px]">ส่วนที่ 1 การกำกับมาตรฐาน</p>
                    <select name="criterion" id="" class="w-[918px] h-[42px] border rounded-[5px] bg-white mt-[9px] pl-3">
                        <option value="เป็นไปตามเกณฑ์">เป็นไปตามเกณฑ์</option>
                        <option value="ไม่เป็นไปตามเกณฑ์">ไม่เป็นไปตามเกณฑ์</option>
                    </select>
                    <p class="text-[24px] mt-[9px]">ส่วนที่ 2 ผลการตรวจประเมินตามเกณฑ์ AUN-QA</p>
                    <select name="result" id="" class="w-[918px] h-[42px] border rounded-[5px] bg-white mt-[9px] pl-3">
                        <option value="1">Absolutely Inadequate (Rating 1)</option>
                        <option value="2">Inadequate and Improvement is
                            Necessary (Rating 2)</option>
                        <option value="3">Inadequate but Minor
                            Improvement Will Make It Adequate (Rating 3)</option>
                        <option value="4">Adequate as Expexted (Rating 4)</option>
                        <option value="5">Better Than Adequate (Rating 5)</option>
                        <option value="6">Example of Best Practices (Rating 6)</option>
                        <option value="7">Excellent (Example of
                            World-class of Leading Practices (Rating 7))</option>
                    </select>
                </div>
                <div
                    class="w-[1040px] h-[220px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[32px] pl-[40px] pt-[10px]">
                    <p class="text-[24px]">Strength</p>
                    <input type="text" name="strength" id=""
                        class="pl-3 w-[918px] h-[42px] rounded-[5px] bg-white border mt-[9px]">
                    <p class="text-[24px] mt-[9px]">Area for Improvement</p>
                    <input type="text" name="improvement" id=""
                        class="pl-3 w-[918px] h-[42px] rounded-[5px] bg-white border mt-[9px]">
                </div>
                @php
                    // เก็บชื่อหัวข้อหลัก AUN-QA ไว้ใน array
                    $aunqaTopics = [
                        'AUN-QA 1_Expected Learning Outcomes',
                        'AUN-QA 2_Programme Strucure and Content',
                        'AUN-QA 3_Teaching and Learning Approach',
                        'AUN-QA 4_Student Assessment',
                        'AUN-QA 5_Academic Staff',
                        'AUN-QA 6_Student Support Services',
                        'AUN-QA 7_Facilities and Infrastructure',
                        'AUN-QA 8_Output and Outcomes',
                    ];
                @endphp
                @foreach ($aunqaTopics as $index => $topic)
                    <div class="w-full">
                        <p class="text-[24px] mt-[32px] ml-[250px]">{{$topic}}</p>
                    </div>

                    <div
                        class="w-[1040px] min-h-[200px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[32px] pl-[40px] pr-[40px] pt-[10px] pb-[20px]">
                        @php
                            $subcounts = [5, 7, 6, 7, 8, 6, 9, 5]; // จำนวนหัวข้อย่อยแต่ละหัวข้อ
                            $subCount = $subcounts[$index] ?? 5;   // default 5 ถ้า index เกิน
                        @endphp

                        @for ($j = 1; $j <= $subCount; $j++)
                            <p class="text-[24px] mt-[30px]">AUN-QA {{$index + 1}}.{{$j}}</p>
                            <div class="flex justify-between mt-2">
                                @for ($k = 1; $k <= 7; $k++)
                                    <label class="flex flex-col items-center">
                                        <p class="text-[20px]">{{$k}}</p>
                                        <input type="radio" name="score[{{$index + 1}}][{{$j}}]" value="{{$k}}"
                                            class="score_{{$index}} w-[23px] h-[22px] mt-1">
                                    </label>
                                @endfor
                            </div>
                        @endfor
                    </div>
                    <div
                        class="w-[1040px] h-[126px] bg-[#DBDBDB] border border-black rounded-[39px] mt-[32px] pl-[40px] pt-[10px]">
                        <p class="text-[24px]">AUN-QA {{$index + 1}}_Overall Opinion</p>
                        <div class="grid grid-cols-8 justify-items-start w-full mt-1 ">
                            @for ($j = 1; $j <= 8; $j++)
                                <div class="flex flex-col items-center">
                                    <p>{{ $j == 8 ? 'N/A' : $j }}</p>
                                    <input type="radio" name="overall[{{$index}}]" id="overall-opinion_{{$index}}" value="{{ $j == 8 ? 'na' : $j }}"
                                        class="w-[23px] h-[22px] border rounded-[5px] bg-white">
                                </div>
                            @endfor
                        </div>
                    </div>
                @endforeach
                <button onclick="alert('บันทึกสำเร็จ')"
                    class="w-[155px] h-[37px] mt-[32px] hover:bg-white bg-[#FFCE00] border rounded-[9px]">บันทึก</button>
                <div class="h-[100px]"></div>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const totalTopics = {{ count($aunqaTopics) }};

            for (let t = 0; t < totalTopics; t++) {
                const radios = document.querySelectorAll(`.score_${t}`);
                const output = document.getElementById(`overall-opinion_${t}`);

                radios.forEach(r => r.addEventListener('change', () => {
                    const values = [...radios].filter(r => r.checked).map(r => r.value);
                    if (values.length === 0) {
                        // output.value = ''; // <-- เปลี่ยนจาก textContent เป็น value
                        return;
                    }

                    const freq = {};
                    values.forEach(v => freq[v] = (freq[v] || 0) + 1);

                    let maxVal = null;
                    let maxCount = -1;
                    for (const [key, count] of Object.entries(freq)) {
                        if (count > maxCount) {
                            maxCount = count;
                            maxVal = key;
                        }
                    }
                    output.value = maxVal; // <-- เปลี่ยนจาก textContent เป็น value
                }));
            }
        });
    </script>
@endsection