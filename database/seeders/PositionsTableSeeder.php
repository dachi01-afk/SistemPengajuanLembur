<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insert([
            ['position' => 'Kepala Dinas'],
            ['position' => 'Sekretaris'],
            ['position' => 'Kasub Umum'],
            ['position' => 'Kasub Keuangan'],
            ['position' => 'Kabid Komunikasi Publik'],
            ['position' => 'Kabid Teknologi Informatika'],
            ['position' => 'Kabid SIP'],
            ['position' => 'Kabid Persandian'],
            ['position' => 'Kabid Aplikasi Informatika'],
            ['position' => 'Pranata Humas Muda'],
            ['position' => 'Penelaah Teknis Kebijakan'],
            ['position' => 'Sandiman Muda'],
            ['position' => 'Pranata Komputer Muda'],
            ['position' => 'Statistisi Muda'],
            ['position' => 'Arsiparis Mahir'],
            ['position' => 'Pengelola Data dan Informasi'],
            ['position' => 'Pengadministrasi  Perkantoran'],
        ]);
    }
}
