<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbl_mast_user_logi')->insert([
            [
                'User_Name'          => 'Admin User',
                'User_Logo'          => 'default-user.png',

                'Prmy_Emai'          => 'admin@example.com',
                'Prmy_Emai_Vefi'     => 1,

                'Altn_Emai'          => 'admin2@example.com',
                'Altn_Emai_Vefi'     => 1,

                'Cutr_UIN'           => 101,
                'ISD_UIN'            => 91,

                'Prmy_MoNo'          => 9876543210,
                'Prmy_MoNo_Vefi'     => 1,
                'OTP_Prmy_MoNo'      => 123456,

                'Altn_MoNo'          => 9123456789,
                'Altn_MoNo_Vefi'     => 1,
                'OTP_Altn_MoNo'      => 654321,

                'User_pasw'          => Hash::make('password'),

                'Pasw_Self_Gntd'     => 1,
                'Pasw_Rest_By'       => null,
                'Pasw_Rest_On'       => null,

                'CrBy'               => 1,
                'Stau_UIN'           => 1,
            ],
            [
                'User_Name'          => 'Jit Backend',
                'User_Logo'          => 'jit-avatar.png',

                'Prmy_Emai'          => 'jit@example.com',
                'Prmy_Emai_Vefi'     => 1,

                'Altn_Emai'          => 'jit.alt@example.com',
                'Altn_Emai_Vefi'     => null,

                'Cutr_UIN'           => 101,
                'ISD_UIN'            => 91,

                'Prmy_MoNo'          => 9812345678,
                'Prmy_MoNo_Vefi'     => 1,
                'OTP_Prmy_MoNo'      => null,

                'Altn_MoNo'          => null,
                'Altn_MoNo_Vefi'     => null,
                'OTP_Altn_MoNo'      => null,

                'User_pasw'          => Hash::make('secret123'),

                'Pasw_Self_Gntd'     => 1,
                'Pasw_Rest_By'       => null,
                'Pasw_Rest_On'       => null,

                'CrBy'               => 1,
                'Stau_UIN'           => 1,
            ],
            [
                'User_Name'          => 'Priya Sharma',
                'User_Logo'          => 'priya-avatar.png',

                'Prmy_Emai'          => 'priya.sharma@example.com',
                'Prmy_Emai_Vefi'     => 1,

                'Altn_Emai'          => 'priya.alt@example.com',
                'Altn_Emai_Vefi'     => 1,

                'Cutr_UIN'           => 102,
                'ISD_UIN'            => 91,

                'Prmy_MoNo'          => 9898989898,
                'Prmy_MoNo_Vefi'     => 1,
                'OTP_Prmy_MoNo'      => null,

                'Altn_MoNo'          => 9797979797,
                'Altn_MoNo_Vefi'     => null,
                'OTP_Altn_MoNo'      => 111222,

                'User_pasw'          => Hash::make('priya@2026'),

                'Pasw_Self_Gntd'     => 0,
                'Pasw_Rest_By'       => 1,
                'Pasw_Rest_On'       => now()->subDays(10)->timestamp,

                'CrBy'               => 1,
                'Stau_UIN'           => 1,
            ],
            [
                'User_Name'          => 'Rahul Verma',
                'User_Logo'          => 'rahul-avatar.png',

                'Prmy_Emai'          => 'rahul.verma@example.com',
                'Prmy_Emai_Vefi'     => null,

                'Altn_Emai'          => 'rahul.alt@example.com',
                'Altn_Emai_Vefi'     => null,

                'Cutr_UIN'           => 103,
                'ISD_UIN'            => 91,

                'Prmy_MoNo'          => 9654321098,
                'Prmy_MoNo_Vefi'     => 0,
                'OTP_Prmy_MoNo'      => 445566,

                'Altn_MoNo'          => null,
                'Altn_MoNo_Vefi'     => null,
                'OTP_Altn_MoNo'      => null,

                'User_pasw'          => Hash::make('rahul@123'),

                'Pasw_Self_Gntd'     => 1,
                'Pasw_Rest_By'       => null,
                'Pasw_Rest_On'       => null,

                'CrBy'               => 2,
                'Stau_UIN'           => 2,
            ],
            [
                'User_Name'          => 'Ananya Das',
                'User_Logo'          => 'ananya-avatar.png',

                'Prmy_Emai'          => 'ananya.das@example.com',
                'Prmy_Emai_Vefi'     => 1,

                'Altn_Emai'          => 'ananya.alt@example.com',
                'Altn_Emai_Vefi'     => 1,

                'Cutr_UIN'           => 101,
                'ISD_UIN'            => 91,

                'Prmy_MoNo'          => 9911223344,
                'Prmy_MoNo_Vefi'     => 1,
                'OTP_Prmy_MoNo'      => null,

                'Altn_MoNo'          => 9955667788,
                'Altn_MoNo_Vefi'     => 1,
                'OTP_Altn_MoNo'      => null,

                'User_pasw'          => Hash::make('ananya@pass'),

                'Pasw_Self_Gntd'     => 1,
                'Pasw_Rest_By'       => null,
                'Pasw_Rest_On'       => null,

                'CrBy'               => 1,
                'Stau_UIN'           => 3,
            ],
        ]);
    }
}
