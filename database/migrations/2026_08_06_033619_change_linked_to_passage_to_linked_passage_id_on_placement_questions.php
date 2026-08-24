<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('placement_questions', function (Blueprint $table) {
            $table->string('linked_passage_id')->nullable()->after('linked_to_passage');
        });

        // Câu nào đang linked_to_passage = true -> gán tạm về 'p1'
        // (đoạn văn đầu tiên, đúng với dữ liệu cũ chỉ có 1 đoạn/đề).
        DB::table('placement_questions')
            ->where('linked_to_passage', true)
            ->update(['linked_passage_id' => 'p1']);

        Schema::table('placement_questions', function (Blueprint $table) {
            $table->dropColumn('linked_to_passage');
        });
    }

    public function down(): void
    {
        Schema::table('placement_questions', function (Blueprint $table) {
            $table->boolean('linked_to_passage')->default(false)->after('linked_passage_id');
        });

        DB::table('placement_questions')
            ->whereNotNull('linked_passage_id')
            ->update(['linked_to_passage' => true]);

        Schema::table('placement_questions', function (Blueprint $table) {
            $table->dropColumn('linked_passage_id');
        });
    }
};