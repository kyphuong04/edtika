<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIeltsTestAutosavesTable extends Migration
{
    public function up()
    {
        Schema::create('ielts_test_autosaves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_id')->nullable();
            $table->string('context_key', 120);
            $table->longText('payload_json');
            $table->unsignedBigInteger('saved_at_ms')->nullable();
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();

            $table->unique(['user_id', 'context_key'], 'ielts_autosaves_user_context_unique');
            $table->index('test_id');
            $table->index('updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ielts_test_autosaves');
    }
}
