<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->text('requirments');
            $table->text('responsibilities');
            $table->string('location');
            $table->enum('type', ['full-time', 'part-time', 'internship' , 'freelance']);
            $table->enum('workpalce_type', ['remote', 'on-site', 'hybrid']);
            $table->enum('experience_level', ['entry-level', 'junior' , 'mid-level', 'senior-level']);
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->enum('status', ['closed','open','draft'])->default('open');
            $table->date('application_deadline')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
