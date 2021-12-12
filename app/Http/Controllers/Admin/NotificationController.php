<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        $countries = Country::query()->with('cities')->get();
        return view('admin.notifications.index', compact('countries'));

    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'country_uuid' => 'nullable',
            'city_uuid' => 'nullable',
            'image' => 'required|image',
        ];

        $this->validate($request, $rules);

        $data = $request->only('title', 'content', 'country_uuid', 'city_uuid');
        $data['is_all'] = 1;
        if ($request->users) {
            $data['is_all'] = 0;
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }
        $notification = Notification::query()->create($data);
        $users = User::query();

        if ($request->users) {
            $users->whereIn('uuid', $request->users);
        }
        if ($request->country_uuid) {
            $users->where('country_uuid', $request->country_uuid);
        }
        if ($request->city_uuid) {
            $users->where('city_uuid', $request->city_uuid);
        }
        $users = $users->pluck('uuid')->toArray();
        $notification->users()->sync($users);

        $android_tokens = UserDevice::query()->where('user_uuid', $users)
            ->where('device', 'android')->pluck('token')->toArray();
        $ios_tokens = UserDevice::query()->where('user_uuid', $users)
            ->where('device', 'ios')->pluck('token')->toArray();
        if ($android_tokens) {
            fcmNotification($android_tokens, $request->title, $request->message, $request->message, 'general', 'android');
        }
        if ($ios_tokens) {
            fcmNotification($android_tokens, $request->title, $request->message, $request->message, 'general', 'ios');
        }


        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_added'));

        return redirect('user/notifications');
    }

    public function destroy($id, Request $request)
    {
        $notifications = Notification::query()->whereIn('uuid', explode(',', $id))->delete();
        return response()->json(['status' => true]);
    }

    public function indexTable(Request $request)
    {
        $notifications = Notification::query()->orderByDesc('id');
        return Datatables::of($notifications)
            ->filter(function ($query) use ($request) {
                if ($request->get('title')) {
                    $query->where('title', 'like', "%{$request->get('title')}%");
                }
            })->addColumn('action', function ($notification) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $notification->uuid . '" ';
                $data_attr .= 'data-title="' . $notification->title . '" ';
                $data_attr .= 'data-content="' . $notification->content . '" ';
                $data_attr .= 'data-country_uuid="' . $notification->country_uuid . '" ';
                $data_attr .= 'data-city_uuid="' . $notification->city_uuid . '" ';
                $data_attr .= 'data-is_all="' . $notification->is_all . '" ';
                $data_attr .= 'data-image="' . $notification->image . '" ';
                if (!$notification->is_all) {
                    $data_attr .= 'data-user_uuids="' . implode('___', $notification->users->pluck('uuid')->toArray()) . '"';
                    $data_attr .= 'data-user_names="' . implode('___', $notification->users->pluck('name')->toArray()) . '"';
                } else {
                    $data_attr .= 'data-user_uuids=""';
                    $data_attr .= 'data-user_names=""';
                }
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('show') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $notification->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })
//            ->editColumn('id', 'ID: {{$id}}')

            ->make(true);
    }

}
