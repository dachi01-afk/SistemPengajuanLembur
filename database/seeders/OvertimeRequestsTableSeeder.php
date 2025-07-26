<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class OvertimeRequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('overtime_requests')->insert([
            [
                'user_id' => 2, // Andi Pegawai
                'department_id' => 3,
                'overtime_date' => now()->subDays(3),
                'start_time' => '18:00:00',
                'end_time' => '21:00:00',
                'reason' => 'Mengerjakan laporan tahunan',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'department_id' => 3,
                'overtime_date' => now()->subDays(7),
                'start_time' => '17:00:00',
                'end_time' => '20:00:00',
                'reason' => 'Rekap data kegiatan Diskominfo',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
