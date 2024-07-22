<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    protected $table = 'schedule';

    protected $fillable = [
        'message', 
        'newMessage', 
        'status', 
        'character', 
        'scheduled_at',
        "phone"
    ];
}
