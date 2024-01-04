<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackLog extends Model
{
    use HasFactory;

    public $connection = 'aim_app';
    public $table = 'track_app_track_logs';
}
