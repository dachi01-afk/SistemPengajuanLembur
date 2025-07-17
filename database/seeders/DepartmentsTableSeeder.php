<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['department' => 'Komunikasi Publik'],
            ['department' => 'Teknologi Informatika'],
            ['department' => 'Persandian'],
            ['department' => 'SIP'],
            ['department' => 'Aplikasi Informatika'],
            ['department' => 'Kepegawaian & Umum'],
        ]);
    }
}
