<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        if ($request->social_id && $request->social_type) {
            if ($request->social_type == 'google' && !is_null($request->social_id)) {
                if ($request->email) {
                    $user = User::query()->where('email', $request->email)->first();
                    if ($user) {
                        if (is_null($user->google_id)) {
                            $user->update(['google_id' => $request->social_id, 'email_verified_at' => Carbon::now()]);
                        }
                    }
                }
                if (!$user_ = User::query()->where('google_id', $request->social_id)->first()) {
                    $user = User::query()->create(['google_id' => $request->social_id, 'email' => @$request->email,
                        'name' => @$request->name, 'email_verified_at' => Carbon::now(),
                    ]);
                }
                $user = User::query()->where('google_id', $request->social_id)->first();

            }
            if ($request->social_type == 'facebook' && !is_null($request->social_id)) {
                if ($request->email) {
                    $user = User::query()->where('email', $request->email)->orWhere('mobile', $request->mobile)->first();
                    if ($user) {
                        if (is_null($user->facebook_id)) {
                            $user->update(['facebook_id' => $request->social_id, 'email_verified_at' => Carbon::now()]);
                        }
                    }
                }
                if (!$user_ = User::query()->where('facebook_id', $request->social_id)->first()) {
                    $user = User::query()->create(['facebook_id' => $request->social_id, 'email' => @$request->email,
                        'name' => @$request->name, 'email_verified_at' => Carbon::now(),
                    ]);
                }
                $user = User::query()->where('facebook_id', $request->social_id)->first();

            }
            if ($request->social_type == 'apple' && !is_null($request->social_id)) {
                if ($request->email) {
                    $user = User::query()->where('email', $request->email)->first();
                    if ($user) {
                        if (is_null($user->apple_id)) {
                            $user->update(['apple_id' => $request->social_id, 'email_verified_at' => Carbon::now()]);
                        }
                    }
                }

                if (!$user_ = User::query()->where('apple_id', $request->social_id)->first()) {
                    $user = User::query()->create(['apple_id' => $request->social_id, 'email' => @$request->email,
                        'name' => @$request->name, 'email_verified_at' => Carbon::now(),
                    ]);
                }
                $user = User::query()->where('apple_id', $request->social_id)->first();

            }
            if ($user) {
                $user->setAttribute('token', $user->createToken('user_api')->plainTextToken);
                if ($request->token && $request->device) {
                    UserDevice::query()->updateOrCreate(
                        ['user_uuid' => $user->uuid, 'token' => $request->fcm_token, 'device' => $request->fcm_device],
                        ['user_uuid' => $user->uuid, 'token' => $request->fcm_token, 'device' => $request->fcm_device]);
                }
            }
            return mainResponse(true, 'api.ok', $user, []);

        }

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user_uuid = auth()->guard()->id();
            $this->clearLoginAttempts($request);
            $user = User::query()->find($user_uuid);
            UserDevice::query()->updateOrCreate(
                ['user_uuid' => $user->uuid, 'token' => $request->fcm_token, 'device' => $request->fcm_device],
                ['user_uuid' => $user->uuid, 'token' => $request->fcm_token, 'device' => $request->fcm_device]);
            $user->setAttribute('token', $user->createToken('user_api')->plainTextToken);
            return mainResponse(true, __('ok'), $user, [], 200);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validator(array $data)
    {
        $rules = [
            $this->username() => 'required',
            'password' => 'required|string',
            'fcm_token' => 'required|string|max:255',
            'fcm_device' => 'required|string|max:255',
        ];

        return Validator::make($data, $rules);
    }

    public function username()
    {
        return 'mobile';
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        return mainResponse(false, __('auth.throttle', ['seconds' => $seconds]), [], [], 200);
    }

    protected function attemptLogin(Request $request)
    {
        return auth()->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function credentials(\Illuminate\Http\Request $request)
    {
        if (filter_var($request->get('mobile'), FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->get('mobile'), 'password' => $request->get('password'), 'deleted_at' => null];
        }
        return ['mobile' => $request->get('mobile'), 'password' => $request->get('password'), 'deleted_at' => null];
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return mainResponse(false, __('auth.failed'), (object)[], [], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return mainResponse(true, __('ok'), [], [], 200);
    }

    protected function guard()
    {
        return Auth::guard();
    }

}
