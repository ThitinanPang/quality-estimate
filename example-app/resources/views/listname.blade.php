@extends('layouts.header')

@section('content')
    <p class="absolute left-[85px] top-[150px] font-normal text-[36px] leading-[54px]">
        รายชื่อผู้ประเมิน</p>
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
    <p class=" absolute text-[24px] top-[215px] left-[85px]">คณะวิทยาการสารสนเทศ</p>
    <p
        class=" absolute top-[215px] left-[350px] flex items-center px-[25px] w-[73px] h-[32px] bg-[#D9D9D9] border border-black rounded-l-[20px]">
        สาขา</p>
    <select name="" id=""
        class=" absolute top-[215px] left-[422px] flex flex-row justify-center items-center px-[6px]w-[205px] h-[32px] bg-white border border-black rounded-r-[20px]">
        <option value="">วิศวกรรมซอฟต์แวร์</option>
    </select>
@endsection