<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class OvertimeApprovalsTabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('overtime_approvals')->insert([
            [
                'overtime_request_id' => 2, // approved request
                'approved_by' => 3, // Budi Kepala Bidang
                'decision' => 'approved',
                'notes' => 'Disetujui karena urgent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
