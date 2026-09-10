<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_word_progress', function (Blueprint $table) {
            $table->boolean('needs_review')->default(false)->after('is_learned')->index();
            $table->timestamp('needs_review_at')->nullable()->after('needs_review');
            $table->boolean('is_starred')->default(false)->after('needs_review_at')->index();
            $table->timestamp('starred_at')->nullable()->after('is_starred');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_word_progress', function (Blueprint $table) {
            $table->dropColumn(['needs_review', 'needs_review_at', 'is_starred', 'starred_at']);
        });
    }
};
