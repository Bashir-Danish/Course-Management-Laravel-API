<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        // Create Branch table
        if (!Schema::hasTable('branches')) {
            Schema::create('branches', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('address');
                $table->timestamps();
            });
            Log::info('Created branches table');
        } else {
            Log::info('Branches table already exists');
        }

        // Create Department table
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
            Log::info('Created departments table');
        } else {
            Log::info('Departments table already exists');
        }

        // Create Teacher table
        if (!Schema::hasTable('teachers')) {
            Schema::create('teachers', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->unique();
                $table->enum('gender', ['Male', 'Female']);
                $table->decimal('salary', 10, 2);
                $table->timestamps();
            });
            Log::info('Created teachers table');
        } else {
            Log::info('Teachers table already exists');
        }

        // Create Teacher Departments pivot table
        if (!Schema::hasTable('teacher_departments')) {
            Schema::create('teacher_departments', function (Blueprint $table) {
                $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
                $table->foreignId('department_id')->constrained()->onDelete('cascade');
                $table->primary(['teacher_id', 'department_id']);
                $table->timestamps();
            });
            Log::info('Created teacher_departments table');
        } else {
            Log::info('Teacher_departments table already exists');
        }

        // Create Course table
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('fees', 10, 2);
                $table->string('duration');
                $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
                $table->json('available_time_slots');
                $table->timestamps();
            });
            Log::info('Created courses table');
        } else {
            Log::info('Courses table already exists');
        }

        // Create Student table
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->enum('gender', ['Male', 'Female']);
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->date('dob');
                $table->date('enrolls_date');
                $table->timestamps();
            });
            Log::info('Created students table');
        } else {
            Log::info('Students table already exists');
        }

        // Create Registration table
        if (!Schema::hasTable('registrations')) {
            Schema::create('registrations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->date('registration_date');
                $table->decimal('fees_total', 10, 2);
                $table->decimal('fees_paid', 10, 2);
                $table->json('time_slot');
                $table->enum('status', ['Unpaid', 'Paid', 'Cancelled']);
                $table->timestamps();
            });
            Log::info('Created registrations table');
        } else {
            Log::info('Registrations table already exists');
        }

        // Create Monthly Reporting table
        if (!Schema::hasTable('monthly_reportings')) {
            Schema::create('monthly_reportings', function (Blueprint $table) {
                $table->id();
                $table->date('month');
                $table->integer('total_students');
                $table->integer('new_registrations');
                $table->decimal('total_revenue', 10, 2);
                $table->integer('total_teachers');
                $table->integer('total_courses');
                $table->decimal('total_expenses', 10, 2);
                $table->json('data')->nullable();
                $table->timestamps();
            });
            Log::info('Created monthly_reportings table');
        } else {
            Log::info('Monthly_reportings table already exists');
        }
    }

    public function down(): void
    {
        // No table drops as per requirement
    }
};