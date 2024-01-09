<?php namespace App\Http\Controllers;

use Auth;
use Validator;
use Carbon\Carbon;
use App\Models\User;
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
            'username' => 'required_without:deviceId|min:4|max:255',
            'password' => 'required_without:deviceId|min:4|max:255',
            'deviceId' => 'required_without:username|min:4|max:255',
            'deviceToken' => 'required_without:username|min:4|max:255',
        ];

        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            return [
                'ok' => false,
                'message' => $validator->errors(),
            ];
        }

        if (isset($input['username'])) {
            $token = Str::random(64);
            $username = $input['username'];
            $password = $input['password'];
            if (Auth::attempt(['username' => $username, 'password' => $password])) {
                $user = Auth::user();
            } else {
                return [
                    'ok' => false,
                    'message' => 'User not found or password is incorrect',
                ];
            }
        }

        if (isset($input['deviceId'])) {
            $deviceId = $input['deviceId'];
            $token = $input['deviceToken'];

            $salt = config('app.salt');
            $authToken = sha1($token . $salt);
            
            $user = User::where('device_id', $deviceId)
                ->where('device_token', $authToken)
                ->first();
            if (!$user) {
                return [
                    'ok' => false,
                    'message' => 'User not found or token is incorrect',
                ];
            }
        }

        $salt = config('app.salt');
        $tokenWithSalt = sha1($token . $salt);

        $device = new Device;
        $device->client_id = $user->client_id;
        $device->user_id = $user->id;
        $device->auth_token = $tokenWithSalt;
        $device->active_till = Carbon::now()->addDays(30);
        $device->save();

        return [
            'ok' => true,
            'token' => $token,//no hash
            'maxScreenWidth' => $user->max_screen_width,
            'intervalScreenshot' => $user->interval_screenshot,
            'intervalLog' => $user->interval_log,
            'trackMode' => $user->track_mode,
            'isBackgroundApp' => $user->is_background_app,
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
