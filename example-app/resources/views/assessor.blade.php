@extends('layouts.header')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
    <p class="absolute w-[257px] h-[53px] left-[85px] top-[200px] font-normal text-[36px] leading-[54px]">
        ข้อมูลผู้ประเมิน</p>
    {{-- ตัวอย่าง excel --}}
    <a href="{{ route('assessor.template') }}"
        class="absolute left-[330px] top-[215px]  w-[174px] h-[30px] gap-2 border rounded-[10px] flex items-center justify-center bg-[#FFCE00]">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M7.75852 11.6378L2.90944 6.7887L4.26718 5.38247L6.7887 7.90399V0H8.72833V7.90399L11.2499 5.38247L12.6076 6.7887L7.75852 11.6378ZM1.93963 15.517C1.40623 15.517 0.949772 15.3273 0.570251 14.9478C0.19073 14.5682 0.000646543 14.1115 0 13.5774V10.668H1.93963V13.5774H13.5774V10.668H15.517V13.5774C15.517 14.1108 15.3273 14.5676 14.9478 14.9478C14.5682 15.3279 14.1115 15.5177 13.5774 15.517H1.93963Z"
                fill="black" />
        </svg>
        เทมเพลตเพิ่มข้อมูล
    </a>
    <form method="GET">
        <div
            class="w-[236px] h-[46px] bg-[#FFCE00] rounded-[24px] absolute right-[85px] top-[210px] text-[20px] items-center flex justify-center">
            ปีการศึกษา
            <select name="thai_year" id="thai-year" class="h-[46px] ml-2" onchange="this.form.submit()"></select>
        </div>
    </form>
    <span
        class="w-[79px] h-[32px] border bg-[#D9D9D9] rounded-l-[20px] absolute top-[158.33px] right-[458px] px-3 py-1">Sort
        By</span>
    <select name="" id="sortName"
        class="w-[79px] h-[32px] border rounded-r-[20px] absolute top-[158.33px] right-[380px] text-center">
        <option value="asc">ก-ฮ</option>
        <option value="desc">ฮ-ก</option>
    </select>
    <div
        class="absolute w-[284px] h-[34.67px] right-[85px] top-[158.33px] bg-[#D9D9D9] border border-black rounded-[20px] box-border flex items-center">
        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search" title="Type in a name"
            class="ml-4 outline-none border-none focus:outline-none focus:border-none ">
        <svg class="absolute left-[247px]" width="18" height="18" viewBox="0 0 18 18" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M17.1542 16.2675L12.5061 11.9769M12.5061 11.9769C13.7641 10.8157 14.4709 9.24071 14.4709 7.59847C14.4709 5.95622 13.7641 4.38124 12.5061 3.21999C11.2481 2.05875 9.54189 1.40637 7.76279 1.40637C5.98369 1.40637 4.27746 2.05875 3.01944 3.21999C1.76143 4.38124 1.05469 5.95622 1.05469 7.59847C1.05469 9.24071 1.76143 10.8157 3.01944 11.9769C4.27746 13.1382 5.98369 13.7906 7.76279 13.7906C9.54189 13.7906 11.2481 13.1382 12.5061 11.9769Z"
                stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    <form id="importForm" action="{{ route('import.assessor') }}" method="post" enctype="multipart/form-data">
        @csrf
        <!-- ซ่อน input file -->
        <input type="file" id="excelInput" name="excel_file" accept=".xlsx,.xls" style="display: none;">

        <!-- ปุ่มกด -->
        <button type="button" onclick="document.getElementById('excelInput').click();"
            class="absolute w-[155px] h-[37px] left-[85px] top-[267px] bg-[#FFCE00] border border-black rounded-[9px] box-border flex items-center justify-center text-[18px] hover:bg-white">
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
        $sql = "SELECT id, code_assessor,prefix, name, faculty, status, email, phone_number,assessor_type,training_type 
                                        FROM users_assessor WHERE YEAR(created_at) = $selectedADYear ORDER BY LEFT(code_assessor, 2) ASC, code_assessor ASC";

        $result = $conn->query($sql);

        $counter = 0;
    @endphp
    <div class="overflow-x-auto flex items-center justify-center mt-[200px]">
        <table id="myTable" class="w-[1350px] border border-black box-border" style="width:1350px;">
            <thead class="bg-[#FFCE00]">
                <tr>
                    <th class="px-4 py-2 border-b text-center align-middle">เลือกรายการ</th>
                    <th class="px-4 py-2 border-b text-center align-middle">
                        <div class="flex items-center justify-center gap-2">
                            Code Assessor
                            <a href="javascript:void(0)" onclick="sortTableByCode()" id="codeSortBtn" data-order="asc">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5.8125 1.99542H13.125M5.8125 1.99542C5.8125 2.32573 5.69397 2.64251 5.483 2.87607C5.27202 3.10963 4.98587 3.24084 4.6875 3.24084C4.38913 3.24084 4.10298 3.10963 3.892 2.87607C3.68103 2.64251 3.5625 2.32573 3.5625 1.99542M5.8125 1.99542C5.8125 1.66512 5.69397 1.34834 5.483 1.11478C5.27202 0.881214 4.98587 0.75 4.6875 0.75C4.38913 0.75 4.10298 0.881214 3.892 1.11478C3.68103 1.34834 3.5625 1.66512 3.5625 1.99542M3.5625 1.99542H0.75M5.8125 11.9588H13.125M5.8125 11.9588C5.8125 12.2891 5.69397 12.6059 5.483 12.8394C5.27202 13.073 4.98587 13.2042 4.6875 13.2042C4.38913 13.2042 4.10298 13.073 3.892 12.8394C3.68103 12.6059 3.5625 12.2891 3.5625 11.9588M5.8125 11.9588C5.8125 11.6285 5.69397 11.3117 5.483 11.0782C5.27202 10.8446 4.98587 10.7134 4.6875 10.7134C4.38913 10.7134 4.10298 10.8446 3.892 11.0782C3.68103 11.3117 3.5625 11.6285 3.5625 11.9588M3.5625 11.9588H0.75M10.3125 6.97711H13.125M10.3125 6.97711C10.3125 7.30742 10.194 7.6242 9.98299 7.85776C9.77202 8.09132 9.48587 8.22253 9.1875 8.22253C8.88913 8.22253 8.60298 8.09132 8.392 7.85776C8.18103 7.6242 8.0625 7.30742 8.0625 6.97711M10.3125 6.97711C10.3125 6.64681 10.194 6.33003 9.98299 6.09647C9.77202 5.8629 9.48587 5.73169 9.1875 5.73169C8.88913 5.73169 8.60298 5.8629 8.392 6.09647C8.18103 6.33003 8.0625 6.64681 8.0625 6.97711M8.0625 6.97711H0.75"
                                        stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                    </th>
                    <th class="px-4 py-2 border-b text-center align-middle">คำนำหน้า</th>
                    <th class="px-4 py-2 border-b text-center align-middle">ชื่อ - นามสกุล</th>
                    <th class="px-4 py-2 border-b text-center align-middle">
                        <span class='inline-flex items-center justify-center'>
                            คณะ
                        </span>
                    </th>
                    <th class="px-4 py-2 border-b text-center align-middle">สถานะ</th>
                    <th class="px-4 py-2 border-b text-center align-middle">Assessor Type</th>
                    <th class="px-4 py-2 border-b text-center align-middle">Training Type</th>
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
                                <a href="{{ route('editassessor', $row['id']) }}" class="text-[#7B7B7B]">แก้ไข</a>
                            </td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['code_assessor'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['prefix'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle whitespace-nowrap">{{ $row['name'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['faculty'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle capitalize">{{ $row['status'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle capitalize">{{ $row['assessor_type'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['training_type'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['email'] }}</td>
                            <td class="px-4 py-2 border-b text-center align-middle">{{ $row['phone_number'] }}</td>
                        </tr>
                        @php $counter++; @endphp
                    @endwhile
                @else
                    <tr>
                        <td class="px-4 py-2 border-b text-center" colspan="10">ไม่มีข้อมูล</td>
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
                cell.colSpan = 10;
                cell.className = "px-4 py-2 border-b text-center";
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
            alert('เพิ่มผู้ใช้งานสำเร็จ');
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

            // กรองเอาแถว "ไม่มีข้อมูล" ออกก่อนทำการ sort (ถ้ามี)
            let filteredRows = rows.filter(row => row.id !== "no-data-row" && row.cells.length > 1);

            filteredRows.sort(function (a, b) {
                // ใช้ index [3] เพื่อดึงข้อมูลจากคอลัมน์ "ชื่อ - นามสกุล"
                let nameA = a.children[3].innerText.trim();
                let nameB = b.children[3].innerText.trim();

                if (order === "asc") {
                    return nameA.localeCompare(nameB, 'th');
                } else {
                    return nameB.localeCompare(nameA, 'th');
                }
            });

            // นำแถวที่เรียงแล้วใส่กลับเข้าไปใน tbody
            filteredRows.forEach(row => tbody.appendChild(row));

            // ถ้าคุณใช้ Pagination ของ jQuery ด้านล่างด้วย 
            // อย่าลืมเรียกฟังก์ชันแสดงหน้าแรกใหม่เพื่อให้แถวที่ sort แล้วอัปเดตตามหน้า
            if (typeof showPage === "function") {
                showPage(1);
            }

        });

        $(document).ready(function () {
            const rowsPerPage = 6;
            const $table = $('#myTable');
            const $pagination = $('#pagination');

            // ฟังก์ชันหลักสำหรับจัดการ Pagination
            window.updatePagination = function () {
                const filter = $('#myInput').val().toUpperCase();

                // ลบแถว "ไม่พบข้อมูล" ออกก่อน
                $('#no-data-row').remove();

                // 1. ดึงแถวปัจจุบันจากตารางจริงเสมอ (เพื่อให้รองรับการ Sort ที่เพิ่งทำไป)
                const $currentRows = $table.find('tbody tr:not(#no-data-row)');

                // 2. กรองแถวตามคำค้นหา (Search)
                const $filteredRows = $currentRows.filter(function () {
                    const text = $(this).text().toUpperCase();
                    return text.indexOf(filter) > -1;
                });

                // 3. จัดการกรณีไม่พบข้อมูล
                if ($filteredRows.length === 0) {
                    $table.find('tbody').append('<tr id="no-data-row"><td colspan="10" class="px-4 py-2 border-b text-center">ไม่พบข้อมูล</td></tr>');
                    $pagination.empty();
                    $currentRows.hide();
                    return;
                }

                // 4. คำนวณหน้า
                const totalPages = Math.ceil($filteredRows.length / rowsPerPage);
                $pagination.empty();

                for (let i = 1; i <= totalPages; i++) {
                    $pagination.append(
                        `<button class="px-3 py-1 rounded-full hover:bg-[#FFCE00]" data-page="${i}">${i}</button>`
                    );
                }

                // 5. ฟังก์ชันแสดงหน้า
                function showPage(page) {
                    const start = (page - 1) * rowsPerPage;
                    const end = start + rowsPerPage;

                    // ซ่อนทุกแถวก่อน (เฉพาะแถวข้อมูล)
                    $currentRows.hide();

                    // แสดงเฉพาะแถวที่ผ่านการกรองและอยู่ในช่วง Index ของหน้านั้นๆ
                    $filteredRows.slice(start, end).show();

                    // จัดการสีของแถวให้สลับกันสวยงาม (Zebra Stripes) หลังการ Filter/Sort
                    $filteredRows.filter(':visible').each(function (index) {
                        $(this).removeClass('bg-white bg-[#DBDBDB]')
                            .addClass(index % 2 === 0 ? 'bg-white' : 'bg-[#DBDBDB]');
                    });

                    // Highlight ปุ่มหน้าปัจจุบัน
                    $pagination.find('button').removeClass('bg-[#FFCE00] text-black');
                    $pagination.find(`button[data-page=${page}]`).addClass('bg-[#FFCE00] text-black');
                }

                // ผูกเหตุการณ์คลิกปุ่ม
                $pagination.off('click').on('click', 'button', function () {
                    showPage($(this).data('page'));
                });

                // แสดงหน้าแรก
                showPage(1);
            };

            // เชื่อมโยงกับฟังก์ชัน Search เดิม
            window.myFunction = function () {
                window.updatePagination();
            };

            // เรียกใช้งานครั้งแรก
            window.updatePagination();
        });

        // ส่วนของฟังก์ชัน Sort (ต้องเรียก updatePagination ปิดท้ายเสมอ)
        function sortTableByCode() {
            let table = document.getElementById("myTable");
            let tbody = table.querySelector("tbody");
            let rows = Array.from(tbody.querySelectorAll("tr:not(#no-data-row)"));
            let btn = document.getElementById("codeSortBtn");
            let order = btn.getAttribute("data-order");

            rows.sort(function (a, b) {
                let codeA = a.children[1].innerText.trim();
                let codeB = b.children[1].innerText.trim();
                return order === "asc"
                    ? codeA.localeCompare(codeB, undefined, { numeric: true })
                    : codeB.localeCompare(codeA, undefined, { numeric: true });
            });

            rows.forEach(row => tbody.appendChild(row));
            btn.setAttribute("data-order", order === "asc" ? "desc" : "asc");

            // สำคัญ: เมื่อ Sort เสร็จ ต้องสั่งให้แบ่งหน้าใหม่
            if (typeof window.updatePagination === "function") {
                window.updatePagination();
            }
        }
    </script>
@endsection