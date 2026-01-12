<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToFlashcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flashcards', function (Blueprint $table) {
            // Add user_id first
            if (!Schema::hasColumn('flashcards', 'user_id')) {
                $table->bigInteger('user_id')->unsigned()->after('id');
                $table->index('user_id');
                
                // Add foreign key
                try {
                    $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                } catch (\Exception $e) {
                    // Foreign key might already exist, ignore error
                }
            }
            
            // Add word column if not exists
            if (!Schema::hasColumn('flashcards', 'word')) {
                $table->string('word');
            }
            
            // Add pronunciation without referencing 'word' column
            if (!Schema::hasColumn('flashcards', 'pronunciation')) {
                $table->string('pronunciation')->nullable();
            }
            
            // Add definition if not exists
            if (!Schema::hasColumn('flashcards', 'definition')) {
                $table->text('definition');
            }
            
            // Add example without referencing 'definition' column
            if (!Schema::hasColumn('flashcards', 'example')) {
                $table->text('example')->nullable();
            }
            
            // Add translation without referencing 'example' column
            if (!Schema::hasColumn('flashcards', 'translation')) {
                $table->text('translation')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flashcards', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('flashcards', 'user_id')) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, ignore error
                }
                $table->dropIndex(['user_id']);
                $table->dropColumn('user_id');
            }
            
            if (Schema::hasColumn('flashcards', 'word')) {
                $table->dropColumn('word');
            }
            
            if (Schema::hasColumn('flashcards', 'pronunciation')) {
                $table->dropColumn('pronunciation');
            }
            
            if (Schema::hasColumn('flashcards', 'definition')) {
                $table->dropColumn('definition');
            }
            
            if (Schema::hasColumn('flashcards', 'example')) {
                $table->dropColumn('example');
            }
            
            if (Schema::hasColumn('flashcards', 'translation')) {
                $table->dropColumn('translation');
            }
        });
    }
}
