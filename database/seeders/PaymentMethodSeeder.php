<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create(
            [
                'name' => 'eSewa'
            ],
        );
        PaymentMethod::create(
            [
                'name' => 'Khalti'
            ],
        );
        PaymentMethod::create(
            [
                'name' => 'Bank'
            ],
        );
        PaymentMethod::create(
            [
                'name' => 'IME Pay'
            ],
        );
    }
}
