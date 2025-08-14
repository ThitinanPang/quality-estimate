@extends('layouts.header')

@section('content')
    <p  class="absolute w-[257px] h-[53px] left-[85px] top-[150px] font-normal text-[36px] leading-[54px]">ข้อมูลพื้นฐานผู้ใช้</p>
    <div class="absolute w-[284px] h-[34.67px] left-[1114px] top-[158.33px] 
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
    
    <?php
    // 1. เชื่อมต่อฐานข้อมูล
    $conn = new mysqli("localhost", "root", "", "localhost");

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("เชื่อมต่อไม่สำเร็จ: " . $conn->connect_error);
    }

    // 2. ดึงข้อมูลจาก DB
    $sql = "SELECT id, prefix, name, faculty, status, email, phone_number FROM users";
    $result = $conn->query($sql);

    // 3. แสดงในตาราง HTML
    echo "<div class='overflow-x-auto flex items-center justify-center mt-[104px]'>
            <table id='myTable' class='w-[1350px] border border-black box-border'>
                <thead class='bg-[#FFCE00]'>
                    <tr>
                        <th class='px-4 py-2 border-b text-center align-middle'>คำนำหน้า</th>
                        <th class='px-4 py-2 border-b text-center align-middle'>ชื่อ - นามสกุล</th>
                        <th class='px-4 py-2 border-b text-center align-middle'>
                            <span class='inline-flex items-center justify-center'>
                                คณะ
                                <svg class='ml-1' width='15' height='15' viewBox='0 0 15 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                <path d='M6.0625 2.34967H13.375M6.0625 2.34967C6.0625 2.67998 5.94397 2.99676 5.733 3.23032C5.52202 3.46388 5.23587 3.59509 4.9375 3.59509C4.63913 3.59509 4.35298 3.46388 4.142 3.23032C3.93103 2.99676 3.8125 2.67998 3.8125 2.34967M6.0625 2.34967C6.0625 2.01936 5.94397 1.70259 5.733 1.46902C5.52202 1.23546 5.23587 1.10425 4.9375 1.10425C4.63913 1.10425 4.35298 1.23546 4.142 1.46902C3.93103 1.70259 3.8125 2.01936 3.8125 2.34967M3.8125 2.34967H1M6.0625 12.313H13.375M6.0625 12.313C6.0625 12.6434 5.94397 12.9601 5.733 13.1937C5.52202 13.4273 5.23587 13.5585 4.9375 13.5585C4.63913 13.5585 4.35298 13.4273 4.142 13.1937C3.93103 12.9601 3.8125 12.6434 3.8125 12.313M6.0625 12.313C6.0625 11.9827 5.94397 11.666 5.733 11.4324C5.52202 11.1988 5.23587 11.0676 4.9375 11.0676C4.63913 11.0676 4.35298 11.1988 4.142 11.4324C3.93103 11.666 3.8125 11.9827 3.8125 12.313M3.8125 12.313H1M10.5625 7.33136H13.375M10.5625 7.33136C10.5625 7.66167 10.444 7.97844 10.233 8.21201C10.022 8.44557 9.73587 8.57678 9.4375 8.57678C9.13913 8.57678 8.85298 8.44557 8.642 8.21201C8.43103 7.97844 8.3125 7.66167 8.3125 7.33136M10.5625 7.33136C10.5625 7.00105 10.444 6.68428 10.233 6.45071C10.022 6.21715 9.73587 6.08594 9.4375 6.08594C9.13913 6.08594 8.85298 6.21715 8.642 6.45071C8.43103 6.68428 8.3125 7.00105 8.3125 7.33136M8.3125 7.33136H1' stroke='black' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/>
                                </svg>
                            </span>
                        </th>
                        <th class='px-4 py-2 border-b text-center align-middle'>
                                <span class='inline-flex items-center justify-center'>
                                Status
                                <svg class='ml-1' width='15' height='15' viewBox='0 0 15 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                <path d='M6.0625 2.34967H13.375M6.0625 2.34967C6.0625 2.67998 5.94397 2.99676 5.733 3.23032C5.52202 3.46388 5.23587 3.59509 4.9375 3.59509C4.63913 3.59509 4.35298 3.46388 4.142 3.23032C3.93103 2.99676 3.8125 2.67998 3.8125 2.34967M6.0625 2.34967C6.0625 2.01936 5.94397 1.70259 5.733 1.46902C5.52202 1.23546 5.23587 1.10425 4.9375 1.10425C4.63913 1.10425 4.35298 1.23546 4.142 1.46902C3.93103 1.70259 3.8125 2.01936 3.8125 2.34967M3.8125 2.34967H1M6.0625 12.313H13.375M6.0625 12.313C6.0625 12.6434 5.94397 12.9601 5.733 13.1937C5.52202 13.4273 5.23587 13.5585 4.9375 13.5585C4.63913 13.5585 4.35298 13.4273 4.142 13.1937C3.93103 12.9601 3.8125 12.6434 3.8125 12.313M6.0625 12.313C6.0625 11.9827 5.94397 11.666 5.733 11.4324C5.52202 11.1988 5.23587 11.0676 4.9375 11.0676C4.63913 11.0676 4.35298 11.1988 4.142 11.4324C3.93103 11.666 3.8125 11.9827 3.8125 12.313M3.8125 12.313H1M10.5625 7.33136H13.375M10.5625 7.33136C10.5625 7.66167 10.444 7.97844 10.233 8.21201C10.022 8.44557 9.73587 8.57678 9.4375 8.57678C9.13913 8.57678 8.85298 8.44557 8.642 8.21201C8.43103 7.97844 8.3125 7.66167 8.3125 7.33136M10.5625 7.33136C10.5625 7.00105 10.444 6.68428 10.233 6.45071C10.022 6.21715 9.73587 6.08594 9.4375 6.08594C9.13913 6.08594 8.85298 6.21715 8.642 6.45071C8.43103 6.68428 8.3125 7.00105 8.3125 7.33136M8.3125 7.33136H1' stroke='black' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/>
                                </svg>
                            </span>
                        </th>
                        <th class='px-4 py-2 border-b text-center align-middle'>อีเมล</th>
                        <th class='px-4 py-2 border-b text-center align-middle'>เบอร์โทรศัพท์</th>
                    </tr>
                </thead>
                <tbody>";
    $counter = 0;                
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rowColor = $counter % 2 === 0 ? 'bg-white' : 'bg-[#DBDBDB]';
            echo "<tr class='{$rowColor}'>
                    <td class='px-4 py-2 border-b text-center align-middle'>" . $row['prefix'] . "</td>
                    <td class='px-4 py-2 border-b text-center align-middle'>" . $row['name'] . "</td>
                    <td class='px-4 py-2 border-b text-center align-middle'>" . $row['faculty'] . "</td>
                    <td class='px-4 py-2 border-b text-center align-middle'>" . $row['status'] . "</td>
                    <td class='px-4 py-2 border-b text-center align-middle'>" . $row['email'] . "</td>
                    <td class='px-4 py-2 border-b text-center align-middle'>" . $row['phone_number'] . "</td>
                  </tr>";
            $counter++;
        }
    } else {
        echo "<tr>
                <td class='px-4 py-2 border-b text-center' colspan='6'>ไม่มีข้อมูล</td>
              </tr>";
    }
    echo "    </tbody>
            </table>
          </div>";

    $conn->close();
        ?>

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