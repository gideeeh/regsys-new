<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrolled_subjects', function (Blueprint $table) {
            if (Schema::hasColumn('enrolled_subjects', 'section_id')) {
                $table->dropForeign(['section_id']);
                $table->dropColumn('section_id');
            }

            $table->unsignedBigInteger('sub_section_id')->nullable()->after('subject_id');
            $table->foreign('sub_section_id')->references('id')->on('section_subjects')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('enrolled_subjects', function (Blueprint $table) {
            $table->dropForeign(['sub_section_id']);
            $table->dropColumn('sub_section_id');

            $table->unsignedBigInteger('section_id')->nullable()->after('subject_id');
            $table->foreign('section_id')->references('section_id')->on('sections')->onDelete('set null');
        });
    }
};
