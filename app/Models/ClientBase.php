<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cache;
use Config;

class ClientBase extends Model
{
    use HasFactory;
    public $connection = 'aim_base';
    public $table = 'aim_base_client_bases';

    public static function clientConfig($clientId) {
        $config = Cache::remember('client_config_'.$clientId, config('database.client_base_cache'), function () use ($clientId) {
            $clientBase = ClientBase::find($clientId);
            if (!$clientBase) {
                return null;
            }
            return $clientBase->toArray();
        });
        if (!$config) {
            return false;
        }
        
        Config::set('database.connections.aim_app.driver', $config['db_driver']);
        Config::set('database.connections.aim_app.host', $config['db_host']);
        Config::set('database.connections.aim_app.port', $config['db_port']);
        Config::set('database.connections.aim_app.database', $config['db_database']);
        Config::set('database.connections.aim_app.username', $config['db_username']);
        Config::set('database.connections.aim_app.port', $config['db_port']);
        
        return true; // IDEA: We might return some debug info
    }

}
