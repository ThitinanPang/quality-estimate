@extends('layouts.header')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
    <div class="flex ml-[90px] mt-[70px]">
        <p class="text-[36px]">ข้อมูลหลักสูตร</p>
        {{-- ตัวอย่าง excel --}}
        <a href="{{ route('assessor.template') }}"
            class="w-[174px] h-[30px] mt-[15px] ml-[20px] gap-2 border rounded-[10px] flex items-center justify-center bg-[#FFCE00]">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.75852 11.6378L2.90944 6.7887L4.26718 5.38247L6.7887 7.90399V0H8.72833V7.90399L11.2499 5.38247L12.6076 6.7887L7.75852 11.6378ZM1.93963 15.517C1.40623 15.517 0.949772 15.3273 0.570251 14.9478C0.19073 14.5682 0.000646543 14.1115 0 13.5774V10.668H1.93963V13.5774H13.5774V10.668H15.517V13.5774C15.517 14.1108 15.3273 14.5676 14.9478 14.9478C14.5682 15.3279 14.1115 15.5177 13.5774 15.517H1.93963Z"
                    fill="black" />
            </svg>
            เทมเพลตเพิ่มข้อมูล
        </a>

    </div>
    <div class="flex ml-[90px] gap-2 mt-[10px]">
        <a href="{{ route('listfaculty') }}"
            class="w-[135px] h-[38px] text-[20px] bg-[#D9D9D9] rounded-[5px] flex items-center justify-center">คณะ</a>
        <a href="{{ route('listcourse') }}"
            class="w-[135px] h-[38px] text-[20px] bg-[#D9D9D9] rounded-[5px] flex items-center justify-center">หลักสูตร</a>
    </div>
    <form id="importForm" action="{{ route('import.faculty') }}" method="post" enctype="multipart/form-data">
        @csrf
        <!-- ซ่อน input file -->
        <input type="file" id="excelInput" name="excel_file" accept=".xlsx,.xls" style="display: none;">

        <!-- ปุ่มกด -->
        <button type="button" onclick="document.getElementById('excelInput').click();"
            class=" w-[155px] h-[37px] ml-[90px] mt-[10px] bg-[#FFCE00] border border-black rounded-[9px] box-border flex items-center justify-center text-[18px] hover:bg-white">
            <svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.5 7.75V14.25M14.875 11H8.125M21.625 11C21.625 12.2804 21.3631 13.5482 20.8543 14.7312C20.3455 15.9141 19.5996 16.9889 18.6595 17.8943C17.7193 18.7997 16.6031 19.5178 15.3747 20.0078C14.1462 20.4978 12.8296 20.75 11.5 20.75C10.1704 20.75 8.85375 20.4978 7.62533 20.0078C6.39691 19.5178 5.28074 18.7997 4.34054 17.8943C3.40035 16.9889 2.65455 15.9141 2.14572 14.7312C1.63689 13.5482 1.375 12.2804 1.375 11C1.375 8.41414 2.44174 5.93419 4.34054 4.10571C6.23935 2.27723 8.81468 1.25 11.5 1.25C14.1853 1.25 16.7606 2.27723 18.6595 4.10571C20.5583 5.93419 21.625 8.41414 21.625 11Z"
                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            เพิ่มหลักสูตร
        </button>
    </form>
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
    <form action="" method="get">
        <div class="absolute left-[1160px] top-[270px]">
            <span>วิทยาเขต :</span>
            <select name="campus" onchange="this.form.submit()"
                class="w-[196px] h-[35px] bg-[#DBDBDB] rounded-[10px] text-center">
                <option value="" selected disabled>เลือกข้อมูล</option>
                <option value="บางแสน" {{ request('campus') == 'บางแสน' ? 'selected' : '' }}>บางแสน</option>
                <option value="จันทบุรี" {{ request('campus') == 'จันทบุรี' ? 'selected' : '' }}>จันทบุรี</option>
            </select>
        </div>
    </form>
    <div class="absolute left-[1130px] top-[320px]">
        <span>กลุ่มสาขาวิชา :</span>
        <select name="subject_group" class="w-[196px] h-[35px] bg-[#DBDBDB] rounded-[10px] text-center">
            <option value="">เลือกข้อมูล</option>
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
    </div>
    <div class="flex items-center justify-center mt-[20px]">
        <div class="w-[1350px] rounded-[10px] overflow-hidden">
            <table id="myTable" class="w-full">
                <thead class="bg-[#FFCE00] h-[66px]">
                    <tr>
                        <th class="text-[24px] text-center w-[120px]">ลำดับที่</th>
                        <th class="text-[24px] text-center w-[120px]">สถานะ</th>
                        <th class="text-[24px] text-left pl-4">หลักสูตรภายใน มหาวิทยาลัยบูรพา</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @forelse ($faculties as $index => $faculty)
                        @foreach ($faculty->courses as $course)
                            <tr>
                                <td class="px-2 py-2 text-center text-[24px] w-[120px]">{{$i++}}</td>
                                <td class="px-2 py-2 text-center text-[24px] w-[120px]">
                                    <select name="" data-id="{{ $course->id }}"
                                        class="w-[105px] h-[40px] rounded-[14px] border text-center status-select">
                                        <option value="active" {{ $course->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $course->status == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </td>
                                <td class="px-2 py-2 text-[24px] text-left pl-4 flex flex-col">
                                    <div class="w-full flex items-center">
                                        {{ $course->name }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td class="px-4 py-2 text-center" colspan="3">
                                ไม่มีข้อมูล
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div id="pagination" class="mt-5 justify-center items-center flex space-x-2"></div>
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
                cell.className = "px-4 py-2 text-center";
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

            rows.sort(function (a, b) {

                let nameA = a.children[1].innerText.trim();
                let nameB = b.children[1].innerText.trim();

                if (order === "asc") {
                    return nameA.localeCompare(nameB, 'th');
                } else {
                    return nameB.localeCompare(nameA, 'th');
                }

            });

            rows.forEach(row => tbody.appendChild(row));

        });

        $(document).ready(function () {
            const rowsPerPage = 10; // จำนวนแถวต่อหน้า
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
        document.querySelectorAll('.status-select').forEach(select => {

            function updateColor() {
                if (select.value === 'active') {
                    select.classList.remove('bg-[#D5D5D5]');
                    select.classList.add('bg-[#FFCE00]');
                } else {
                    select.classList.remove('bg-[#FFCE00]');
                    select.classList.add('bg-[#D5D5D5]');
                }
            }
            // ตอนโหลดครั้งแรก
            updateColor();

            // ตอนเปลี่ยนค่า
            select.addEventListener('change', updateColor);
        });
        document.querySelectorAll('.status-select').forEach(select => {

            select.addEventListener('change', function () {

                let courseId = this.dataset.id;
                let status = this.value;

                fetch("{{ route('course.updateStatus') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: courseId,
                        status: status
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log("saved");
                    })
                    .catch(err => {
                        console.error(err);
                    });

            });
        });
    </script>
@endsection