<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Property;
use App\Models\Review;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $countries = Country::query()->with('cities')->get();
        return view('admin.users.index', compact('countries'));

    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'mobile' => 'string|digits_between:8,14|unique:users',
            'password' => 'nullable|string|min:6',
            'country_uuid' => 'required',
            'city_uuid' => 'required',
            'is_agent' => 'required',
            'can_comment' => 'required',
            'about' => 'required',
            'image' => 'nullable|image',
        ];

        $this->validate($request, $rules);

        $data = $request->only('name', 'email', 'mobile', 'country_uuid', 'city_uuid', 'is_agent', 'about', 'can_comment');
        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }
        User::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_added'));

        return redirect('user/users');
    }

    public function show($id, Request $request)
    {
        $user = User::query()->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function update($id, Request $request)
    {
        $user = User::query()->find($id);
        $old_is_agent = $user->is_agent;
        $new_is_agent = $request->is_agent;
        $old_can_comment = $user->can_comment;
        $new_can_comment = $request->can_comment;
        $rules = [
            'name' => 'required|string|max:255',
            'mobile' => 'string|digits_between:8,14|unique:users,mobile,' . $id.',uuid',
            'password' => 'nullable|string|min:6',
            'country_uuid' => 'required',
            'city_uuid' => 'required',
            'is_agent' => 'required',
            'can_comment' => 'required',
            'about' => 'required',
            'image' => 'nullable|image',
        ];

        $this->validate($request, $rules);

        $data = $request->only('name', 'email', 'mobile', 'country_uuid', 'city_uuid', 'is_agent', 'about', 'can_comment');

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }

        $user->update($data);

        $android_tokens = UserDevice::query()->where('user_uuid', $user->uuid)
            ->where('device', 'android')->pluck('token')->toArray();
        $ios_tokens = UserDevice::query()->where('user_uuid', $user->uuid)
            ->where('device', 'ios')->pluck('token')->toArray();
        if ($old_is_agent <> $new_is_agent){
            if ($new_is_agent){
                $message = __('you_are_an_agent');
                $type = 'you_are_agent';
            }else{
                $message = __('you_are_not_an_agent');
                $type = 'you_are_not_agent';
            }
            if ($android_tokens){
                fcmNotification($android_tokens, $message, $message, $message, $type, 'android');
            }
            if ($ios_tokens){
                fcmNotification($ios_tokens, $message, $message, $message, $type, 'ios');
            }
        }
        if ($old_can_comment <> $new_can_comment){
            if ($new_can_comment){
                $message = __('you_can_comment');
                $type = 'you_can_comment';
            }else{
                $message = __('you_can_not_comment');
                $type = 'you_can_not_comment';
            }
            if ($android_tokens){
                fcmNotification($android_tokens, $message, $message, $message, $type, 'android');
            }
            if ($ios_tokens){
                fcmNotification($ios_tokens, $message, $message, $message, $type, 'ios');
            }
        }
        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_edited'));

        return redirect()->back();
    }

    public function destroy($id, Request $request)
    {
        $users = User::query()->whereIn('uuid', explode(',', $id))->get();
        foreach ($users as $user) {
            $time = Carbon::now()->timestamp;
//            $user->email = $time.'deleted_'.$user->email;
            $user->mobile = $time.'_deleted_'.$user->mobile;
            $user->name = $time.'_deleted_'.$user->name;
            $user->save();
            $user->delete();
        }
        Property::query()->whereIn('user_uuid', explode(',', $id))->delete();
        Review::query()->whereIn('user_uuid', explode(',', $id))->delete();
        return response()->json(['status' => true]);
    }

    public function users(Request $request)
    {
        return view('layout.app');

    }


    public function indexTable(Request $request)
    {
        $users = User::query()->orderByDesc('id');
        return Datatables::of($users)
            ->filter(function ($query) use ($request) {
                if ($request->get('name')) {
                    $query->where('name', 'like', "%{$request->get('name')}%");
                }
                if ($request->country_uuid) {
                    $query->where('country_uuid', $request->country_uuid);
                }
                if ($request->city_uuid) {
                    $query->where('city_uuid', $request->city_uuid);
                }
                if ($request->filled('is_agent')) {
                    $query->where('is_agent', $request->is_agent);
                }
                if ($request->get('mobile')) {
                    $query->where('mobile', 'like', "%{$request->get('mobile')}%");
                }

//                $request->merge(['length' => -1]);
            })->addColumn('action', function ($user) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $user->uuid . '" ';
                $data_attr .= 'data-name="' . $user->name . '" ';
                $data_attr .= 'data-email="' . $user->email . '" ';
                $data_attr .= 'data-mobile="' . $user->mobile . '" ';
                $data_attr .= 'data-password="' . $user->password . '" ';
                $data_attr .= 'data-country_uuid="' . $user->country_uuid . '" ';
                $data_attr .= 'data-city_uuid="' . $user->city_uuid . '" ';
                $data_attr .= 'data-is_agent="' . $user->is_agent . '" ';
                $data_attr .= 'data-can_comment="' . $user->can_comment . '" ';
                $data_attr .= 'data-about="' . $user->about . '" ';
                $data_attr .= 'data-image="' . $user->image . '" ';
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $user->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })
//            ->editColumn('id', 'ID: {{$id}}')

            ->make(true);
    }

}
