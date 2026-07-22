<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgreementStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('status_master')->insert([
            [
                'Stau_UIN' => 1,
                'Status_Name' => 'Active',
            ],
            [
                'Stau_UIN' => 2,
                'Status_Name' => 'Expired',
            ],
            [
                'Stau_UIN' => 3,
                'Status_Name' => 'Renewed',
            ],
        ]);
    }
}