<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = User::query()->find(auth()->id());
        $user->setAttribute('token', $user->createToken('user_api')->plainTextToken);
        return mainResponse(true, __('ok'), $user, [], 200);
    }

    public function update(Request $request)
    {
        $user_uuid = auth()->id();
        $user = User::query()->find($user_uuid);
        $rules = [
            'image' => 'nullable|image',
            'name' => 'nullable|string|max:255',
            'mobile' => 'nullable|unique:users,mobile,' . $user_uuid . ',uuid',
            'country_uuid' => 'nullable|exists:countries,uuid',
            'city_uuid' => 'nullable|exists:cities,uuid',
            'about' => 'nullable|max:255',
        ];
/*        if ($request->mobile) {
            $rules['current_password'] = 'required|hash_check:' . $user->getAttribute('password');
        }*/
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages());
        }

        $data = [];

        if ($request->name) {
            $data['name'] = $request->name;
        }
        if ($request->mobile) {
            $data['mobile'] = $request->mobile;
        }
        if ($request->whatsapp) {
            $data['whatsapp'] = $request->whatsapp;
        }
        if ($request->about) {
            $data['about'] = $request->about;
        }
        if ($request->country_uuid) {
            $data['country_uuid'] = $request->country_uuid;
        }
        if ($request->city_uuid) {
            $data['city_uuid'] = $request->city_uuid;
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        if (count($data)) {
            $user->update($data);
        }
        $user->setAttribute('token', $user->createToken('user_api')->plainTextToken);
        return mainResponse(true, __('ok'), $user, [], 200);

    }

    public function updatePassword(Request $request)
    {
        $user = User::query()->find(auth()->id());
        $rules = [
            'current_password' => 'required|hash_check:' . $user->getAttribute('password'),
            'password' => 'required|string|min:6',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }
        $user->update(['password' => bcrypt($request->get('password'))]);
        $user->setAttribute('token', $request->bearerToken());
        return mainResponse(true, __('ok'), $user, []);
    }

    public function forgotPassword(Request $request)
    {
        $rules = [
            'mobile' => 'required|exists:users',
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }
        $this->sendCodes($request->mobile, 'Your reset password code is ');
        return mainResponse(true, 'We sent reset code to your mobile', [], [], 200);
    }

    public function sendCodes($mobile, $message)
    {
        $code = str_replace('0', '', \Carbon\Carbon::now()->timestamp);
        $code = str_shuffle($code);
        $code = substr($code, 0, 6);
//      send_sms([$mobile], $message.$code);
        $code = 1234;
        VerificationCode::query()->where('mobile', $mobile)->delete();
        VerificationCode::query()->insert(['mobile' => $mobile, 'code' => bcrypt($code)]);
        return mainResponse(true, __('ok'), [], []);
    }

    public function resetPassword(Request $request)
    {
        $code = VerificationCode::query()->
        where('mobile', $request->mobile)->first();
        $rules = [
            'mobile' => 'required|exists:users',
            'code' => 'required|hash_check:' . @$code->code,
            'password' => 'required|string|min:6',
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }
        User::query()->where('mobile', $request->mobile)
            ->update(['password' => bcrypt($request->password)]);
        VerificationCode::query()->where('mobile', $request->mobile)->delete();
        return mainResponse(true, 'Your password reset successfully, please login', [], [], 200);
    }

    public function verifyCode(Request $request)
    {
        $mobile_code = VerificationCode::query()->
        where('mobile', $request->mobile)->first();
        $rules = [
            'mobile' => 'required|exists:verification_codes',
            'code' => 'required|hash_check:' . @$mobile_code->code,
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }
        $code = str_replace('0', '', \Carbon\Carbon::now()->timestamp);
        $code = 1234;
        $code = str_shuffle($code);
        $code = substr($code, 0, 6);
        VerificationCode::query()->
        where('mobile', $request->mobile)->update(['code' => bcrypt($code)]);

        return mainResponse(true, __('ok'), $code, []);
    }


}
