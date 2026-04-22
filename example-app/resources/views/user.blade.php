@extends('layouts.header')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
    <p class="absolute w-[257px] h-[53px] left-[85px] top-[200px] font-normal text-[40px] leading-[54px]">
        ข้อมูลพื้นฐานผู้ใช้</p>
    {{-- ตัวอย่าง excel --}}
    <a href="{{ route('users.template') }}" class="absolute text-[20px] left-[280px] top-[210px] w-[174px] h-[30px] gap-2 border rounded-[10px] flex items-center justify-center bg-[#FFCE00]">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M7.75852 11.6378L2.90944 6.7887L4.26718 5.38247L6.7887 7.90399V0H8.72833V7.90399L11.2499 5.38247L12.6076 6.7887L7.75852 11.6378ZM1.93963 15.517C1.40623 15.517 0.949772 15.3273 0.570251 14.9478C0.19073 14.5682 0.000646543 14.1115 0 13.5774V10.668H1.93963V13.5774H13.5774V10.668H15.517V13.5774C15.517 14.1108 15.3273 14.5676 14.9478 14.9478C14.5682 15.3279 14.1115 15.5177 13.5774 15.517H1.93963Z"
                fill="black" />
        </svg>
        เทมเพลตเพิ่มข้อมูล
    </a>
    <form method="GET">
        <div
            class="w-[236px] h-[46px] bg-[#FFCE00] rounded-[24px] absolute right-[85px] top-[210px] text-[24px] items-center flex justify-center">
            ปีการศึกษา
            <select name="thai_year" id="thai-year" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
        </div>
    </form>
    <span
        class="w-[79px] h-[32px] text-[20px] border bg-[#D9D9D9] rounded-l-[20px] absolute top-[158.33px] right-[458px] px-3 py-1">Sort
        By</span>
    <select name="" id="sortName"
        class="w-[79px] h-[32px] border rounded-r-[20px] text-[20px] absolute top-[158.33px] right-[380px] text-center">
        <option value="asc">ก-ฮ</option>
        <option value="desc">ฮ-ก</option>
    </select>
    <div
        class="absolute w-[284px] h-[34.67px] right-[85px] top-[158.33px] bg-[#D9D9D9] border border-black rounded-[20px] box-border flex items-center">
        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search" title="Type in a name"
            class="ml-4 text-[20px] outline-none border-none focus:outline-none focus:border-none ">
        <svg class="absolute left-[247px]" width="18" height="18" viewBox="0 0 18 18" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M17.1542 16.2675L12.5061 11.9769M12.5061 11.9769C13.7641 10.8157 14.4709 9.24071 14.4709 7.59847C14.4709 5.95622 13.7641 4.38124 12.5061 3.21999C11.2481 2.05875 9.54189 1.40637 7.76279 1.40637C5.98369 1.40637 4.27746 2.05875 3.01944 3.21999C1.76143 4.38124 1.05469 5.95622 1.05469 7.59847C1.05469 9.24071 1.76143 10.8157 3.01944 11.9769C4.27746 13.1382 5.98369 13.7906 7.76279 13.7906C9.54189 13.7906 11.2481 13.1382 12.5061 11.9769Z"
                stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <div class="flex">
        <button onclick="window.location.href='{{route('user')}}'"
            class="{{ request()->routeIs('user') ? 'bg-[#BEBEBE]' : 'hover:bg-[#D9D9D9]' }} w-[135px] h-[38px] rounded-[5px] hover:bg-[#BEBEBE] text-[24px] ml-[85px] mt-[150px]">ผู้ใช้ทั่วไป</button>
        <button onclick="window.location.href='{{route('faculty')}}'"
            class="w-[233px] h-[38px] rounded-[5px] bg-[#D9D9D9] hover:bg-[#BEBEBE] text-[24px] ml-[10px] mt-[150px]">ผู้ดูแลระดับคณะ</button>
        <button onclick="window.location.href='{{route('university')}}'"
            class="w-[305px] h-[38px] rounded-[5px] bg-[#D9D9D9] hover:bg-[#BEBEBE] text-[24px] ml-[10px] mt-[150px]">ผู้ดูแลระดับมหาวิทยาลัย</button>
    </div>
    <form id="importForm" action="{{ route('import.users') }}" method="post" enctype="multipart/form-data">
        @csrf
        <!-- ซ่อน input file -->
        <input type="file" id="excelInput" name="excel_file" accept=".xlsx,.xls" style="display: none;">

        <!-- ปุ่มกด -->
        <button type="button" onclick="document.getElementById('excelInput').click();"
            class="absolute w-[155px] h-[37px] right-[85px] top-[275px] bg-[#FFCE00] border border-black rounded-[9px] 
            box-border flex items-center justify-center text-[22px] hover:bg-white">
            <svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.5 7.75V14.25M14.875 11H8.125M21.625 11C21.625 12.2804 21.3631 13.5482 20.8543 14.7312C20.3455 15.9141 19.5996 16.9889 18.6595 17.8943C17.7193 18.7997 16.6031 19.5178 15.3747 20.0078C14.1462 20.4978 12.8296 20.75 11.5 20.75C10.1704 20.75 8.85375 20.4978 7.62533 20.0078C6.39691 19.5178 5.28074 18.7997 4.34054 17.8943C3.40035 16.9889 2.65455 15.9141 2.14572 14.7312C1.63689 13.5482 1.375 12.2804 1.375 11C1.375 8.41414 2.44174 5.93419 4.34054 4.10571C6.23935 2.27723 8.81468 1.25 11.5 1.25C14.1853 1.25 16.7606 2.27723 18.6595 4.10571C20.5583 5.93419 21.625 8.41414 21.625 11Z"
                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            เพิ่มข้อมูล
        </button>
    </form>
    @php
        // 1. เชื่อมต่อฐานข้อมูล
        $conn = new mysqli("localhost", "root", "", "localhost");

        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die("เชื่อมต่อไม่สำเร็จ: " . $conn->connect_error);
        }

        $selectedThaiYear = $_GET['thai_year'] ?? (date('Y') + 543);
        $selectedADYear = $selectedThaiYear - 543;
        // 2. ดึงข้อมูลจาก DB  
        $sql = "SELECT id, prefix, name, faculty, status, email, phone_number FROM users 
                                        WHERE YEAR(created_at) = $selectedADYear";

        $result = $conn->query($sql);

        $counter = 0;
    @endphp
    <div class="overflow-x-auto flex items-center justify-center mt-[20px]">
        <table id="myTable" class="w-[1350px] border border-black box-border" style="width:1350px;">
            <thead class="bg-[#FFCE00]">
                <tr>
                    <th class="px-4 py-2 border-b text-center align-middle text-[20px]">เลือกรายการ</th>
                    <th class="px-4 py-2 border-b text-center align-middle text-[20px]">คำนำหน้า</th>
                    <th class="px-4 py-2 border-b text-center align-middle text-[20px]">ชื่อ - นามสกุล</th>
                    <th class="px-4 py-2 border-b text-center align-middle text-[20px]">
                        <span class='inline-flex items-center justify-center'>
                            คณะ
                        </span>
                    </th>
                    <th class="px-4 py-2 border-b text-center align-middle text-[20px]">สถานะ</th>
                    <th class="px-4 py-2 border-b text-center align-middle text-[20px]">อีเมล</th>
                    <th class="px-4 py-2 border-b text-center align-middle text-[20px]">เบอร์โทรศัพท์</th>
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
                                <a href="{{ route('edituser', $row['id']) }}" class="text-[#7B7B7B] text-[20px]">แก้ไข</a>
                            </td>
                            <td class="px-4 py-2 border-b text-center align-middle text-[20px]">{{ $row['prefix'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle text-[20px]">{{ $row['name'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle text-[20px]">{{ $row['faculty'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle capitalize text-[20px]">{{ $row['status'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle text-[20px]">{{ $row['email'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle text-[20px]">{{ $row['phone_number'] }}</td>
                        </tr>
                        @php $counter++; @endphp
                    @endwhile
                @else
                    <tr>
                        <td class="px-4 py-2 border-b text-center text-[20px]" colspan="7">ไม่มีข้อมูล</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div id="pagination" class="mt-5 justify-center items-center flex space-x-2"></div>
    @php
        $conn->close();
    @endphp
    <script>
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
                cell.colSpan = 7;
                cell.className = "px-4 py-2 border-b text-center text-[20px]";
                cell.innerText = "ไม่พบข้อมูล";

                row.appendChild(cell);
                tbody.appendChild(row);
            }
        }
        const excelInput = document.getElementById('excelInput');
        const form = document.getElementById('importForm');

        excelInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                form.submit();
                // reset ค่าให้เลือกไฟล์เดิมได้อีก
                this.value = "";
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            // 1. แจ้งเตือนกรณีมีข้อมูลซ้ำ (แสดงเป็นรายการ)
            @if(session('warning_list'))
                Swal.fire({
                    icon: 'warning',
                    title: 'พบข้อมูลอยู่ในระบบแล้ว',
                    html: `
                        <div style="font-family: 'Kanit', sans-serif;">
                            <p style="margin-bottom: 10px;">รายชื่อต่อไปนี้มีข้อมูลอยู่ในระบบหลักแล้ว จึงไม่ถูกเพิ่ม:</p>
                            {!! session('warning_list') !!}
                        </div>
                    `,
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#FFCE00'
                });
            @endif

            // 2. แจ้งเตือนกรณีสำเร็จ
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: "{{ session('success') }}",
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
        });
        document.addEventListener('DOMContentLoaded', function () {
            const selectElement = document.getElementById('thai-year');
            const currentYear = new Date().getFullYear();
            const currentThaiYear = currentYear + 543;

            const startYear = currentThaiYear - 2;
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
        // SORT ชื่อ
        document.getElementById("sortName").addEventListener("change", function () {

            let order = this.value;
            let table = document.getElementById("myTable");
            let tbody = table.querySelector("tbody");
            let rows = Array.from(tbody.querySelectorAll("tr"));

            rows.sort(function (a, b) {

                let nameA = a.children[2].innerText.trim();
                let nameB = b.children[2].innerText.trim();

                if (order === "asc") {
                    return nameA.localeCompare(nameB, 'th');
                } else {
                    return nameB.localeCompare(nameA, 'th');
                }

            });

            rows.forEach(row => tbody.appendChild(row));

        });

        $(document).ready(function () {
            const rowsPerPage = 6; // จำนวนแถวต่อหน้า
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