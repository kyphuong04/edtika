<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicWordListsTableFixed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_word_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('band_level'); // Band 4.5-5.0, 5.0-5.5, 6.0-6.5, 7+
            $table->integer('word_count')->default(0);
            $table->unsignedBigInteger('creator_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index thay vì foreign key
            $table->index('creator_id');
        });
        
        Schema::create('academic_word_list_words', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_word_list_id');
            $table->string('word');
            $table->string('pronunciation')->nullable();
            $table->text('definition');
            $table->text('example')->nullable();
            $table->text('translation')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index('academic_word_list_id');
        });
        
        Schema::create('user_word_list_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('academic_word_list_id');
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('academic_word_list_id');
            $table->unique(['user_id', 'academic_word_list_id']);
        });
        
        // Add column to user_word_progress
        if (Schema::hasTable('user_word_progress')) {
            Schema::table('user_word_progress', function (Blueprint $table) {
                if (!Schema::hasColumn('user_word_progress', 'academic_word_list_word_id')) {
                    $table->unsignedBigInteger('academic_word_list_word_id')->nullable()->after('word_list_id');
                    $table->index('academic_word_list_word_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_word_progress')) {
            Schema::table('user_word_progress', function (Blueprint $table) {
                if (Schema::hasColumn('user_word_progress', 'academic_word_list_word_id')) {
                    $table->dropIndex(['academic_word_list_word_id']);
                    $table->dropColumn('academic_word_list_word_id');
                }
            });
        }
        
        Schema::dropIfExists('user_word_list_access');
        Schema::dropIfExists('academic_word_list_words');
        Schema::dropIfExists('academic_word_lists');
    }
}
