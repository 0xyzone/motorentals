<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentType::create([
            'name' => 'Advance'
        ]);
        PaymentType::create([
            'name' => 'Full Settlement'
        ]);
        PaymentType::create([
            'name' => 'Normal'
        ]);
    }
}
