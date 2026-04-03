<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->nullable();
            $table->string('name')->nullable();
            $table->string('subject_group')->nullable(); // กลุ่มวิชา
            $table->string('faculty')->nullable();
            $table->string('course')->nullable(); // หลักสูตร
            $table->string('role')->default('user');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->enum('status', ['active', 'retired', 'expired'])->default('active');
            $table->timestamps();
        });
        DB::table('users')->insert([
            'prefix' => 'นาย',
            'name' => 'ฐิตินันท์ วัชรมงคลกุล',
            'subject_group' => 'วิศวกรรมซอฟต์แวร์',
            'faculty' => 'คณะวิทยาการสารสนเทศ',
            'course' => null,
            'role' => 'admin university',
            'email' => '65160217@go.buu.ac.th',
            'phone_number' => '0847337787',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Schema::create('users_assessor', function (Blueprint $table) {
            $table->id();
            $table->string('code_assessor')->nullable();
            $table->string('prefix')->nullable();
            $table->string('name')->nullable();
            $table->string('subject_group')->nullable();
            $table->string('faculty')->nullable();
            $table->string('course')->nullable();
            $table->string('role')->default('assessor');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('assessor_type')->default('junior');
            $table->string('training_type')->nullable();
            $table->enum('status', ['active', 'retired', 'expired'])->default('active');
            $table->timestamps();
        });
        DB::table('users_assessor')->insert([
            'code_assessor' => '69-000',
            'prefix' => 'นาย',
            'name' => 'ฐิตินันท์ วัชรมงคลกุล',
            'subject_group' => 'วิศวกรรมซอฟต์แวร์',
            'faculty' => 'คณะวิทยาการสารสนเทศ',
            'course' => null,
            'role' => 'assessor',
            'email' => '65160217@go.buu.ac.th',
            'phone_number' => '0847337787',
            'assessor_type' => 'junior',
            'training_type' => 'AUN 69',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // ตาราง faculty
        Schema::create('faculty', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // ชื่อคณะ
            $table->string('campus')->nullable(); //วิทยาเขต
            $table->timestamps();
        });
        DB::table('faculty')->insert([
            [
                'name' => 'คณะดนตรีและการแสดง',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะแพทยศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะโลจิสติกส์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะวิทยาศาสตร์การกีฬา',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะศึกษาศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'วิทยาลัยนานาชาติ',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะเภสัชศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะมนุษยศาสตร์และสังคมศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะวิทยาการสารสนเทศ',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะวิศวกรรมศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะสาธารณสุขศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะพยาบาลศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะรัฐศาสตร์และนิติศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะวิทยาศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะศิลปกรรมศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะสหเวชศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะบริหารธุรกิจ',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'คณะทันตแพทยศาสตร์',
                'campus' => 'บางแสน',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ตาราง courses
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->string('code')->unique(); // รหัสหลักสูตร
            $table->string('name'); // ชื่อหลักสูตร
            $table->string('level')->nullable(); //1 = ตรี/2 = โท/3 = เอก
            $table->enum('status', ['active', 'closed' , 'suspended'])->default('active');
            $table->timestamps();
        });

        Schema::create('assessment', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();          // ชื่อผู้ประเมินหรือข้อมูลที่เกี่ยวข้อง
            $table->string('faculty')->nullable();
            $table->string('courses')->nullable();
            $table->string('criterion')->nullable();      // เกณฑ์การประเมิน
            $table->string('result')->nullable();       // ผลการประเมิน
            $table->string('strength')->nullable();       // จุดแข็ง
            $table->string('improvement')->nullable();    // จุดปรับปรุง
            $table->json('score')->nullable();  // เก็บ array เป็น JSON
            $table->json('overall')->nullable();
            $table->timestamps();
        });

        Schema::create('course_assessors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->string('campus')->nullable(); // วิทยาเขต
            $table->string('subject_group')->nullable(); // กลุ่มสาขาวิชา
            $table->string('education_level')->nullable(); // ระดับการศึกษา
            $table->string('assessment_type')->nullable(); // รูปแบบการประเมิน
            $table->string('chairperson')->nullable(); // ประธานการประเมิน
            $table->string('position')->nullable(); // กรรมการ
            $table->string('intern')->nullable(); // ผู้ฝึกประสบการณ์
            $table->date('assessment_date')->nullable(); // วันตรวจประเมิน
            $table->string('secretary')->nullable(); // เลขา
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
