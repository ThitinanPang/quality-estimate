@extends('layouts.header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
    <div class="flex">
        <p class="text-[36px] ml-[85px] mt-[10px]">จัดการผู้ประเมินหลักสูตร</p>
        <form method="GET">
            <div
                class="w-[236px] h-[46px] bg-[#FFCE00] mt-[15px] ml-[800px]  rounded-[24px] text-[20px] items-center flex justify-center">
                ปีการศึกษา
                <select name="thai_year" id="thai-year" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
            </div>
        </form>
    </div>
    <div class="flex ml-[85px] mt-3 mb-3 items-center gap-x-4">
        <form action="" method="GET">
            <span class="text-[16px]">วิทยาเขต :</span>
            <select name="campus" onchange="this.form.submit()" class="w-[196px] h-[35px] bg-[#DBDBDB] rounded-[8px] text-center px-2">
                <option value="" selected disabled>เลือกข้อมูล</option>
                <option value="บางแสน" {{ request('campus')=='บางแสน'?'selected':'' }}>บางแสน</option>
                <option value="จันทบุรี" {{ request('campus')=='จันทบุรี'?'selected':'' }}>จันทบุรี</option>
            </select>
        </form>
        <form action="{{ route('course-assessor.store') }}" method="POST">
        @csrf
        <span class="text-[16px]">กลุ่มวิชาสาขา :</span>
        <select name="subject_group" id="" class="w-[230px] h-[35px] bg-[#DBDBDB] rounded-[8px] text-center">
            <option value="" selected disabled>เลือกข้อมูล</option>
            <option value="วิทยาศาสตร์สุขภาพ">วิทยาศาสตร์สุขภาพ</option>
            <option value="วิทยาศาสตร์และเทคโนโลยี">วิทยาศาสตร์และเทคโนโลยี</option>
            <option value="วิศวกรรมศาตร์">วิศวกรรมศาตร์</option>
            <option value="สถาปัตยกรรมศาสตร์">สถาปัตยกรรมศาสตร์</option>
            <option value="เกษตรศาสตร์">เกษตรศาสตร์</option>
            <option value="บริหารธุรกิจ">บริหารธุรกิจ</option>
            <option value="ศิลปกรรมศาสตร์">ศิลปกรรมศาสตร์</option>
            <option value="ครุศาสตร์/ศึกษาศาสตร์">ครุศาสตร์/ศึกษาศาสตร์</option>
            <option value="สหสาขาวิชา">สหสาขาวิชา</option>
        </select>
        <button type="submit" class="w-[155px] h-[37px] ml-[610px] border rounded-[9px] bg-[#FFCE00] text-[20px]">
           บันทึก
        </button>
    </div>
    @php
        $selectedThaiYear = $_GET['thai_year'] ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;
    @endphp
        <div class="flex pl-[85px] pt-[10px] w-full">
            <div class="w-[1500px] h-[380px] overflow-auto">
                <table id="myTable" class="w-[1500px] h-[390px] border-collapse">
                    <thead class="bg-[#FFCE00]">
                        <tr>
                            <th class="p-2 border text-[18px] whitespace-nowrap">ลำดับที่</th>
                            <th class="px-4 min-w-[280px] border text-[18px]">หลักสูตร</th>
                            <th class="px-4 border text-[18px] whitespace-nowrap">คณะ/วิทยาลัย</th>
                            <th class="px-4 border text-[18px] whitespace-nowrap">ระดับการศึกษา</th>
                            <th class="px-4 border text-[18px] whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    รูปแบบการประเมิน
                                    <a href="" onclick="openModal(); return false;">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.5 0C13.2848 0 15.9555 1.10625 17.9246 3.07538C19.8938 5.04451 21 7.71523 21 10.5C21 13.2848 19.8938 15.9555 17.9246 17.9246C15.9555 19.8938 13.2848 21 10.5 21C7.71523 21 5.04451 19.8938 3.07538 17.9246C1.10625 15.9555 0 13.2848 0 10.5C0 7.71523 1.10625 5.04451 3.07538 3.07538C5.04451 1.10625 7.71523 0 10.5 0ZM10.5 19.5C12.8869 19.5 15.1761 18.5518 16.864 16.864C18.5518 15.1761 19.5 12.8869 19.5 10.5C19.5 8.11305 18.5518 5.82387 16.864 4.13604C15.1761 2.44821 12.8869 1.5 10.5 1.5C8.11305 1.5 5.82387 2.44821 4.13604 4.13604C2.44821 5.82387 1.5 8.11305 1.5 10.5C1.5 12.8869 2.44821 15.1761 4.13604 16.864C5.82387 18.5518 8.11305 19.5 10.5 19.5ZM11.625 15.375C11.625 15.6734 11.5065 15.9595 11.2955 16.1705C11.0845 16.3815 10.7984 16.5 10.5 16.5C10.2016 16.5 9.91548 16.3815 9.7045 16.1705C9.49353 15.9595 9.375 15.6734 9.375 15.375C9.375 15.0766 9.49353 14.7905 9.7045 14.5795C9.91548 14.3685 10.2016 14.25 10.5 14.25C10.7984 14.25 11.0845 14.3685 11.2955 14.5795C11.5065 14.7905 11.625 15.0766 11.625 15.375ZM10.5 4.5C10.6989 4.5 10.8897 4.57902 11.0303 4.71967C11.171 4.86032 11.25 5.05109 11.25 5.25V12C11.25 12.1989 11.171 12.3897 11.0303 12.5303C10.8897 12.671 10.6989 12.75 10.5 12.75C10.3011 12.75 10.1103 12.671 9.96967 12.5303C9.82902 12.3897 9.75 12.1989 9.75 12V5.25C9.75 5.05109 9.82902 4.86032 9.96967 4.71967C10.1103 4.57902 10.3011 4.5 10.5 4.5Z" fill="black"/>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 border text-[18px] whitespace-nowrap">ประธานการประเมิน</th>
                            <th class="px-4 border text-[18px]">กรรมการ</th>
                            <th class="px-4 border text-[18px] whitespace-nowrap">ผู้ฝึกประสบการณ์</th>
                            <th class="px-4 border text-[18px] whitespace-nowrap">วันตรวจประเมิน</th>
                            <th class="px-4 border text-[18px]">เลขา</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $hasData = false;
                        $no = 1; @endphp
                        @foreach($faculties as $faculty)
                            @foreach($faculty->courses as $course)
                                @php $hasData = true; @endphp
                                <tr>
                                    <input type="hidden" name="courses[{{ $course->id }}][course_id]" value="{{ $course->id }}">
                                    <input type="hidden" name="courses[{{ $course->id }}][faculty_id]" value="{{ $faculty->id }}">
                                    <input type="hidden" name="courses[{{ $course->id }}][campus]" value="{{ request('campus') }}">
                                    <td class="px-4 border text-center">{{ $no++ }}</td>
                                    <td class="px-4 border ">{{ $course->name }}</td>
                                    <td class="px-4 border whitespace-nowrap text-center">{{ $faculty->name }}</td>
                                    <td class="px-4 border text-center">
                                        @if($course->level == 1)
                                            ปริญญาตรี
                                        @elseif($course->level == 2)
                                            ปริญญาโท
                                        @elseif($course->level == 3)
                                            ปริญญาเอก
                                        @else
                                            ไม่ระบุ
                                        @endif
                                    </td>
                                    <input type="hidden" name="courses[{{ $course->id }}][education_level]" value="{{ $course->level }}">
                                    <td class="px-4 py-4 border text-center">
                                        <select name="courses[{{ $course->id }}][assessment_type]" id="" class="border">
                                            <option value="1" class="text-center">O</option>
                                            <option value="2" class="text-center">F</option>
                                            <option value="3" class="text-center">ปธ.คนนอก</option>
                                        </select>
                                    </td>
                                    <td class="px-3 border text-center">
                                        <div x-data="{ open: false, title: '', selectedUser: '', tempUser: '' }"
                                            class="relative flex items-center justify-center">
                                            <!-- ปุ่มเปิด Modal -->
                                            <button x-show="!selectedUser" type="button" @click="open = true; title = 'ประธานการประเมิน'"
                                                class="w-[155px] h-[37px] bg-[#FFCE00] border border-black rounded-[9px] flex items-center justify-center text-[18px] hover:bg-white">
                                                <svg width="23" height="22" viewBox="0 0 23 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.5 7.75V14.25M14.875 11H8.125M21.625 11C21.625 12.2804 21.3631 13.5482 20.8543 14.7312C20.3455 15.9141 19.5996 16.9889 18.6595 17.8943C17.7193 18.7997 16.6031 19.5178 15.3747 20.0078C14.1462 20.4978 12.8296 20.75 11.5 20.75C10.1704 20.75 8.85375 20.4978 7.62533 20.0078C6.39691 19.5178 5.28074 18.7997 4.34054 17.8943C3.40035 16.9889 2.65455 15.9141 2.14572 14.7312C1.63689 13.5482 1.375 12.2804 1.375 11C1.375 8.41414 2.44174 5.93419 4.34054 4.10571C6.23935 2.27723 8.81468 1.25 11.5 1.25C14.1853 1.25 16.7606 2.27723 18.6595 4.10571C20.5583 5.93419 21.625 8.41414 21.625 11Z"
                                                        stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                เพิ่มข้อมูล
                                            </button>
                                            <div class="flex items-center gap-2">
                                                <span x-show="selectedUser" x-cloak x-text="selectedUser"
                                                    class=" whitespace-nowrap"></span>
                                                <svg x-show="selectedUser" x-cloak
                                                    @click="open = true; title = 'ประธานการประเมิน';tempUser = selectedUser"
                                                    class="cursor-pointer" width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.5 2.76777H2.66667C2.22464 2.76777 1.80072 2.94336 1.48816 3.25592C1.17559 3.56848 1 3.99241 1 4.43443V16.1011C1 16.5431 1.17559 16.9671 1.48816 17.2796C1.80072 17.5922 2.22464 17.7678 2.66667 17.7678H14.3333C14.7754 17.7678 15.1993 17.5922 15.5118 17.2796C15.8244 16.9671 16 16.5431 16 16.1011V10.2678M14.75 1.51777C15.0815 1.18625 15.5312 1 16 1C16.4688 1 16.9185 1.18625 17.25 1.51777C17.5815 1.84929 17.7678 2.29893 17.7678 2.76777C17.7678 3.23661 17.5815 3.68625 17.25 4.01777L9.33333 11.9344L6 12.7678L6.83333 9.43443L14.75 1.51777Z"
                                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <span x-show="selectedUser" x-cloak class="text-[18px]">|</span>
                                                <svg x-show="selectedUser" x-cloak @click="selectedUser = ''; tempUser = ''"
                                                    class=" cursor-pointer" width="16" height="18" viewBox="0 0 16 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M3 18C2.45 18 1.97933 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z"
                                                        fill="black" />
                                                </svg>

                                            </div>
                                            <!-- Modal -->
                                            <div x-show="open" x-cloak
                                                class="fixed inset-0 bg-opacity-50 flex flex-col items-center justify-center z-50">
                                                <div
                                                    class="bg-[#FFCE00] w-[400px] h-[45px] rounded-t-[14px] flex items-center justify-between px-3 border border-b-0">
                                                    <p class="ml-3" x-text="title"></p>
                                                    <svg class="" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M10 11.4L12.9 14.3C13.0833 14.4833 13.3167 14.575 13.6 14.575C13.8833 14.575 14.1167 14.4833 14.3 14.3C14.4833 14.1167 14.575 13.8833 14.575 13.6C14.575 13.3167 14.4833 13.0833 14.3 12.9L11.4 10L14.3 7.1C14.4833 6.91667 14.575 6.68333 14.575 6.4C14.575 6.11667 14.4833 5.88333 14.3 5.7C14.1167 5.51667 13.8833 5.425 13.6 5.425C13.3167 5.425 13.0833 5.51667 12.9 5.7L10 8.6L7.1 5.7C6.91667 5.51667 6.68333 5.425 6.4 5.425C6.11667 5.425 5.88333 5.51667 5.7 5.7C5.51667 5.88333 5.425 6.11667 5.425 6.4C5.425 6.68333 5.51667 6.91667 5.7 7.1L8.6 10L5.7 12.9C5.51667 13.0833 5.425 13.3167 5.425 13.6C5.425 13.8833 5.51667 14.1167 5.7 14.3C5.88333 14.4833 6.11667 14.575 6.4 14.575C6.68333 14.575 6.91667 14.4833 7.1 14.3L10 11.4ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88334 18.6867 3.825 17.9743 2.925 17.075C2.025 16.1757 1.31267 15.1173 0.788001 13.9C0.263335 12.6827 0.000667933 11.3827 1.26582e-06 10C-0.000665401 8.61733 0.262001 7.31733 0.788001 6.1C1.314 4.88267 2.02633 3.82433 2.925 2.925C3.82367 2.02567 4.882 1.31333 6.1 0.788C7.318 0.262667 8.618 0 10 0C11.382 0 12.682 0.262667 13.9 0.788C15.118 1.31333 16.1763 2.02567 17.075 2.925C17.9737 3.82433 18.6863 4.88267 19.213 6.1C19.7397 7.31733 20.002 8.61733 20 10C19.998 11.3827 19.7353 12.6827 19.212 13.9C18.6887 15.1173 17.9763 16.1757 17.075 17.075C16.1737 17.9743 15.1153 18.687 13.9 19.213C12.6847 19.739 11.3847 20.0013 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z"
                                                            fill="black" />
                                                    </svg>
                                                </div>
                                                <div @click.away="open = false"
                                                    class="bg-white p-6 rounded-b-[14px] w-[400px] h-[114px] border shadow-lg">
                                                    <select x-model="tempUser" name="assessor_user_id"
                                                        class="w-full p-2 border mb-4 bg-[#DBDBDB] rounded-[14px]">
                                                        <option value="">เลือกผู้ประเมิน</option>
                                                        @foreach($users->filter(fn($u) => trim($u->faculty) != trim($faculty->name) && $u->assessor_type == 'lead') as $user)
                                                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="courses[{{ $course->id }}][chairperson]" x-model="selectedUser">
                                                    <div class="flex justify-center gap-2">
                                                        <button type="button" @click="selectedUser = tempUser; open = false"
                                                            class="bg-[#FFCE00] border rounded-[100px] w-[96px] h-[23px] hover:bg-yellow-300">บันทึก</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 border">
                                        <div x-data="{ open: false, title: '', selectedUser: '', tempUser: '' }"
                                            class="relative flex items-center justify-center">
                                            <!-- ปุ่มเปิด Modal -->
                                            <button x-show="!selectedUser" type="button" @click="open = true; title = 'กรรมการ'"
                                                class="w-[155px] h-[37px] bg-[#FFCE00] border border-black rounded-[9px] flex items-center justify-center text-[18px] hover:bg-white">
                                                <svg width="23" height="22" viewBox="0 0 23 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.5 7.75V14.25M14.875 11H8.125M21.625 11C21.625 12.2804 21.3631 13.5482 20.8543 14.7312C20.3455 15.9141 19.5996 16.9889 18.6595 17.8943C17.7193 18.7997 16.6031 19.5178 15.3747 20.0078C14.1462 20.4978 12.8296 20.75 11.5 20.75C10.1704 20.75 8.85375 20.4978 7.62533 20.0078C6.39691 19.5178 5.28074 18.7997 4.34054 17.8943C3.40035 16.9889 2.65455 15.9141 2.14572 14.7312C1.63689 13.5482 1.375 12.2804 1.375 11C1.375 8.41414 2.44174 5.93419 4.34054 4.10571C6.23935 2.27723 8.81468 1.25 11.5 1.25C14.1853 1.25 16.7606 2.27723 18.6595 4.10571C20.5583 5.93419 21.625 8.41414 21.625 11Z"
                                                        stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                เพิ่มข้อมูล
                                            </button>
                                            <div class="flex items-center gap-2">
                                                <span x-show="selectedUser" x-cloak x-text="selectedUser"
                                                    class=" whitespace-nowrap"></span>
                                                <svg x-show="selectedUser" x-cloak
                                                    @click="open = true; title = 'กรรมการ';tempUser = selectedUser"
                                                    class="cursor-pointer" width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.5 2.76777H2.66667C2.22464 2.76777 1.80072 2.94336 1.48816 3.25592C1.17559 3.56848 1 3.99241 1 4.43443V16.1011C1 16.5431 1.17559 16.9671 1.48816 17.2796C1.80072 17.5922 2.22464 17.7678 2.66667 17.7678H14.3333C14.7754 17.7678 15.1993 17.5922 15.5118 17.2796C15.8244 16.9671 16 16.5431 16 16.1011V10.2678M14.75 1.51777C15.0815 1.18625 15.5312 1 16 1C16.4688 1 16.9185 1.18625 17.25 1.51777C17.5815 1.84929 17.7678 2.29893 17.7678 2.76777C17.7678 3.23661 17.5815 3.68625 17.25 4.01777L9.33333 11.9344L6 12.7678L6.83333 9.43443L14.75 1.51777Z"
                                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <span x-show="selectedUser" x-cloak class="text-[18px]">|</span>
                                                <svg x-show="selectedUser" x-cloak @click="selectedUser = ''; tempUser = ''"
                                                    class=" cursor-pointer" width="16" height="18" viewBox="0 0 16 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M3 18C2.45 18 1.97933 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z"
                                                        fill="black" />
                                                </svg>

                                            </div>
                                            <!-- Modal -->
                                            <div x-show="open" x-cloak
                                                class="fixed inset-0 bg-opacity-50 flex flex-col items-center justify-center z-50">
                                                <div
                                                    class="bg-[#FFCE00] w-[400px] h-[45px] rounded-t-[14px] flex items-center justify-between px-3 border border-b-0">
                                                    <p class="ml-3" x-text="title"></p>
                                                    <svg class="" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M10 11.4L12.9 14.3C13.0833 14.4833 13.3167 14.575 13.6 14.575C13.8833 14.575 14.1167 14.4833 14.3 14.3C14.4833 14.1167 14.575 13.8833 14.575 13.6C14.575 13.3167 14.4833 13.0833 14.3 12.9L11.4 10L14.3 7.1C14.4833 6.91667 14.575 6.68333 14.575 6.4C14.575 6.11667 14.4833 5.88333 14.3 5.7C14.1167 5.51667 13.8833 5.425 13.6 5.425C13.3167 5.425 13.0833 5.51667 12.9 5.7L10 8.6L7.1 5.7C6.91667 5.51667 6.68333 5.425 6.4 5.425C6.11667 5.425 5.88333 5.51667 5.7 5.7C5.51667 5.88333 5.425 6.11667 5.425 6.4C5.425 6.68333 5.51667 6.91667 5.7 7.1L8.6 10L5.7 12.9C5.51667 13.0833 5.425 13.3167 5.425 13.6C5.425 13.8833 5.51667 14.1167 5.7 14.3C5.88333 14.4833 6.11667 14.575 6.4 14.575C6.68333 14.575 6.91667 14.4833 7.1 14.3L10 11.4ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88334 18.6867 3.825 17.9743 2.925 17.075C2.025 16.1757 1.31267 15.1173 0.788001 13.9C0.263335 12.6827 0.000667933 11.3827 1.26582e-06 10C-0.000665401 8.61733 0.262001 7.31733 0.788001 6.1C1.314 4.88267 2.02633 3.82433 2.925 2.925C3.82367 2.02567 4.882 1.31333 6.1 0.788C7.318 0.262667 8.618 0 10 0C11.382 0 12.682 0.262667 13.9 0.788C15.118 1.31333 16.1763 2.02567 17.075 2.925C17.9737 3.82433 18.6863 4.88267 19.213 6.1C19.7397 7.31733 20.002 8.61733 20 10C19.998 11.3827 19.7353 12.6827 19.212 13.9C18.6887 15.1173 17.9763 16.1757 17.075 17.075C16.1737 17.9743 15.1153 18.687 13.9 19.213C12.6847 19.739 11.3847 20.0013 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z"
                                                            fill="black" />
                                                    </svg>
                                                </div>
                                                <div @click.away="open = false"
                                                    class="bg-white p-6 rounded-b-[14px] w-[400px] h-[114px] border shadow-lg">
                                                    <select x-model="tempUser" name="assessor_user_id"
                                                        class="w-full p-2 border mb-4 bg-[#DBDBDB] rounded-[14px]">
                                                        <option value="">เลือกผู้ประเมิน</option>
                                                        @foreach($users->filter(fn($u) => trim($u->faculty) != trim($faculty->name) && in_array($u->assessor_type, ['senior', 'lead']))  as $user)
                                                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="courses[{{ $course->id }}][position]" x-model="selectedUser">
                                                    <div class="flex justify-center gap-2">
                                                        <button type="button" @click="selectedUser = tempUser; open = false"
                                                            class="bg-[#FFCE00] border rounded-[100px] w-[96px] h-[23px] hover:bg-yellow-300">บันทึก</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 border">
                                        <div x-data="{ open: false, title: '', selectedUser: '', tempUser: '' }"
                                            class="relative flex items-center justify-center">
                                            <!-- ปุ่มเปิด Modal -->
                                            <button x-show="!selectedUser" type="button" @click="open = true; title = 'ผู้ฝึกประสบการณ์'"
                                                class="w-[155px] h-[37px] bg-[#FFCE00] border border-black rounded-[9px] flex items-center justify-center text-[18px] hover:bg-white">
                                                <svg width="23" height="22" viewBox="0 0 23 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.5 7.75V14.25M14.875 11H8.125M21.625 11C21.625 12.2804 21.3631 13.5482 20.8543 14.7312C20.3455 15.9141 19.5996 16.9889 18.6595 17.8943C17.7193 18.7997 16.6031 19.5178 15.3747 20.0078C14.1462 20.4978 12.8296 20.75 11.5 20.75C10.1704 20.75 8.85375 20.4978 7.62533 20.0078C6.39691 19.5178 5.28074 18.7997 4.34054 17.8943C3.40035 16.9889 2.65455 15.9141 2.14572 14.7312C1.63689 13.5482 1.375 12.2804 1.375 11C1.375 8.41414 2.44174 5.93419 4.34054 4.10571C6.23935 2.27723 8.81468 1.25 11.5 1.25C14.1853 1.25 16.7606 2.27723 18.6595 4.10571C20.5583 5.93419 21.625 8.41414 21.625 11Z"
                                                        stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                เพิ่มข้อมูล
                                            </button>
                                            <div class="flex items-center gap-2">
                                                <span x-show="selectedUser" x-cloak x-text="selectedUser"
                                                    class=" whitespace-nowrap"></span>
                                                <svg x-show="selectedUser" x-cloak
                                                    @click="open = true; title = 'ผู้ฝึกประสบการณ์';tempUser = selectedUser"
                                                    class="cursor-pointer" width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.5 2.76777H2.66667C2.22464 2.76777 1.80072 2.94336 1.48816 3.25592C1.17559 3.56848 1 3.99241 1 4.43443V16.1011C1 16.5431 1.17559 16.9671 1.48816 17.2796C1.80072 17.5922 2.22464 17.7678 2.66667 17.7678H14.3333C14.7754 17.7678 15.1993 17.5922 15.5118 17.2796C15.8244 16.9671 16 16.5431 16 16.1011V10.2678M14.75 1.51777C15.0815 1.18625 15.5312 1 16 1C16.4688 1 16.9185 1.18625 17.25 1.51777C17.5815 1.84929 17.7678 2.29893 17.7678 2.76777C17.7678 3.23661 17.5815 3.68625 17.25 4.01777L9.33333 11.9344L6 12.7678L6.83333 9.43443L14.75 1.51777Z"
                                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <span x-show="selectedUser" x-cloak class="text-[18px]">|</span>
                                                <svg x-show="selectedUser" x-cloak @click="selectedUser = ''; tempUser = ''"
                                                    class=" cursor-pointer" width="16" height="18" viewBox="0 0 16 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M3 18C2.45 18 1.97933 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z"
                                                        fill="black" />
                                                </svg>

                                            </div>
                                            <!-- Modal -->
                                            <div x-show="open" x-cloak
                                                class="fixed inset-0 bg-opacity-50 flex flex-col items-center justify-center z-50">
                                                <div
                                                    class="bg-[#FFCE00] w-[400px] h-[45px] rounded-t-[14px] flex items-center justify-between px-3 border border-b-0">
                                                    <p class="ml-3" x-text="title"></p>
                                                    <svg class="" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M10 11.4L12.9 14.3C13.0833 14.4833 13.3167 14.575 13.6 14.575C13.8833 14.575 14.1167 14.4833 14.3 14.3C14.4833 14.1167 14.575 13.8833 14.575 13.6C14.575 13.3167 14.4833 13.0833 14.3 12.9L11.4 10L14.3 7.1C14.4833 6.91667 14.575 6.68333 14.575 6.4C14.575 6.11667 14.4833 5.88333 14.3 5.7C14.1167 5.51667 13.8833 5.425 13.6 5.425C13.3167 5.425 13.0833 5.51667 12.9 5.7L10 8.6L7.1 5.7C6.91667 5.51667 6.68333 5.425 6.4 5.425C6.11667 5.425 5.88333 5.51667 5.7 5.7C5.51667 5.88333 5.425 6.11667 5.425 6.4C5.425 6.68333 5.51667 6.91667 5.7 7.1L8.6 10L5.7 12.9C5.51667 13.0833 5.425 13.3167 5.425 13.6C5.425 13.8833 5.51667 14.1167 5.7 14.3C5.88333 14.4833 6.11667 14.575 6.4 14.575C6.68333 14.575 6.91667 14.4833 7.1 14.3L10 11.4ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88334 18.6867 3.825 17.9743 2.925 17.075C2.025 16.1757 1.31267 15.1173 0.788001 13.9C0.263335 12.6827 0.000667933 11.3827 1.26582e-06 10C-0.000665401 8.61733 0.262001 7.31733 0.788001 6.1C1.314 4.88267 2.02633 3.82433 2.925 2.925C3.82367 2.02567 4.882 1.31333 6.1 0.788C7.318 0.262667 8.618 0 10 0C11.382 0 12.682 0.262667 13.9 0.788C15.118 1.31333 16.1763 2.02567 17.075 2.925C17.9737 3.82433 18.6863 4.88267 19.213 6.1C19.7397 7.31733 20.002 8.61733 20 10C19.998 11.3827 19.7353 12.6827 19.212 13.9C18.6887 15.1173 17.9763 16.1757 17.075 17.075C16.1737 17.9743 15.1153 18.687 13.9 19.213C12.6847 19.739 11.3847 20.0013 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z"
                                                            fill="black" />
                                                    </svg>
                                                </div>
                                                <div @click.away="open = false"
                                                    class="bg-white p-6 rounded-b-[14px] w-[400px] h-[114px] border shadow-lg">
                                                    <select x-model="tempUser" name="assessor_user_id"
                                                        class="w-full p-2 border mb-4 bg-[#DBDBDB] rounded-[14px]">
                                                        <option value="">เลือกผู้ประเมิน</option>
                                                        @foreach($users->filter(fn($u) => trim($u->faculty) != trim($faculty->name) && $u->assessor_type == 'junior') as $user)
                                                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="courses[{{ $course->id }}][intern]" x-model="selectedUser">
                                                    <div class="flex justify-center gap-2">
                                                        <button type="button" @click="selectedUser = tempUser; open = false"
                                                            class="bg-[#FFCE00] border rounded-[100px] w-[96px] h-[23px] hover:bg-yellow-300">บันทึก</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 border text-center relative">
                                        <span onclick="openDate(this)" class="flex items-center justify-center gap-1 cursor-pointer">

                                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M16 18H2V7H16M13 0V2H5V0H3V2H2C0.89 2 0 2.89 0 4V18C0 18.5304 0.210714 19.0391 0.585786 19.4142C0.960859 19.7893 1.46957 20 2 20H16C16.5304 20 17.0391 19.7893 17.4142 19.4142C17.7893 19.0391 18 18.5304 18 18V4C18 3.46957 17.7893 2.96086 17.4142 2.58579C17.0391 2.21071 16.5304 2 16 2H15V0M14 11H9V16H14V11Z"
                                                    fill="black" />
                                            </svg>

                                            <span class="date-text">เลือกวัน</span>
                                        </span>

                                        <input type="text" name="courses[{{ $course->id }}][assessment_date]" class="date-input absolute opacity-0 pointer-events-none w-0 h-0" />
                                    </td>
                                    <td class="px-4 border">
                                        <div x-data="{ open: false, title: '', selectedUser: '', tempUser: '' }"
                                            class="relative flex items-center justify-center">
                                            <!-- ปุ่มเปิด Modal -->
                                            <button x-show="!selectedUser" type="button" @click="open = true; title = 'เลขา'"
                                                class="w-[155px] h-[37px] bg-[#FFCE00] border border-black rounded-[9px] flex items-center justify-center text-[18px] hover:bg-white">
                                                <svg width="23" height="22" viewBox="0 0 23 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.5 7.75V14.25M14.875 11H8.125M21.625 11C21.625 12.2804 21.3631 13.5482 20.8543 14.7312C20.3455 15.9141 19.5996 16.9889 18.6595 17.8943C17.7193 18.7997 16.6031 19.5178 15.3747 20.0078C14.1462 20.4978 12.8296 20.75 11.5 20.75C10.1704 20.75 8.85375 20.4978 7.62533 20.0078C6.39691 19.5178 5.28074 18.7997 4.34054 17.8943C3.40035 16.9889 2.65455 15.9141 2.14572 14.7312C1.63689 13.5482 1.375 12.2804 1.375 11C1.375 8.41414 2.44174 5.93419 4.34054 4.10571C6.23935 2.27723 8.81468 1.25 11.5 1.25C14.1853 1.25 16.7606 2.27723 18.6595 4.10571C20.5583 5.93419 21.625 8.41414 21.625 11Z"
                                                        stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                เพิ่มข้อมูล
                                            </button>
                                            <div class="flex items-center gap-2">
                                                <span x-show="selectedUser" x-cloak x-text="selectedUser"
                                                    class=" whitespace-nowrap"></span>
                                                <svg x-show="selectedUser" x-cloak
                                                    @click="open = true; title = 'เลขา';tempUser = selectedUser" class="cursor-pointer"
                                                    width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.5 2.76777H2.66667C2.22464 2.76777 1.80072 2.94336 1.48816 3.25592C1.17559 3.56848 1 3.99241 1 4.43443V16.1011C1 16.5431 1.17559 16.9671 1.48816 17.2796C1.80072 17.5922 2.22464 17.7678 2.66667 17.7678H14.3333C14.7754 17.7678 15.1993 17.5922 15.5118 17.2796C15.8244 16.9671 16 16.5431 16 16.1011V10.2678M14.75 1.51777C15.0815 1.18625 15.5312 1 16 1C16.4688 1 16.9185 1.18625 17.25 1.51777C17.5815 1.84929 17.7678 2.29893 17.7678 2.76777C17.7678 3.23661 17.5815 3.68625 17.25 4.01777L9.33333 11.9344L6 12.7678L6.83333 9.43443L14.75 1.51777Z"
                                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <span x-show="selectedUser" x-cloak class="text-[18px]">|</span>
                                                <svg x-show="selectedUser" x-cloak @click="selectedUser = ''; tempUser = ''"
                                                    class=" cursor-pointer" width="16" height="18" viewBox="0 0 16 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M3 18C2.45 18 1.97933 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z"
                                                        fill="black" />
                                                </svg>

                                            </div>
                                            <!-- Modal -->
                                            <div x-show="open" x-cloak
                                                class="fixed inset-0 bg-opacity-50 flex flex-col items-center justify-center z-50">
                                                <div
                                                    class="bg-[#FFCE00] w-[400px] h-[45px] rounded-t-[14px] flex items-center justify-between px-3 border border-b-0">
                                                    <p class="ml-3" x-text="title"></p>
                                                    <svg class="" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M10 11.4L12.9 14.3C13.0833 14.4833 13.3167 14.575 13.6 14.575C13.8833 14.575 14.1167 14.4833 14.3 14.3C14.4833 14.1167 14.575 13.8833 14.575 13.6C14.575 13.3167 14.4833 13.0833 14.3 12.9L11.4 10L14.3 7.1C14.4833 6.91667 14.575 6.68333 14.575 6.4C14.575 6.11667 14.4833 5.88333 14.3 5.7C14.1167 5.51667 13.8833 5.425 13.6 5.425C13.3167 5.425 13.0833 5.51667 12.9 5.7L10 8.6L7.1 5.7C6.91667 5.51667 6.68333 5.425 6.4 5.425C6.11667 5.425 5.88333 5.51667 5.7 5.7C5.51667 5.88333 5.425 6.11667 5.425 6.4C5.425 6.68333 5.51667 6.91667 5.7 7.1L8.6 10L5.7 12.9C5.51667 13.0833 5.425 13.3167 5.425 13.6C5.425 13.8833 5.51667 14.1167 5.7 14.3C5.88333 14.4833 6.11667 14.575 6.4 14.575C6.68333 14.575 6.91667 14.4833 7.1 14.3L10 11.4ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88334 18.6867 3.825 17.9743 2.925 17.075C2.025 16.1757 1.31267 15.1173 0.788001 13.9C0.263335 12.6827 0.000667933 11.3827 1.26582e-06 10C-0.000665401 8.61733 0.262001 7.31733 0.788001 6.1C1.314 4.88267 2.02633 3.82433 2.925 2.925C3.82367 2.02567 4.882 1.31333 6.1 0.788C7.318 0.262667 8.618 0 10 0C11.382 0 12.682 0.262667 13.9 0.788C15.118 1.31333 16.1763 2.02567 17.075 2.925C17.9737 3.82433 18.6863 4.88267 19.213 6.1C19.7397 7.31733 20.002 8.61733 20 10C19.998 11.3827 19.7353 12.6827 19.212 13.9C18.6887 15.1173 17.9763 16.1757 17.075 17.075C16.1737 17.9743 15.1153 18.687 13.9 19.213C12.6847 19.739 11.3847 20.0013 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z"
                                                            fill="black" />
                                                    </svg>
                                                </div>
                                                <div @click.away="open = false"
                                                    class="bg-white p-6 rounded-b-[14px] w-[400px] h-[114px] border shadow-lg">
                                                    <select x-model="tempUser" name="assessor_user_id"
                                                        class="w-full p-2 border mb-4 bg-[#DBDBDB] rounded-[14px]">
                                                        <option value="">เลือกผู้ประเมิน</option>
                                                        @foreach($users->filter(fn($u) => trim($u->faculty) != trim($faculty->name) && $u->assessor_type != 'junior') as $user)
                                                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                                                        @endforeach                                                    </select>
                                                    <input type="hidden" name="courses[{{ $course->id }}][secretary]" x-model="selectedUser">
                                                    <div class="flex justify-center gap-2">
                                                        <button type="button" @click="selectedUser = tempUser; open = false"
                                                            class="bg-[#FFCE00] border rounded-[100px] w-[96px] h-[23px] hover:bg-yellow-300">บันทึก</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        @if(!$hasData)
                            <tr>
                                <td colspan="10" class="text-center border">
                                    ไม่มีข้อมูล
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <div class="w-full flex justify-center mt-4">
        <div id="pagination" class="flex gap-2"></div>
    </div>
    <div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[16px] w-[500px] shadow-lg">
            <!-- header -->
            <div class="bg-[#FFCE00] px-4 py-3 flex justify-between items-center rounded-t-[16px] border-b">
                <p class="text-[20px]">รูปแบบการตรวจประเมิน</p>
                <a href="" onclick="closeModal(); return false;">  
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 11.4L12.9 14.3C13.0833 14.4833 13.3167 14.575 13.6 14.575C13.8833 14.575 14.1167 14.4833 14.3 14.3C14.4833 14.1167 14.575 13.8833 14.575 13.6C14.575 13.3167 14.4833 13.0833 14.3 12.9L11.4 10L14.3 7.1C14.4833 6.91667 14.575 6.68333 14.575 6.4C14.575 6.11667 14.4833 5.88333 14.3 5.7C14.1167 5.51667 13.8833 5.425 13.6 5.425C13.3167 5.425 13.0833 5.51667 12.9 5.7L10 8.6L7.1 5.7C6.91667 5.51667 6.68333 5.425 6.4 5.425C6.11667 5.425 5.88333 5.51667 5.7 5.7C5.51667 5.88333 5.425 6.11667 5.425 6.4C5.425 6.68333 5.51667 6.91667 5.7 7.1L8.6 10L5.7 12.9C5.51667 13.0833 5.425 13.3167 5.425 13.6C5.425 13.8833 5.51667 14.1167 5.7 14.3C5.88333 14.4833 6.11667 14.575 6.4 14.575C6.68333 14.575 6.91667 14.4833 7.1 14.3L10 11.4ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88334 18.6867 3.825 17.9743 2.925 17.075C2.025 16.1757 1.31267 15.1173 0.788001 13.9C0.263335 12.6827 0.000667933 11.3827 1.26582e-06 10C-0.000665401 8.61733 0.262001 7.31733 0.788001 6.1C1.314 4.88267 2.02633 3.82433 2.925 2.925C3.82367 2.02567 4.882 1.31333 6.1 0.788C7.318 0.262667 8.618 0 10 0C11.382 0 12.682 0.262667 13.9 0.788C15.118 1.31333 16.1763 2.02567 17.075 2.925C17.9737 3.82433 18.6863 4.88267 19.213 6.1C19.7397 7.31733 20.002 8.61733 20 10C19.998 11.3827 19.7353 12.6827 19.212 13.9C18.6887 15.1173 17.9763 16.1757 17.075 17.075C16.1737 17.9743 15.1153 18.687 13.9 19.213C12.6847 19.739 11.3847 20.0013 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z" fill="black"/>
                    </svg>
                </a>
            </div>
            <!-- content -->
            <div class="p-4 text-[16px]">
                <p>1. การตรวจประเมินแบบหนึ่งวัน (O)</p>
                <p>2. การตรวจประเมินแบบเต็ม (Full Assessment, 2 วัน : F)</p>
                <p>3. การตรวจประเมินโดยมีกรรมการเป็นผู้ทรงคุณวุฒิภายนอก <br> (ตรวจประเมินฯ 2 วัน : ปธ.คนนอก)</p>
            </div>
        </div>
    </div>
    <script>
        function openModal() {
            const modal = document.getElementById('modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        function openDate(el) {
            const td = el.closest("td");
            const input = td.querySelector(".date-input");

            if (!input._flatpickr) {
                flatpickr(input, {
                    locale: flatpickr.l10ns.th,
                    altInput: true,
                    altFormat: "d/m/Y",
                    dateFormat: "Y-m-d",
                    positionElement: el,
                    position: "below",

                    onChange: function (selectedDates) {
                        if (!selectedDates.length) return;

                        const date = selectedDates[0];
                        const day = String(date.getDate()).padStart(2, "0");
                        const month = String(date.getMonth() + 1).padStart(2, "0");
                        const year = date.getFullYear() + 543;

                        td.querySelector(".date-text").innerText =
                            `${day}/${month}/${year}`;
                    }
                });
            } else {
                // ถ้าคลิกแถวอื่นให้ย้ายตำแหน่ง popup
                input._flatpickr.set("positionElement", el);
            }

            input._flatpickr.open();
        }
        document.addEventListener('DOMContentLoaded', function () {

            const selectElement = document.getElementById('thai-year');
            if (!selectElement) return;

            const currentYear = new Date().getFullYear();
            const currentThaiYear = currentYear + 543;

            const startYear = currentThaiYear - 2; // ย้อนหลัง 2 ปี
            const endYear = currentThaiYear;

            const selectedYear = {{ $selectedThaiYear }};

            for (let i = endYear; i >= startYear; i--) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;

                if (i === selectedYear) {
                    option.selected = true;
                }
                selectElement.appendChild(option);
            }
        });
        $(document).ready(function () {
            const rowsPerPage = 11; // จำนวนแถวต่อหน้า
            const $table = $('#myTable');
            const $rows = $table.find('tbody tr');
            const totalPages = Math.ceil($rows.length / rowsPerPage);

            function showPage(page) {
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                $rows.hide().slice(start, end).show();
                // highlight ปุ่มที่เลือก
                $('#pagination button').removeClass('bg-[#FFCE00] text-white');
                $(`#pagination button[data-page=${page}]`).addClass('bg-[#FFCE00] text-black');
            }

            // สร้างปุ่มหน้า
            for (let i = 1; i <= totalPages; i++) {
                $('#pagination').append(
                    `<button class="px-3 py-1 rounded-full hover:bg-[#FFCE00]" data-page="${i}">${i}</button>`
                );
            }

            // กดปุ่มเปลี่ยนหน้า
            $('#pagination').on('click', 'button', function () {
                const page = $(this).data('page');
                showPage(page);
            });

            // แสดงหน้าแรก
            showPage(1);
        });
    </script>
@endsection