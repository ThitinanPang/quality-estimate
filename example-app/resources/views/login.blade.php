<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <a href="{{route('login')}}"></a>
    <div class="flex items-center justify-center min-h-screen">
        <div class="flex flex-col space-y-4 p-8 bg-white rounded-xl shadow-lg">
            <h1 class="text-2xl font-bold text-center">เข้าสู่ระบบ</h1>
            <input id="name" type="text" placeholder="USERNAME"
                class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input id="password" type="password" placeholder="PASSWORD"
                class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <a href="https://myid.buu.ac.th/newchangepwd"
                class="text-blue-500 hover:underline text-sm text-center">ลืมรหัสผ่าน?</a>
            <button class="bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">ตกลง</button>
        </div>
    </div>
</body>

</html>