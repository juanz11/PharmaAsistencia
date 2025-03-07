<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'break_start',
        'break_end',
        'status',
        'device',
        'ip_address'
    ];

    protected $dates = [
        'date',
        'check_in',
        'check_out',
        'break_start',
        'break_end'
    ];

    public function getCheckInAttribute($value)
    {
        if (!$value) return null;
        return Carbon::parse($value)->setTimezone('America/Caracas');
    }

    public function getCheckOutAttribute($value)
    {
        if (!$value) return null;
        return Carbon::parse($value)->setTimezone('America/Caracas');
    }

    public function getBreakStartAttribute($value)
    {
        if (!$value) return null;
        return Carbon::parse($value)->setTimezone('America/Caracas');
    }

    public function getBreakEndAttribute($value)
    {
        if (!$value) return null;
        return Carbon::parse($value)->setTimezone('America/Caracas');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
