<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ad;
use App\Models\ImageTool;
use App\Models\Sett;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{


    public function edit(Request $request)
    {
        $setting = Setting::query()->firstOrFail();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::query()->firstOrFail();
        $rules = [
            'email' => 'nullable|email',
            'mobile' => 'nullable|digits_between:8,14',
            'whatsapp' => 'nullable|string',
            'google_store' => 'nullable|string|url',
            'apple_store' => 'nullable|string|url',
            'facebook' => 'nullable|string|url',
            'twitter' => 'nullable|string|url',
            'instagram' => 'nullable|string|url',
            'linkedin' => 'nullable|string|url',
            'snapchat' => 'nullable|string|url',
        ];
        $this->validate($request, $rules);
        $data = $request->only('email', 'mobile', 'facebook', 'twitter', 'instagram', 'linkedin',
            'google_store', 'apple_store', 'mobile', 'whatsapp', 'snapchat');
        $setting->update($data);
        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
    }

}
