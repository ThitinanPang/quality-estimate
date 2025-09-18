@extends('layouts.header')

@section('content')
    <p class="absolute w-[257px] h-[53px] left-[85px] top-[150px] font-normal text-[36px] leading-[54px]">
        ข้อมูลหลักสูตร</p>
    <label for="facultyFile"
        class="absolute w-[155px] h-[37px] left-[85px] top-[210px] bg-[#FFCE00] border border-black rounded-[9px] box-border flex items-center justify-center text-[18px] hover:bg-white cursor-pointer">
        <svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M11.5 7.75V14.25M14.875 11H8.125M21.625 11C21.625 12.2804 21.3631 13.5482 20.8543 14.7312C20.3455 15.9141 19.5996 16.9889 18.6595 17.8943C17.7193 18.7997 16.6031 19.5178 15.3747 20.0078C14.1462 20.4978 12.8296 20.75 11.5 20.75C10.1704 20.75 8.85375 20.4978 7.62533 20.0078C6.39691 19.5178 5.28074 18.7997 4.34054 17.8943C3.40035 16.9889 2.65455 15.9141 2.14572 14.7312C1.63689 13.5482 1.375 12.2804 1.375 11C1.375 8.41414 2.44174 5.93419 4.34054 4.10571C6.23935 2.27723 8.81468 1.25 11.5 1.25C14.1853 1.25 16.7606 2.27723 18.6595 4.10571C20.5583 5.93419 21.625 8.41414 21.625 11Z"
                stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        เพิ่มหลักสูตร
    </label>
    <input type="file" id="facultyFile" class="hidden" accept=".xlsx,.xls">
    <div class="flex items-center justify-center mt-[140px]">
        <table class="w-[1350px]  rounded-[10px] overflow-hidden">
            <thead class="bg-[#FFCE00] h-[66px]">
                <tr>
                    <th class="text-[24px]">ลำดับที่</th>
                    <th class="text-[24px]">คณะภายในมหาวิทยาลัยบูรพา</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <script>
        document.getElementById('facultyFile').addEventListener('change', function (e) {
            let file = e.target.files[0];
            let formData = new FormData();
            formData.append('file', file);

            fetch("{{ route('faculty.import') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let tbody = document.querySelector('tbody');
                        tbody.innerHTML = '';
                        data.faculties.forEach((f, index) => {
                            tbody.innerHTML += `<tr>
                        <td class="text-center">${index + 1}</td>
                        <td class="text-center">${f.name}</td>
                    </tr>`;
                        });
                    } else {
                        alert('อัปโหลดล้มเหลว');
                    }
                })
                .catch(err => console.error(err));
        });
    </script>

@endsection