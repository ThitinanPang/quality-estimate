@extends('layouts.header')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
    <div class="flex flex-col items-center justify-center mt-[32px]">
        <p class="text-[28px] w-[900px] h-[40px] bg-[#FFCE00] text-center border border-b-0 rounded-t-[14px]">
            แก้ไขข้อมูลผู้ใช้</p>

        <form action="{{ route('updateuser', $user->id) }}" method="POST" id="updateUserForm">
            @csrf
            <div id="formContainer"
                class="border rounded-b-[39px] bg-[#DBDBDB] w-[900px] pl-[40px] pr-[40px] pt-[10px] overflow-y-auto">

                <p class="mt-[9px] text-[20px]">ตำแหน่ง</p>
                <select name="role" id="roleSelector"
                    class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin Faculty</option>
                    <option value="admin university" {{ $user->role == 'admin university' ? 'selected' : '' }}>Admin
                        University</option>
                    <option value="assessor" {{ $user->role == 'assessor' ? 'selected' : '' }}>Assessor</option>
                </select>

                <div id="form-user">
                    <p class="mt-[9px] text-[20px]">คำนำหน้า</p>
                    <input type="text" name="prefix" value="{{ $user->prefix }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">ชื่อ - สกุล</p>
                    <input type="text" name="name" value="{{ $user->name }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">กลุ่มวิชา</p>
                    <input type="text" name="subject_group" value="{{ $user->subject_group }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">คณะ</p>
                    <input type="text" name="faculty" value="{{ $user->faculty }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">หลักสูตร</p>
                    <input type="text" name="course" value="{{ $user->course }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">อีเมล</p>
                    <input type="email" name="email" value="{{ $user->email }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">เบอร์โทรศัพท์</p>
                    <input type="text" name="phone_number" value="{{ $user->phone_number }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">สถานะ</p>
                    <select name="status" class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">
                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="retired" {{ $user->status == 'retire' ? 'selected' : '' }}>Retired</option>
                        <option value="expired" {{ $user->status == 'expire' ? 'selected' : '' }}>Expired</option>
                    </select>
                    <div class="h-[20px]"></div>
                </div>
                {{-- role assessor --}}
                <div id="form-assessor" class="hidden">
                    <p class="mt-[9px] text-[20px]">Code Assessor <span class="text-red-500">*</span></p>
                    <input required name="code_assessor"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]"
                        value="{{ $user->code_assessor ?? '' }}">

                    <p class="mt-[9px] text-[20px]">คำนำหน้า</p>
                    <input type="text" name="assessor_prefix" value="{{ $user->prefix }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">ชื่อ - สกุล</p>
                    <input type="text" name="name" value="{{ $user->name }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">กลุ่มวิชา</p>
                    <input type="text" name="subject_group" value="{{ $user->subject_group }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">คณะ</p>
                    <input type="text" name="faculty" value="{{ $user->faculty }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">หลักสูตร</p>
                    <input type="text" name="course" value="{{ $user->course }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">Assessor Type</p>
                    <select name="assessor_type"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">
                        <option value="junior">Junior</option>
                        <option value="senior">Senior</option>
                        <option value="lead">Lead</option>
                        <option value="lead">Novice</option>
                    </select>

                    <p class="mt-[9px] text-[20px]">Training Type <span class="text-red-500">*</span></p>
                    <input required type="text" name="training_type"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">อีเมล</p>
                    <input type="email" name="email" value="{{ $user->email }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">เบอร์โทรศัพท์</p>
                    <input type="text" name="phone_number" value="{{ $user->phone_number }}"
                        class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">

                    <p class="mt-[9px] text-[20px]">สถานะ</p>
                    <select name="status" class="bg-white h-[25px] w-[800px] border rounded mt-[9px] pl-3 text-[20px]">
                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="retired" {{ $user->status == 'retire' ? 'selected' : '' }}>Retired</option>
                        <option value="expired" {{ $user->status == 'expire' ? 'selected' : '' }}>Expired</option>
                    </select>
                    <div class="h-[20px]"></div>
                </div>
            </div>

            <div class="justify-center flex gap-[50px]">
                <button type="button" onclick="window.history.back()"
                    class="w-[155px] h-[37px] mt-[32px] bg-[#DBDBDB] rounded-[9px] border text-[20px]">ยกเลิก</button>
                <button type="submit"
                    class="w-[155px] h-[37px] mt-[32px] bg-[#FFCE00] border rounded-[9px] hover:bg-white text-[20px]">
                    บันทึก
                </button>
            </div>
        </form>
    </div>

    <script>
        const roleSelector = document.getElementById('roleSelector');
        const formUser = document.getElementById('form-user');
        const formAssessor = document.getElementById('form-assessor');
        const formContainer = document.getElementById('formContainer');
        // ประกาศชื่อตัวแปรให้ชัดเจน และประกาศแค่ครั้งเดียวพอครับ
        const updateForm = document.getElementById('updateUserForm');

        function setInputsDisabled(container, isDisabled) {
            const inputs = container.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.disabled = isDisabled;
            });
        }

        function toggleFields() {
            const isAssessor = roleSelector.value === 'assessor';

            if (isAssessor) {
                formUser.classList.add('hidden');
                formAssessor.classList.remove('hidden');
                formContainer.style.height = "920px";

                setInputsDisabled(formUser, true);
                setInputsDisabled(formAssessor, false);
            } else {
                formUser.classList.remove('hidden');
                formAssessor.classList.add('hidden');
                formContainer.style.height = "700px";

                setInputsDisabled(formUser, false);
                setInputsDisabled(formAssessor, true);
            }
        }

        // ทำงานเมื่อโหลดหน้าและเมื่อเปลี่ยนค่า Role
        document.addEventListener('DOMContentLoaded', toggleFields);
        roleSelector.addEventListener('change', toggleFields);

        // --- ส่วนของ SweetAlert (แก้ไขให้เหลืออันเดียว) ---
        updateForm.addEventListener('submit', function (event) {
            // 1. ป้องกันการ Submit ทันที
            event.preventDefault();

            // 2. ตรวจสอบ HTML5 Validation (เช่น required)
            if (!updateForm.checkValidity()) {
                updateForm.reportValidity();
                return;
            }

            // 3. แสดง SweetAlert ยืนยัน
            Swal.fire({
                icon: 'success',
                title: 'บันทึกสำเร็จ!',
                text: 'ข้อมูลของคุณถูกอัปเดตเรียบร้อยแล้ว',
                showConfirmButton: false, // ไม่โชว์ปุ่มตกลง
                timer: 1500, // แสดงผล 1.5 วินาที
                timerProgressBar: true
            }).then(() => {

                updateForm.submit();

            });
        });
    </script>
@endsection