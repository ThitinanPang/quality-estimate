@extends('layouts.header')
<script src="//unpkg.com/alpinejs" defer></script>
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
        <span class="text-[16px]">วิทยาเขต :</span>
        <select name="" id="" class="w-[196px] h-[35px] bg-[#DBDBDB] rounded-[8px] text-center">
            <option value="บางแสน" class="bg-white">บางแสน</option>
            <option value="จันทบุรี" class="bg-white">จันทบุรี</option>
        </select>
        <span class="text-[16px]">กลุ่มวิชาสาขา :</span>
        <select name="" id="" class="w-[196px] h-[35px] bg-[#DBDBDB] rounded-[8px] text-center">
            <option value=""></option>
            <option value=""></option>
        </select>
    </div>
    <div class="flex pl-[85px] pt-[10px] w-full">
        <div class="w-[1500px] h-[380px] overflow-auto">
            <table class="w-[1500px] h-[390px]">
                <thead class="bg-[#FFCE00]">
                    <tr>
                        <th class="p-2 border text-[18px] whitespace-nowrap">ลำดับที่</th>
                        <th class="px-4 border text-[18px]">หลักสูตร</th>
                        <th class="px-4 border text-[18px] whitespace-nowrap">ระดับการศึกษา</th>
                        <th class="px-4 border text-[18px] whitespace-nowrap">คณะ/วิทยาลัย</th>
                        <th class="px-4 border text-[18px] whitespace-nowrap">รูปแบบการประเมิน</th>
                        <th class="px-4 border text-[18px] whitespace-nowrap">ประธานการประเมิน</th>
                        <th class="px-4 border text-[18px]">กรรมการ</th>
                        <th class="px-4 border text-[18px] whitespace-nowrap">ผู้ฝึกประสบการณ์</th>
                        <th class="px-4 border text-[18px] whitespace-nowrap">วันตรวจประเมิน</th>
                        <th class="px-4 border text-[18px]">เลขา</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($faculties as $faculty)
                        @foreach($faculty->courses as $course)
                            <tr>
                                <td class="px-4 border text-center">{{ $no++ }}</td>
                                <td class="px-4 border ">{{ $course->name }}</td>
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
                                <td class="px-4 border whitespace-nowrap text-center">{{ $faculty->name }}</td>
                                <td class="px-4 py-4 border text-center">
                                    <select name="" id="" class="border">
                                        <option value="1" class="text-center">O</option>
                                        <option value="2" class="text-center">F</option>
                                        <option value="3" class="text-center">ปธ.คนนอก</option>
                                    </select>
                                </td>
                                <td class="px-3 border text-center">
                                    <div x-data="{ open: false, title: '', selectedUser: '', tempUser: '' }"
                                        class="relative flex items-center justify-center">
                                        <!-- ปุ่มเปิด Modal -->
                                        <button x-show="!selectedUser" @click="open = true; title = 'ประธานการประเมิน'"
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
                                            <span x-show="selectedUser" x-text="selectedUser" class=" whitespace-nowrap"></span>
                                            <svg x-show="selectedUser"
                                                @click="open = true; title = 'ประธานการประเมิน';tempUser = selectedUser"
                                                class="cursor-pointer" width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M8.5 2.76777H2.66667C2.22464 2.76777 1.80072 2.94336 1.48816 3.25592C1.17559 3.56848 1 3.99241 1 4.43443V16.1011C1 16.5431 1.17559 16.9671 1.48816 17.2796C1.80072 17.5922 2.22464 17.7678 2.66667 17.7678H14.3333C14.7754 17.7678 15.1993 17.5922 15.5118 17.2796C15.8244 16.9671 16 16.5431 16 16.1011V10.2678M14.75 1.51777C15.0815 1.18625 15.5312 1 16 1C16.4688 1 16.9185 1.18625 17.25 1.51777C17.5815 1.84929 17.7678 2.29893 17.7678 2.76777C17.7678 3.23661 17.5815 3.68625 17.25 4.01777L9.33333 11.9344L6 12.7678L6.83333 9.43443L14.75 1.51777Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span x-show="selectedUser" class="text-[18px]">|</span>
                                            <svg x-show="selectedUser" @click="selectedUser = ''; tempUser = ''"
                                                class=" cursor-pointer" width="16" height="18" viewBox="0 0 16 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M3 18C2.45 18 1.97933 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z"
                                                    fill="black" />
                                            </svg>

                                        </div>
                                        <!-- Modal -->
                                        <div x-show="open"
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
                                                    @foreach($users->filter(fn($u) => trim($u->faculty) == trim($faculty->name)) as $user)
                                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="flex justify-center gap-2">
                                                    <button @click="selectedUser = tempUser; open = false"
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
                                        <button x-show="!selectedUser" @click="open = true; title = 'กรรมการ'"
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
                                            <span x-show="selectedUser" x-text="selectedUser" class=" whitespace-nowrap"></span>
                                            <svg x-show="selectedUser"
                                                @click="open = true; title = 'กรรมการ';tempUser = selectedUser"
                                                class="cursor-pointer" width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M8.5 2.76777H2.66667C2.22464 2.76777 1.80072 2.94336 1.48816 3.25592C1.17559 3.56848 1 3.99241 1 4.43443V16.1011C1 16.5431 1.17559 16.9671 1.48816 17.2796C1.80072 17.5922 2.22464 17.7678 2.66667 17.7678H14.3333C14.7754 17.7678 15.1993 17.5922 15.5118 17.2796C15.8244 16.9671 16 16.5431 16 16.1011V10.2678M14.75 1.51777C15.0815 1.18625 15.5312 1 16 1C16.4688 1 16.9185 1.18625 17.25 1.51777C17.5815 1.84929 17.7678 2.29893 17.7678 2.76777C17.7678 3.23661 17.5815 3.68625 17.25 4.01777L9.33333 11.9344L6 12.7678L6.83333 9.43443L14.75 1.51777Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span x-show="selectedUser" class="text-[18px]">|</span>
                                            <svg x-show="selectedUser" @click="selectedUser = ''; tempUser = ''"
                                                class=" cursor-pointer" width="16" height="18" viewBox="0 0 16 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M3 18C2.45 18 1.97933 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z"
                                                    fill="black" />
                                            </svg>

                                        </div>
                                        <!-- Modal -->
                                        <div x-show="open"
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
                                                    @foreach($users->filter(fn($u) => trim($u->faculty) == trim($faculty->name)) as $user)
                                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="flex justify-center gap-2">
                                                    <button @click="selectedUser = tempUser; open = false"
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
                                        <button x-show="!selectedUser" @click="open = true; title = 'ผู้ฝึกประสบการณ์'"
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
                                            <span x-show="selectedUser" x-text="selectedUser" class=" whitespace-nowrap"></span>
                                            <svg x-show="selectedUser"
                                                @click="open = true; title = 'ผู้ฝึกประสบการณ์';tempUser = selectedUser"
                                                class="cursor-pointer" width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M8.5 2.76777H2.66667C2.22464 2.76777 1.80072 2.94336 1.48816 3.25592C1.17559 3.56848 1 3.99241 1 4.43443V16.1011C1 16.5431 1.17559 16.9671 1.48816 17.2796C1.80072 17.5922 2.22464 17.7678 2.66667 17.7678H14.3333C14.7754 17.7678 15.1993 17.5922 15.5118 17.2796C15.8244 16.9671 16 16.5431 16 16.1011V10.2678M14.75 1.51777C15.0815 1.18625 15.5312 1 16 1C16.4688 1 16.9185 1.18625 17.25 1.51777C17.5815 1.84929 17.7678 2.29893 17.7678 2.76777C17.7678 3.23661 17.5815 3.68625 17.25 4.01777L9.33333 11.9344L6 12.7678L6.83333 9.43443L14.75 1.51777Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span x-show="selectedUser" class="text-[18px]">|</span>
                                            <svg x-show="selectedUser" @click="selectedUser = ''; tempUser = ''"
                                                class=" cursor-pointer" width="16" height="18" viewBox="0 0 16 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M3 18C2.45 18 1.97933 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z"
                                                    fill="black" />
                                            </svg>

                                        </div>
                                        <!-- Modal -->
                                        <div x-show="open"
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
                                                    @foreach($users->filter(fn($u) => trim($u->faculty) == trim($faculty->name)) as $user)
                                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="flex justify-center gap-2">
                                                    <button @click="selectedUser = tempUser; open = false"
                                                        class="bg-[#FFCE00] border rounded-[100px] w-[96px] h-[23px] hover:bg-yellow-300">บันทึก</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 border text-center cursor-pointer">
                                    <svg class=" inline-flex" width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 18H2V7H16M13 0V2H5V0H3V2H2C0.89 2 0 2.89 0 4V18C0 18.5304 0.210714 19.0391 0.585786 19.4142C0.960859 19.7893 1.46957 20 2 20H16C16.5304 20 17.0391 19.7893 17.4142 19.4142C17.7893 19.0391 18 18.5304 18 18V4C18 3.46957 17.7893 2.96086 17.4142 2.58579C17.0391 2.21071 16.5304 2 16 2H15V0M14 11H9V16H14V11Z" fill="black"/>
                                    </svg>
                                    เลือกวัน
                                </td>
                                <td class="px-4 border">
                                    <div x-data="{ open: false, title: '', selectedUser: '', tempUser: '' }"
                                        class="relative flex items-center justify-center">
                                        <!-- ปุ่มเปิด Modal -->
                                        <button x-show="!selectedUser" @click="open = true; title = 'ผู้ฝึกประสบการณ์'"
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
                                            <span x-show="selectedUser" x-text="selectedUser" class=" whitespace-nowrap"></span>
                                            <svg x-show="selectedUser"
                                                @click="open = true; title = 'ผู้ฝึกประสบการณ์';tempUser = selectedUser"
                                                class="cursor-pointer" width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M8.5 2.76777H2.66667C2.22464 2.76777 1.80072 2.94336 1.48816 3.25592C1.17559 3.56848 1 3.99241 1 4.43443V16.1011C1 16.5431 1.17559 16.9671 1.48816 17.2796C1.80072 17.5922 2.22464 17.7678 2.66667 17.7678H14.3333C14.7754 17.7678 15.1993 17.5922 15.5118 17.2796C15.8244 16.9671 16 16.5431 16 16.1011V10.2678M14.75 1.51777C15.0815 1.18625 15.5312 1 16 1C16.4688 1 16.9185 1.18625 17.25 1.51777C17.5815 1.84929 17.7678 2.29893 17.7678 2.76777C17.7678 3.23661 17.5815 3.68625 17.25 4.01777L9.33333 11.9344L6 12.7678L6.83333 9.43443L14.75 1.51777Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span x-show="selectedUser" class="text-[18px]">|</span>
                                            <svg x-show="selectedUser" @click="selectedUser = ''; tempUser = ''"
                                                class=" cursor-pointer" width="16" height="18" viewBox="0 0 16 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M3 18C2.45 18 1.97933 17.8043 1.588 17.413C1.19667 17.0217 1.00067 16.5507 1 16V3H0V1H5V0H11V1H16V3H15V16C15 16.55 14.8043 17.021 14.413 17.413C14.0217 17.805 13.5507 18.0007 13 18H3ZM13 3H3V16H13V3ZM5 14H7V5H5V14ZM9 14H11V5H9V14Z"
                                                    fill="black" />
                                            </svg>

                                        </div>
                                        <!-- Modal -->
                                        <div x-show="open"
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
                                                    @foreach($users->filter(fn($u) => trim($u->faculty) == trim($faculty->name)) as $user)
                                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="flex justify-center gap-2">
                                                    <button @click="selectedUser = tempUser; open = false"
                                                        class="bg-[#FFCE00] border rounded-[100px] w-[96px] h-[23px] hover:bg-yellow-300">บันทึก</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- <select name="assessor_user_id" class="w-auto border ">
        <option value="" class="text-center">-- เลือกผู้ใช้ --</option>
        @foreach($users->where('faculty', $faculty->name) as $user)
        <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select> --}}
    <script>

    </script>
@endsection