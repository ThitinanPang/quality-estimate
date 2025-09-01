<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UserFill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="flex flex-col items-center justify-center mt-[32px]">
        <p class="text-[24px]">กรอกรายละเอียด</p>
        <form action="{{route('userfill.submit')}}" method="post" class="border rounded-[39px] bg-[#DBDBDB] w-[900px] h-[500px] mt-[32px] pl-[40px] pr-[40px] pt-[10px]">
            @csrf
            <p class="mt-[9px]">คำนำหน้า</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px]">
            <p class="mt-[9px]">ขื่อ - สกุล</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px]">
            <p class="mt-[9px]">กลุ่มวิชา</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px]">
            <p class="mt-[9px]">คณะ</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px]">
            <p class="mt-[9px]">หลักสูตร</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px]">
            <p class="mt-[9px]">อีเมล</p>
            <input type="text" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px]">
            <p class="mt-[9px]">เบอร์โทรศัพท์</p>
            <input type="text" id="myInput" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px]">
        </form>
        <button onclick="myfunction()"
            class="w-[155px] h-[37px] mt-[32px] bg-[#FFCE00] border rounded-[9px] hover:bg-white">บันทึก</button>
    </div>

</body>
    <script>
        const input = document.getElementById('myInput');

        input.addEventListener('input', () => {
            // แทนที่เครื่องหมาย - ด้วยช่องว่าง
            input.value = input.value.replace(/[-\s]/g, '');
        });
        function myfunction() {
            alert("บันทึกสำเร็จ");
            window.location.href = "{{ route('home') }}";
        }
    </script>

</html>
