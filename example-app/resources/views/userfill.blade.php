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
        <form action="{{route('userfill.submit')}}" method="post"
            class="border rounded-[39px] bg-[#DBDBDB] w-[900px] h-[450px] mt-[32px] pl-[40px] pr-[40px] pt-[10px]">
            @csrf
            <p class="mt-[9px]">คำนำหน้า</p>
            <input type="text" name="prefix" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">ขื่อ - สกุล</p>
            <input type="text" name="name" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">กลุ่มวิชา</p>
            <input type="text" name="subject_group" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">คณะ</p>
            <input type="text" name="faculty" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">หลักสูตร</p>
            <input type="text" name="course" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <p class="mt-[9px]">เบอร์โทรศัพท์</p>
            <input type="text" id="myInput" name="phone_number" class=" bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3">
            <div class="flex items-center justify-center">
            <button type="submit"
                class="w-[155px] mt-[55px] h-[37px] bg-[#FFCE00] border rounded-[9px] hover:bg-white">บันทึก</button>
            </div>
        </form>
    </div>
</body>
</html>