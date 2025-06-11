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
        {{-- ฟอร์มเข้าสู่ระบบ --}}
        <form method="POST" action="{{route('login.submit')}}"
            class="flex flex-col space-y-4 p-8 bg-white rounded-xl shadow-lg">
            @csrf
            <h1 class="text-2xl font-bold text-center">เข้าสู่ระบบ</h1>
            {{-- แสดงข้อความ error ในการเข้าสู้ระบบ --}}
            @if($errors->any())
                <div class="text-red-500 text-sm items-center justify-center flex">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <input name="email" type="text" placeholder="EMAIL @go.buu.ac.th" value="{{old('email')}}"
                class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="relative">
                <input id="password" name="password" type="password" placeholder="PASSWORD"
                    class="px-4 py-2 border rounded-md w-full pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                {{-- ปุ่ม toggle แสดง/ซ่อนรหัสผ่าน --}}
                <button type="button" onclick="togglePassword()"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-black-500">
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 block">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 
            10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 
            10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 
            3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 
            0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>
            </div>

            <a href="https://myid.buu.ac.th/newchangepwd"
                class="text-blue-500 hover:underline text-sm text-center">ลืมรหัสผ่าน?</a>
            <button class="bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">ตกลง</button>
        </form>
    </div>
</body>

<script>
    // ฟังก์ชัน แสดง/ซ่อนรหัสผ่าน
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        eyeOpen.classList.toggle('hidden', !isHidden);
        eyeOpen.classList.toggle('block', isHidden);
        eyeClosed.classList.toggle('hidden', isHidden);
        eyeClosed.classList.toggle('block', !isHidden);
    }  
</script>

</html>