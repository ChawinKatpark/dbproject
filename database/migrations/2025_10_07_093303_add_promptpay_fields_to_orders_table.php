<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_slip_path')->nullable();
            $table->dateTime('payment_time')->nullable();
            $table->string('payment_status')->default('pending'); // pending, verified, rejected
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_slip_path', 'payment_time', 'payment_status']);
        });
    }
};
