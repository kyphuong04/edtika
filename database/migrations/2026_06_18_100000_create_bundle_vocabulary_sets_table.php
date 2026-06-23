<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundleVocabularySetsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bundle_vocabulary_sets')) {
            Schema::create('bundle_vocabulary_sets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('bundle_id');
                $table->unsignedInteger('created_by');
                $table->unsignedInteger('approved_by')->nullable();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('source_file_path')->nullable();
                $table->string('status')->default('draft');
                $table->unsignedInteger('words_count')->default(0);
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->text('rejection_note')->nullable();
                $table->timestamps();

                $table->index('bundle_id');
                $table->index('created_by');
                $table->index('approved_by');
                $table->index('status');

                $table->foreign('bundle_id')->references('id')->on('bundles')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bundle_vocabulary_sets');
    }
}
