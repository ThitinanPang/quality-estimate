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
            $table->string('subject_group')->nullable();
            $table->string('faculty')->nullable();
            $table->string('course')->nullable();
            $table->string('role')->default('user');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

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

        // ตาราง courses
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->string('code')->unique(); // รหัสหลักสูตร
            $table->string('name'); // ชื่อหลักสูตร
            $table->string('level')->nullable(); //1 = ตรี/2 = โท/3 = เอก
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
