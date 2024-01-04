<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;
    public $connection = 'aim_app';
    public $table = 'track_app_devices';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
