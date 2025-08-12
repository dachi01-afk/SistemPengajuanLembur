<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeFeedback extends Model
{
    protected $fillable = [
        'overtime_request_id',
        'user_id',
        'overtime_request_id',
        'documentation'
    ];
    public $timestamps = false;
}
