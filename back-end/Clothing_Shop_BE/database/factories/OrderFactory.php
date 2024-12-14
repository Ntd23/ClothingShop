<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'id' => rand(100, 999),
      'user_id' => 3, // Tạo người dùng mới cho order
      'amount' => '9999999', // Tổng tiền ngẫu nhiên
      'status' => rand(0, 3), // Trạng thái ngẫu nhiên
      'receive_date' => Carbon::now(),
      'receive_user' => 'test1',
      'payment_method' => 'direct_payment',
      'phone' => '4344545'
    ];
  }
}
