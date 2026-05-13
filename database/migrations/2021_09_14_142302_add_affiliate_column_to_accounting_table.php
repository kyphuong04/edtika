<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAffiliateColumnToAccountingTable extends Migration
{
    public function up()
    {
        Schema::table('accounting', function (Blueprint $table) {
            $table->integer('referred_user_id')
                ->unsigned()
                ->nullable()
                ->after('store_type');

            $table->boolean('is_affiliate_amount')
                ->default(false)
                ->after('referred_user_id');

            $table->boolean('is_affiliate_commission')
                ->default(false)
                ->after('is_affiliate_amount');
        });
    }
}