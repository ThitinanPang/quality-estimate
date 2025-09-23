@extends('layouts.header')

@section('content')
    <form action="{{route('results.collect')}}" method="GET">
        @csrf
        <div class="flex flex-col items-center justify-center">
            <p class="text-[36px] mt-[24px]">ข้อมูลการประเมิน</p>
            <div class="w-[1008px] h-[68px] bg-[#FFCE00] rounded-t-[15px] border-b mt-[24px]">
                <p class="text-[32px] ml-[24px] mt-[10px]">ผู้ประเมิน</p>
            </div>
            <div
                class="w-[1008px] h-[750px] left-[322px] shadow-[0_4px_4px_rgba(0,0,0,0.25)] rounded-b-[15px] border flex flex-col items-center justify-center">
                <div class="w-[958px] h-[50px] rounded-[14px] bg-[#D9D9D9]">
                    <p class="text-[24px] ml-[24px] mt-[5px]">ชื่อ : {{Auth::user()->name}}</p>
                </div>
                <div class="w-[958px] h-[207px] bg-[#D9D9D9] rounded-[14px] mt-[14px]">
                    @php
                        $conn = new mysqli("localhost", "root", "", "localhost");
                        $sql = "SELECT id, name FROM faculty ORDER BY name ASC";
                        $faculty = $conn->query($sql);
                    @endphp
                    <p class="text-[24px] ml-[24px] py-4">หลักสูตรที่ต้องประเมิน<br>
                        คณะ :
                        <select name="faculty" id="" class="bg-white rounded">
                            @while ($row = $faculty->fetch_assoc())
                                <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                            @endwhile
                        </select><br>
                        หลักสูตร : <br>
                        ประเภทการประเมิน : แบบ 1 วัน (O)<br>
                        ตำแหน่ง : ประธานกรรมการ
                    </p>
                </div>
                <div class="w-[958px] h-[207px] bg-[#D9D9D9] rounded-[14px] mt-[14px]">
                    <p class="text-[24px] ml-[24px] py-4">กรรมการ<br>
                        ชื่อ : {{Auth::user()->name}}<br>
                        อีเมล : {{Auth::user()->email}}<br>
                        เบอร์โทร : {{Auth::user()->phone_number}}</p>
                </div>
                <div class="w-[958px] h-[207px] bg-[#D9D9D9] rounded-[14px] mt-[14px]">
                    <p class="text-[24px] ml-[24px] py-4">ผู้ฝึกประสบการณ์<br>
                        ชื่อ : {{Auth::user()->name}}<br>
                        อีเมล : {{Auth::user()->email}}<br>
                        เบอร์โทร : {{Auth::user()->phone_number}}</p>
                </div>
            </div>
            <button
                class="border bg-[#FFCE00] text-[20px] rounded-[9px] box-border w-[155px] h-[37px] mt-[51px] hover:bg-white">ประเมิน</button>
        </div>
    </form>
@endsection