@extends('layouts.header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
    <div class="ml-[90px] mt-[20px] flex items-center">
        <span class="text-[36px]">ตารางข้อมูลการประเมิน</span>
        <form method="GET">
            <div
                class="w-[236px] h-[46px] bg-[#FFCE00] ml-[20px] rounded-[24px] text-[20px] items-center flex justify-center">
                ปีการศึกษา
                <select name="thai_year" id="thai-year" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
            </div>
        </form>
        <div
            class="w-[284px] h-[34.67px] ml-[530px] bg-[#D9D9D9] border border-black rounded-[20px] box-border flex items-center">
            <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search" title="Type in a name"
                class="ml-4 outline-none border-none focus:outline-none focus:border-none ">
            <svg class="ml-[40px]" width="18" height="18" viewBox="0 0 18 18" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M17.1542 16.2675L12.5061 11.9769M12.5061 11.9769C13.7641 10.8157 14.4709 9.24071 14.4709 7.59847C14.4709 5.95622 13.7641 4.38124 12.5061 3.21999C11.2481 2.05875 9.54189 1.40637 7.76279 1.40637C5.98369 1.40637 4.27746 2.05875 3.01944 3.21999C1.76143 4.38124 1.05469 5.95622 1.05469 7.59847C1.05469 9.24071 1.76143 10.8157 3.01944 11.9769C4.27746 13.1382 5.98369 13.7906 7.76279 13.7906C9.54189 13.7906 11.2481 13.1382 12.5061 11.9769Z"
                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>
    <input type="text" readonly value="ผู้ประเมิน : {{ Auth::user()->name }}"
        class="w-[1410px] h-[57px] border rounded-[50px] bg-[#DBDBDB] ml-[90px] mt-[20px] p-[30px] text-[30px]">
    <div class="flex ml-[90px] mt-[20px]">
        <div class="w-[2735px] overflow-auto">
            <table id="myTable" class="w-[2735px]">
                <thead class="bg-[#FFCE00]">
                    <tr>
                        <th class="border text-[20px] px-4 py-2">ลำดับที่</th>
                        <th class="border text-[20px] px-4 py-2">หลักสูตร</th>
                        <th class="border text-[20px] px-4 py-2">คณะ/วิทยาลัย</th>
                        <th class="border text-[20px] px-4 py-2">ระดับการศึกษา</th>
                        <th class="border text-[20px] px-4 py-2">
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
                        <th class="border text-[20px] px-4 py-2">ประธานการประเมิน</th>
                        <th class="border text-[20px] px-4 py-2">กรรมการ</th>
                        <th class="border text-[20px] px-4 py-2">ผู้ฝึกประสบการณ์</th>
                        <th class="border text-[20px] px-4 py-2">วันตรวจประเมิน</th>
                        <th class="border text-[20px] px-4 py-2">เลขา</th>
                        <th class="border text-[20px] px-4 py-2">ประเมิน</th>
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
                            3 => 'External'
                        ];
                    @endphp
                    @forelse ($courseassessor as $index => $row)
                        <tr>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $index + 1 }}</td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $row->course->name ?? '-' }}
                            </td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $row->faculty->name ?? '-' }}
                            </td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">
                                {{ $levelMap[$row->education_level] ?? '-' }}</td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">
                                {{ $typeMap[$row->assessment_type] ?? '-' }}</td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $row->chairperson }}</td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $row->position }}</td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $row->intern }}</td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $row->assessment_date ? \Carbon\Carbon::parse($row->assessment_date)->addYears(543)->format('d/m/Y') : '-' }}</td>
                            <td class="border text-[20px] text-center px-4 py-2 bg-[#DBDBDB]">{{ $row->secretary }}</td>
                            <form action="{{route('tableassessor.save')}}" method="get">
                                <td class="border text-center px-4 py-4 bg-[#DBDBDB]">
                                    <input type="hidden" name="faculty_id" value="{{ $row->faculty->id }}">
                                    <input type="hidden" name="course_id" value="{{ $row->course->id }}">
                                    @php
                                        $userName = Auth::user()->name;
                                        $isType3 = ($row->assessment_type == 3);
                                        $canAssess = ($isType3 && $userName === $row->position) || (!$isType3 && $userName === $row->chairperson);
                                        $disabledText = $isType3 ? 'เฉพาะกรรมการ' : 'เฉพาะประธานการประเมิน';
                                    @endphp
                                    @if($canAssess)
                                        <button type="submit"
                                            class="w-[155px] h-[37px] border bg-[#FFCE00] hover:bg-white rounded-[5px] p-2">
                                            ประเมิน
                                        </button>
                                    @else
                                        <button type="button" disabled
                                            class="w-auto h-[37px] border bg-[#FFCE00] cursor-not-allowed rounded-[5px] p-2">
                                            {{ $disabledText }}
                                        </button>
                                    @endif
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-[20px] py-4 border bg-[#DBDBDB]">ไม่มีข้อมูล</td>
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
                <p>3. การตรวจประเมินโดยมีกรรมการเป็นผู้ทรงคุณวุฒิภายนอก <br> (ตรวจประเมินฯ 2 วัน : ประธานคนนอก)</p>
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
                function myFunction() {
            var input = document.getElementById("myInput");
            var filter = input.value.toUpperCase();
            var table = document.getElementById("myTable");
            var tr = table.getElementsByTagName("tr");

            var found = false;

            // ลบ row "ไม่พบข้อมูล" เดิมก่อน
            var oldMsg = document.getElementById("no-data-row");
            if (oldMsg) oldMsg.remove();

            for (var i = 1; i < tr.length; i++) {
                var tds = tr[i].getElementsByTagName("td");
                var show = false;

                for (var j = 0; j < tds.length; j++) {
                    var txtValue = tds[j].textContent || tds[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        show = true;
                        break;
                    }
                }

                tr[i].style.display = show ? "" : "none";
                if (show) found = true;
            }

            // ถ้าไม่พบข้อมูล
            if (!found) {
                var tbody = table.querySelector("tbody");
                var row = document.createElement("tr");
                row.id = "no-data-row";

                var cell = document.createElement("td");
                cell.colSpan = 11;
                cell.className = "py-4 text-center border bg-[#DBDBDB] text-[20px]";
                cell.innerText = "ไม่พบข้อมูล";

                row.appendChild(cell);
                tbody.appendChild(row);
            }
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
    </script>
@endsection