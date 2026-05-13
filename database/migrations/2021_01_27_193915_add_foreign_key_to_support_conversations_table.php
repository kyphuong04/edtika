<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToSupportConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Kiểm tra foreign key support_id
        $supportFk = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'support_conversations'
              AND CONSTRAINT_NAME = 'support_conversations_support_id_foreign'
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");

        if (empty($supportFk)) {
            Schema::table('support_conversations', function (Blueprint $table) {
                $table->foreign('support_id')
                      ->references('id')
                      ->on('supports')
                      ->onDelete('cascade');
            });
        }

        // Kiểm tra foreign key sender_id
        $senderFk = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'support_conversations'
              AND CONSTRAINT_NAME = 'support_conversations_sender_id_foreign'
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");

        if (empty($senderFk)) {
            Schema::table('support_conversations', function (Blueprint $table) {
                $table->foreign('sender_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        }
    }
}
