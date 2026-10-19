<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('job_applications')
            ->where('status', 'withdrawn')
            ->update(['status' => 'pending']);

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->enum('status', ['draft', 'open', 'closed'])->default('draft')->change();
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'under_review',
                'shortlisted',
                'interview',
                'accepted',
                'rejected',
            ])->default('pending')->change();
        });

        if (Schema::hasColumn('job_applications', 'deleted_at')) {
            if (DB::connection()->getDriverName() === 'sqlite') {
                DB::statement('ALTER TABLE job_applications DROP COLUMN deleted_at');
            } else {
                Schema::table('job_applications', function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->enum('status', ['draft', 'open', 'closed'])->default('open')->change();
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'under_review',
                'shortlisted',
                'interview',
                'accepted',
                'rejected',
                'withdrawn',
            ])->default('pending')->change();
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
};
