@extends('layouts.header')

@section('content')
    <p class="text-[36px] ml-[85px] mt-[26px]">รายชื่อผู้ประเมินย้อนหลัง</p>
    <div class="flex">
        <p class="text-[18px] ml-[85px] mt-[8px]">ปีที่เริ่ม--ปีที่สิ้นสุด</p>
        <div
            class="absolute w-[284px] h-[34.67px] right-[58px] top-[205px] bg-[#D9D9D9] border border-black rounded-[20px] box-border flex items-center">
            <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search" title="Type in a name"
                class="ml-4 outline-none border-none focus:outline-none focus:border-none ">
            <svg class="absolute left-[247px]" width="18" height="18" viewBox="0 0 18 18" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M17.1542 16.2675L12.5061 11.9769M12.5061 11.9769C13.7641 10.8157 14.4709 9.24071 14.4709 7.59847C14.4709 5.95622 13.7641 4.38124 12.5061 3.21999C11.2481 2.05875 9.54189 1.40637 7.76279 1.40637C5.98369 1.40637 4.27746 2.05875 3.01944 3.21999C1.76143 4.38124 1.05469 5.95622 1.05469 7.59847C1.05469 9.24071 1.76143 10.8157 3.01944 11.9769C4.27746 13.1382 5.98369 13.7906 7.76279 13.7906C9.54189 13.7906 11.2481 13.1382 12.5061 11.9769Z"
                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>
    <div class="flex items-center justify-center ml-[85px] mt-[20px] w-[1377px]">
        <table class="w-[1377px] h-[100px] ">
            <thead class="bg-[#FFCE00]">
                <tr>
                    <th rowspan="2" class="text-[24px] px-4 border">ที่</th>
                    <th rowspan="2" class="text-[24px] px-4 border">รายชื่อ</th>
                    <th colspan="6" class="text-[24px] px-4 border">ปีการศึกษา 2566</th>
                </tr>
                <tr>
                    <th class="text-[16px] px-4 border">ปธ.คนนอก</th>
                    <th class="text-[16px] px-4 border">LeadAssessor<br>Full</th>
                    <th class="text-[16px] px-4 border">Assessor<br>Full</th>
                    <th class="text-[16px] px-4 border">LeadAssessor<br>OneD</th>
                    <th class="text-[16px] px-4 border">Assessor<br>OneD_66</th>
                    <th class="text-[16px] px-4 border">รวมหลักสูตร</th>
                </tr>
            </thead>
        </table>
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
    </script>
@endsection