<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apps extends Model
{
    use HasFactory;

    public $connection = 'aim_app';
    public $table = 'track_app_apps';
}
