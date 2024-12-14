<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->string('id', 8)->primary();
      $table->string('amount');
      $table->string('payment_method');
      $table->dateTimeTz('order_date', precision: 0);;
      $table->string('receive_user');
      $table->dateTime('receive_date');
      $table->string('phone');
      $table->tinyInteger('status');
      $table->unsignedBigInteger('user_id');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->unsignedBigInteger('voucher_id')->nullable();
      $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
