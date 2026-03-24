@extends('layouts.header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
    <div class="ml-[90px] mt-[20px] flex items-center">
        <span class="text-[36px]">ผู้ประเมินหลักสูตร</span>
        <form method="GET">
            <div
                class="w-[236px] h-[46px] bg-[#FFCE00] ml-[900px] rounded-[24px] text-[20px] items-center flex justify-center">
                ปีการศึกษา
                <select name="thai_year" id="thai-year" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
            </div>
        </form>
    </div>
    <div class="flex ml-[90px] mt-[20px]">
        <div class="w-[2328px] overflow-auto">
            <table id="myTable" class="w-[2328px]">
                <thead class="bg-[#FFCE00]">
                    <tr>
                        <th class="border text-[20px] px-2 py-2">ลำดับที่</th>
                        <th class="border text-[20px] px-2 py-2">รหัสวิชา</th>
                        <th class="border text-[20px] px-2 py-2">คณะ/วิทยาลัย</th>
                        <th class="border text-[20px] px-2 py-2">กลุ่มวิชา</th>
                        <th class="border text-[20px] px-2 py-2">
                            <div class="flex items-center justify-center gap-2">
                                ระดับการศึกษา
                                <a href="" onclick="sortTable(4); return false;">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5.8125 1.99542H13.125M5.8125 1.99542C5.8125 2.32573 5.69397 2.64251 5.483 2.87607C5.27202 3.10963 4.98587 3.24084 4.6875 3.24084C4.38913 3.24084 4.10298 3.10963 3.892 2.87607C3.68103 2.64251 3.5625 2.32573 3.5625 1.99542M5.8125 1.99542C5.8125 1.66512 5.69397 1.34834 5.483 1.11478C5.27202 0.881214 4.98587 0.75 4.6875 0.75C4.38913 0.75 4.10298 0.881214 3.892 1.11478C3.68103 1.34834 3.5625 1.66512 3.5625 1.99542M3.5625 1.99542H0.75M5.8125 11.9588H13.125M5.8125 11.9588C5.8125 12.2891 5.69397 12.6059 5.483 12.8394C5.27202 13.073 4.98587 13.2042 4.6875 13.2042C4.38913 13.2042 4.10298 13.073 3.892 12.8394C3.68103 12.6059 3.5625 12.2891 3.5625 11.9588M5.8125 11.9588C5.8125 11.6285 5.69397 11.3117 5.483 11.0782C5.27202 10.8446 4.98587 10.7134 4.6875 10.7134C4.38913 10.7134 4.10298 10.8446 3.892 11.0782C3.68103 11.3117 3.5625 11.6285 3.5625 11.9588M3.5625 11.9588H0.75M10.3125 6.97711H13.125M10.3125 6.97711C10.3125 7.30742 10.194 7.6242 9.98299 7.85776C9.77202 8.09132 9.48587 8.22253 9.1875 8.22253C8.88913 8.22253 8.60298 8.09132 8.392 7.85776C8.18103 7.6242 8.0625 7.30742 8.0625 6.97711M10.3125 6.97711C10.3125 6.64681 10.194 6.33003 9.98299 6.09647C9.77202 5.8629 9.48587 5.73169 9.1875 5.73169C8.88913 5.73169 8.60298 5.8629 8.392 6.09647C8.18103 6.33003 8.0625 6.64681 8.0625 6.97711M8.0625 6.97711H0.75"
                                            stroke="black" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                        </th>
                        <th class="border text-[20px] px-2 py-2">หลักสูตร</th>
                        <th class="border text-[20px] px-2 py-2">
                            <div class="flex items-center justify-center gap-2">
                                รูปแบบการประเมิน
                                <a href="" onclick="openModal(); return false;">
                                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.5 0C13.2848 0 15.9555 1.10625 17.9246 3.07538C19.8938 5.04451 21 7.71523 21 10.5C21 13.2848 19.8938 15.9555 17.9246 17.9246C15.9555 19.8938 13.2848 21 10.5 21C7.71523 21 5.04451 19.8938 3.07538 17.9246C1.10625 15.9555 0 13.2848 0 10.5C0 7.71523 1.10625 5.04451 3.07538 3.07538C5.04451 1.10625 7.71523 0 10.5 0ZM10.5 19.5C12.8869 19.5 15.1761 18.5518 16.864 16.864C18.5518 15.1761 19.5 12.8869 19.5 10.5C19.5 8.11305 18.5518 5.82387 16.864 4.13604C15.1761 2.44821 12.8869 1.5 10.5 1.5C8.11305 1.5 5.82387 2.44821 4.13604 4.13604C2.44821 5.82387 1.5 8.11305 1.5 10.5C1.5 12.8869 2.44821 15.1761 4.13604 16.864C5.82387 18.5518 8.11305 19.5 10.5 19.5ZM11.625 15.375C11.625 15.6734 11.5065 15.9595 11.2955 16.1705C11.0845 16.3815 10.7984 16.5 10.5 16.5C10.2016 16.5 9.91548 16.3815 9.7045 16.1705C9.49353 15.9595 9.375 15.6734 9.375 15.375C9.375 15.0766 9.49353 14.7905 9.7045 14.5795C9.91548 14.3685 10.2016 14.25 10.5 14.25C10.7984 14.25 11.0845 14.3685 11.2955 14.5795C11.5065 14.7905 11.625 15.0766 11.625 15.375ZM10.5 4.5C10.6989 4.5 10.8897 4.57902 11.0303 4.71967C11.171 4.86032 11.25 5.05109 11.25 5.25V12C11.25 12.1989 11.171 12.3897 11.0303 12.5303C10.8897 12.671 10.6989 12.75 10.5 12.75C10.3011 12.75 10.1103 12.671 9.96967 12.5303C9.82902 12.3897 9.75 12.1989 9.75 12V5.25C9.75 5.05109 9.82902 4.86032 9.96967 4.71967C10.1103 4.57902 10.3011 4.5 10.5 4.5Z"
                                            fill="black" />
                                    </svg>
                                </a>
                            </div>
                        </th>
                        <th class="border text-[20px] px-2 py-2">ประธานการประเมิน</th>
                        <th class="border text-[20px] px-2 py-2">กรรมการ</th>
                        <th class="border text-[20px] px-2 py-2">ผู้ฝึกประสบการณ์</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $levelMap = [
                            1 => 'ปริญญาตรี',
                            2 => 'ปริญญาโท',
                            3 => 'ปริญญาเอก'
                        ];

                        $typeMap = [
                            1 => 'O',
                            2 => 'F',
                            3 => 'ปธ.คนนอก'
                        ];
                    @endphp
                    @forelse ($courseassessor as $index => $row)
                        <tr>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $index + 1 }}</td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">
                                {{ $row->course->code ?? '-' }}
                            </td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">
                                {{ $row->course->faculty->name ?? '-' }}
                            </td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">
                                {{ $row->subject_group ?? '-' }}
                            </td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">
                                {{ $levelMap[$row->education_level] ?? '-' }}
                            </td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">
                                {{ $row->course->name ?? '-' }}
                            </td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">
                                {{ $typeMap[$row->assessment_type] ?? '-' }}
                            </td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $row->chairperson ?? '-' }}
                            </td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $row->position ?? '-' }}</td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $row->intern ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-[20px] py-4 border bg-[#DBDBDB]">ไม่มีข้อมูล</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-[16px] w-[500px] shadow-lg">
            <!-- header -->
            <div class="bg-[#FFCE00] px-4 py-3 flex justify-between items-center rounded-t-[16px] border-b">
                <p class="text-[20px]">รูปแบบการตรวจประเมิน</p>
                <a href="" onclick="closeModal(); return false;">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 11.4L12.9 14.3C13.0833 14.4833 13.3167 14.575 13.6 14.575C13.8833 14.575 14.1167 14.4833 14.3 14.3C14.4833 14.1167 14.575 13.8833 14.575 13.6C14.575 13.3167 14.4833 13.0833 14.3 12.9L11.4 10L14.3 7.1C14.4833 6.91667 14.575 6.68333 14.575 6.4C14.575 6.11667 14.4833 5.88333 14.3 5.7C14.1167 5.51667 13.8833 5.425 13.6 5.425C13.3167 5.425 13.0833 5.51667 12.9 5.7L10 8.6L7.1 5.7C6.91667 5.51667 6.68333 5.425 6.4 5.425C6.11667 5.425 5.88333 5.51667 5.7 5.7C5.51667 5.88333 5.425 6.11667 5.425 6.4C5.425 6.68333 5.51667 6.91667 5.7 7.1L8.6 10L5.7 12.9C5.51667 13.0833 5.425 13.3167 5.425 13.6C5.425 13.8833 5.51667 14.1167 5.7 14.3C5.88333 14.4833 6.11667 14.575 6.4 14.575C6.68333 14.575 6.91667 14.4833 7.1 14.3L10 11.4ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88334 18.6867 3.825 17.9743 2.925 17.075C2.025 16.1757 1.31267 15.1173 0.788001 13.9C0.263335 12.6827 0.000667933 11.3827 1.26582e-06 10C-0.000665401 8.61733 0.262001 7.31733 0.788001 6.1C1.314 4.88267 2.02633 3.82433 2.925 2.925C3.82367 2.02567 4.882 1.31333 6.1 0.788C7.318 0.262667 8.618 0 10 0C11.382 0 12.682 0.262667 13.9 0.788C15.118 1.31333 16.1763 2.02567 17.075 2.925C17.9737 3.82433 18.6863 4.88267 19.213 6.1C19.7397 7.31733 20.002 8.61733 20 10C19.998 11.3827 19.7353 12.6827 19.212 13.9C18.6887 15.1173 17.9763 16.1757 17.075 17.075C16.1737 17.9743 15.1153 18.687 13.9 19.213C12.6847 19.739 11.3847 20.0013 10 20ZM10 18C12.2333 18 14.125 17.225 15.675 15.675C17.225 14.125 18 12.2333 18 10C18 7.76667 17.225 5.875 15.675 4.325C14.125 2.775 12.2333 2 10 2C7.76667 2 5.875 2.775 4.325 4.325C2.775 5.875 2 7.76667 2 10C2 12.2333 2.775 14.125 4.325 15.675C5.875 17.225 7.76667 18 10 18Z"
                            fill="black" />
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
    <div id="pagination" class="mt-5 justify-center items-center flex space-x-2"></div>

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
        function sortTable(columnIndex) {
            const table = document.getElementById("myTable");
            const rows = Array.from(table.rows).slice(1);

            let asc = table.getAttribute("data-sort") !== "asc";
            table.setAttribute("data-sort", asc ? "asc" : "desc");

            rows.sort((a, b) => {
                let x = a.cells[columnIndex].innerText.trim();
                let y = b.cells[columnIndex].innerText.trim();

                return asc
                    ? x.localeCompare(y, 'th')
                    : y.localeCompare(x, 'th');
            });

            rows.forEach(row => table.tBodies[0].appendChild(row));
        }
        const select = document.getElementById('thai-year');
        const currentThaiYear = new Date().getFullYear() + 543;
        const selectedThaiYear = {{ $selectedThaiYear }};

        for (let i = currentThaiYear; i >= currentThaiYear - 2; i--) {
            const option = document.createElement('option');
            option.value = i;
            option.text = i;

            if (i == selectedThaiYear) {
                option.selected = true;
            }

            select.appendChild(option);
        }
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