<?php namespace App\Http\Controllers;

use Auth;
use Validator;
use Carbon\Carbon;
use App\Models\Device;
use Illuminate\Http\Request;

class UserAuth extends Controller
{
    public function login(Request $request)
    {
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


        $token = sha1($user->id . $user->username . $user->password . time());
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
            'token' => $device->auth_token,
        ];
    }

    public function logout(Request $request)
    {
        $authToken = $request->header('Auth-Token', "undefined");
        $device = Device::where('auth_token', $authToken)->first();

        if (!$device) {
            return [
                'ok' => false,
                'message' => 'Device not found',
            ];
        }

        $device->delete();

        return [
            'ok' => true,
        ];
    }
}
