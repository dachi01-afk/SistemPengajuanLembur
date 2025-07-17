<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Sistem',
                'nip' => 123456789876567489,
                'no_tlp' => '082233445566',
                'email' => 'admin@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 1,
                'department_id' => 1,
                'position_id' => 1,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Kepala Bidang Sandi',
                'nip' => 123456789876554321,
                'no_tlp' => '092233445566',
                'email' => 'budi@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 3,
                'department_id' => 3,
                'position_id' => 8,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'  => 'Andi Pegawai',
                'nip' => 123456789876561234,
                'no_tlp' => '082233445566',
                'email' => 'andi@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'department_id' => 3,
                'position_id' => 12,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name'  => 'Reno Pegawai',
                'nip' => 1234567898765612344,
                'no_tlp' => '082233445566',
                'email' => 'reno@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'department_id' => 1,
                'position_id' => 10,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name'  => 'diva Pegawai',
                'nip' => 123456789876561233,
                'no_tlp' => '082233445566',
                'email' => 'diva@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'department_id' => 2,
                'position_id' => 11,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name'  => 'babe Pegawai',
                'nip' => 123456789876561222,
                'no_tlp' => '082233445566',
                'email' => 'babe@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'department_id' => 3,
                'position_id' => 13,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name'  => 'Yanto Pegawai',
                'nip' => 123456789876561211,
                'no_tlp' => '082233445566',
                'email' => 'Yanto@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'department_id' => 4,
                'position_id' => 14,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name'  => 'Putra Pegawai',
                'nip' => 123456789876561234,
                'no_tlp' => '082233445566',
                'email' => 'Putra@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'department_id' => 5,
                'position_id' => 15,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name'  => 'Beni Pegawai',
                'nip' => 123456789876561234,
                'no_tlp' => '082233445566',
                'email' => 'Beni@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'department_id' => 6,
                'position_id' => 16,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name'  => 'Bancin Pegawai',
                'nip' => 123456789876561255,
                'no_tlp' => '082233445566',
                'email' => 'Bancin@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'department_id' => 1,
                'position_id' => 17,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name'  => 'Amer Pegawai',
                'nip' => 123456789876561234,
                'no_tlp' => '082233445566',
                'email' => 'Amer@diskominfo.test',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'department_id' => 2,
                'position_id' => 10,
                'email_verified_at' => Carbon::now(),
                'remember_token' => random_int(10, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
