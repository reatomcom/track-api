<?php namespace App\Http\Controllers;

use Auth;
use Validator;
use Carbon\Carbon;
use App\Models\Device;
use App\Models\ClientBase;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserAuth extends Controller
{
    public function login(Request $request)
    {
        ClientBase::clientConfig(1);
        
        $input = $request->all();

        $rules = [
            'username' => 'required|min:4|max:255',
            'password' => 'required|min:4|max:255'
        ];

        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            return [
                'ok' => false,
                'message' => $validator->errors(),
            ];
        }

        $username = $input['username'];
        $password = $input['password'];

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $user = Auth::user();
        } else {
            $user = null;
        }

        if (!$user) {
            return [
                'ok' => false,
                'message' => 'User not found or password is incorrect',
            ];
        }

        $token = Str::random(64);
        $salt = config('app.salt');
        $tokenWithSalt = sha1($token . $salt);

        $device = new Device;
        $device->client_id = $user->client_id;
        $device->user_id = $user->id;
        $device->auth_token = $tokenWithSalt;
        $device->active_till = Carbon::now()->addDays(30);
        $device->save();

        // TODOl; ja ir limits vec'āko device izmet 'ār'ā.

        return [
            'ok' => true,
            'token' => $token,//no hash
        ];
    }

    public function logout(Request $request)
    {
        $device = $request->device;

        $device->delete();

        return [
            'ok' => true,
        ];
    }
}
