<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'overtime_date',
        'start_time',
        'end_time',
        'reason',
        'spt_file',
        'status',
        'approved_by',
        'approved_at',
        'approved_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // public function department()
    // {
    //     return $this->belongsTo(Department::class);
    // }

    public function approvedby()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
