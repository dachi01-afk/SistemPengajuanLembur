<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeFeedback extends Model
{

    protected $table = 'overtime_feedbacks';
    protected $fillable = [
        'overtime_request_id',
        'user_id',
        'activity_description',
        'documentation'
    ];
    public $timestamps = false;

    // OvertimeFeedback.php
    public function overtimeRequest()
    {
        return $this->belongsTo(OvertimeRequest::class);
    }
}
