@extends('layouts.header')
<script src="//unpkg.com/alpinejs" defer></script>

@section('content')
    <p class="absolute w-[257px] h-[53px] left-[85px] top-[150px] font-normal text-[36px] leading-[54px]">
        ข้อมูลพื้นฐานผู้ใช้</p>
    <div
        class="absolute w-[284px] h-[34.67px] left-[1114px] top-[158.33px] 
                                                            bg-[#D9D9D9] border border-black rounded-[20px] box-border flex items-center">
        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search" title="Type in a name"
            class="ml-4 outline-none border-none focus:outline-none focus:border-none ">
        <svg class="absolute left-[247px]" width="18" height="18" viewBox="0 0 18 18" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M17.1542 16.2675L12.5061 11.9769M12.5061 11.9769C13.7641 10.8157 14.4709 9.24071 14.4709 7.59847C14.4709 5.95622 13.7641 4.38124 12.5061 3.21999C11.2481 2.05875 9.54189 1.40637 7.76279 1.40637C5.98369 1.40637 4.27746 2.05875 3.01944 3.21999C1.76143 4.38124 1.05469 5.95622 1.05469 7.59847C1.05469 9.24071 1.76143 10.8157 3.01944 11.9769C4.27746 13.1382 5.98369 13.7906 7.76279 13.7906C9.54189 13.7906 11.2481 13.1382 12.5061 11.9769Z"
                stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <button type="button"
        class="absolute w-[155px] h-[37px] left-[85px] top-[210px] 
                                    bg-[#FFCE00] border border-black rounded-[9px] box-border flex items-center justify-center text-[18px] hover:bg-white">
        <svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M11.5 7.75V14.25M14.875 11H8.125M21.625 11C21.625 12.2804 21.3631 13.5482 20.8543 14.7312C20.3455 15.9141 19.5996 16.9889 18.6595 17.8943C17.7193 18.7997 16.6031 19.5178 15.3747 20.0078C14.1462 20.4978 12.8296 20.75 11.5 20.75C10.1704 20.75 8.85375 20.4978 7.62533 20.0078C6.39691 19.5178 5.28074 18.7997 4.34054 17.8943C3.40035 16.9889 2.65455 15.9141 2.14572 14.7312C1.63689 13.5482 1.375 12.2804 1.375 11C1.375 8.41414 2.44174 5.93419 4.34054 4.10571C6.23935 2.27723 8.81468 1.25 11.5 1.25C14.1853 1.25 16.7606 2.27723 18.6595 4.10571C20.5583 5.93419 21.625 8.41414 21.625 11Z"
                stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        เพิ่มข้อมูล
    </button>
    @php
        // 1. เชื่อมต่อฐานข้อมูล
        $conn = new mysqli("localhost", "root", "", "localhost");

        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die("เชื่อมต่อไม่สำเร็จ: " . $conn->connect_error);
        }

        // 2. ดึงข้อมูลจาก DB
        $sql = "SELECT id, prefix, name, faculty, status, email, phone_number FROM users";
        $result = $conn->query($sql);

        $counter = 0;
    @endphp

    <div class="overflow-x-auto flex items-center justify-center mt-[130px]">
        <table id="myTable" class="w-[1350px] border border-black box-border">
            <thead class="bg-[#FFCE00]">
                <tr>
                    <th class="px-4 py-2 border-b text-center align-middle">เลือกรายการ</th>
                    <th class="px-4 py-2 border-b text-center align-middle">คำนำหน้า</th>
                    <th class="px-4 py-2 border-b text-center align-middle">ชื่อ - นามสกุล</th>
                    <th class="px-4 py-2 border-b text-center align-middle">
                        <span class='inline-flex items-center justify-center'>
                            คณะ
                            <svg class='ml-1' width='15' height='15' viewBox='0 0 15 15' fill='none'
                                xmlns='http://www.w3.org/2000/svg'>
                                <path
                                    d='M6.0625 2.34967H13.375M6.0625 2.34967C6.0625 2.67998 5.94397 2.99676 5.733 3.23032C5.52202 3.46388 5.23587 3.59509 4.9375 3.59509C4.63913 3.59509 4.35298 3.46388 4.142 3.23032C3.93103 2.99676 3.8125 2.67998 3.8125 2.34967M6.0625 2.34967C6.0625 2.01936 5.94397 1.70259 5.733 1.46902C5.52202 1.23546 5.23587 1.10425 4.9375 1.10425C4.63913 1.10425 4.35298 1.23546 4.142 1.46902C3.93103 1.70259 3.8125 2.01936 3.8125 2.34967M3.8125 2.34967H1M6.0625 12.313H13.375M6.0625 12.313C6.0625 12.6434 5.94397 12.9601 5.733 13.1937C5.52202 13.4273 5.23587 13.5585 4.9375 13.5585C4.63913 13.5585 4.35298 13.4273 4.142 13.1937C3.93103 12.9601 3.8125 12.6434 3.8125 12.313M6.0625 12.313C6.0625 11.9827 5.94397 11.666 5.733 11.4324C5.52202 11.1988 5.23587 11.0676 4.9375 11.0676C4.63913 11.0676 4.35298 11.1988 4.142 11.4324C3.93103 11.666 3.8125 11.9827 3.8125 12.313M3.8125 12.313H1M10.5625 7.33136H13.375M10.5625 7.33136C10.5625 7.66167 10.444 7.97844 10.233 8.21201C10.022 8.44557 9.73587 8.57678 9.4375 8.57678C9.13913 8.57678 8.85298 8.44557 8.642 8.21201C8.43103 7.97844 8.3125 7.66167 8.3125 7.33136M10.5625 7.33136C10.5625 7.00105 10.444 6.68428 10.233 6.45071C10.022 6.21715 9.73587 6.08594 9.4375 6.08594C9.13913 6.08594 8.85298 6.21715 8.642 6.45071C8.43103 6.68428 8.3125 7.00105 8.3125 7.33136M8.3125 7.33136H1'
                                    stroke='black' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' />
                            </svg>
                        </span>
                    </th>
                    <th class="px-4 py-2 border-b text-center align-middle">Status</th>
                    <th class="px-4 py-2 border-b text-center align-middle">อีเมล</th>
                    <th class="px-4 py-2 border-b text-center align-middle">เบอร์โทรศัพท์</th>
                </tr>
            </thead>
            <tbody>
                @if ($result->num_rows > 0)
                    @while ($row = $result->fetch_assoc())
                        @php
                            $rowColor = $counter % 2 === 0 ? 'bg-white' : 'bg-[#DBDBDB]';
                        @endphp
                        <tr class="{{ $rowColor }}">
                            <td class="px-4 py-2 border-b text-center align-middle">
                                <svg class='inline-flex mb-1 ' width='18' height='19' viewBox='0 0 18 19' fill='none'
                                    xmlns='http://www.w3.org/2000/svg'>
                                    <path
                                        d='M13.0517 3.53243L14.4575 2.05305C14.7506 1.74484 15.148 1.57169 15.5625 1.57169C15.977 1.57169 16.3744 1.74484 16.6675 2.05305C16.9606 2.36126 17.1252 2.77929 17.1252 3.21517C17.1252 3.65105 16.9606 4.06907 16.6675 4.37729L7.81833 13.6839C7.37777 14.1469 6.83447 14.4873 6.2375 14.6742L4 15.3753L4.66667 13.0222C4.8444 12.3943 5.16803 11.823 5.60833 11.3596L13.0517 3.53243ZM13.0517 3.53243L15.25 5.84439M14 11.8697V16.0326C14 16.5556 13.8025 17.0572 13.4508 17.427C13.0992 17.7968 12.6223 18.0046 12.125 18.0046H3.375C2.87772 18.0046 2.40081 17.7968 2.04917 17.427C1.69754 17.0572 1.5 16.5556 1.5 16.0326V6.83035C1.5 6.30737 1.69754 5.8058 2.04917 5.436C2.40081 5.06619 2.87772 4.85843 3.375 4.85843H7.33333'
                                        stroke='black' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' />
                                </svg>
                                <a href="#" class="text-[#7B7B7B] open-modal" data-prefix="{{ $row['prefix'] }}"
                                    data-name="{{ $row['name'] }}" data-faculty="{{ $row['faculty'] }}"
                                    data-status="{{ $row['status'] }}" data-email="{{ $row['email'] }}"
                                    data-phone="{{ $row['phone_number'] }}">
                                    แก้ไข
                                </a>
                            </td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['prefix'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['name'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['faculty'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['status'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['email'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['phone_number'] }}</td>
                        </tr>
                        @php $counter++; @endphp
                    @endwhile
                @else
                    <tr>
                        <td class="px-4 py-2 border-b text-center" colspan="7">ไม่มีข้อมูล</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    @php
        $conn->close();
    @endphp
    {{-- Modal --}}
    <div id="editModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white w-[500px] rounded-2xl shadow-lg p-6 relative">
            <h2 class="text-xl font-bold mb-4">แก้ไขข้อมูล</h2>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" id="edit_id">

                <div class="mb-3">
                    <label class="block text-sm font-medium">คำนำหน้า</label>
                    <input type="text" name="prefix" id="edit_prefix" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">ชื่อ - นามสกุล</label>
                    <input type="text" name="name" id="edit_name" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">คณะ</label>
                    <input type="text" name="faculty" id="edit_faculty" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">สถานะ</label>
                    <input type="text" name="status" id="edit_status" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">อีเมล</label>
                    <input type="email" name="email" id="edit_email" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">เบอร์โทรศัพท์</label>
                    <input type="text" name="phone_number" id="edit_phone_number" class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">ยกเลิก</button>
                    <button type="submit" class="px-4 py-2 rounded bg-[#FFCE00] hover:bg-white">บันทึก</button>
                </div>
            </form>

            <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-black">&times;</button>
        </div>
    </div>

    <script>
        function myFunction() {
            var input = document.getElementById("myInput");
            var filter = input.value.toUpperCase();
            var table = document.getElementById("myTable");
            var tr = table.getElementsByTagName("tr");
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
            }
        }
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("editModal");
            const closeBtns = document.querySelectorAll("#closeModal");

            document.querySelectorAll(".open-modal").forEach(btn => {
                btn.addEventListener("click", function (e) {
                    e.preventDefault();

                    // ดึงค่า data-* จากลิงก์ที่กด
                    document.getElementById("edit_prefix").value = this.dataset.prefix;
                    document.getElementById("edit_name").value = this.dataset.name;
                    document.getElementById("edit_faculty").value = this.dataset.faculty;
                    document.getElementById("edit_status").value = this.dataset.status;
                    document.getElementById("edit_email").value = this.dataset.email;
                    document.getElementById("edit_phone_number").value = this.dataset.phone;

                    modal.classList.remove("hidden");
                });
            });

            // ปิด modal ได้จากปุ่มทุกอันที่มี id="closeModal"
            closeBtns.forEach(btn => {
                btn.addEventListener("click", () => {
                    modal.classList.add("hidden");
                });
            });
        });    </script>
@endsection