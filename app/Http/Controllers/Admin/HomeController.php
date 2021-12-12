<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $users_count = \App\Models\User::query()->count();
        $properties_count = \App\Models\Property::query()->count();
        $categories_count = \App\Models\Category::query()->count();
        $features_count = \App\Models\Feature::query()->count();
        $countries_count = \App\Models\Country::query()->count();
        $reviews_count = \App\Models\Review::query()->count();
        $services_count = \App\Models\Service::query()->count();
        $cities_count = \App\Models\City::query()->count();
        $properties = \App\Models\Property::query()->orderByDesc('created_at')->take(5)->get();
        return view('admin.home', compact('users_count', 'properties_count', 'categories_count',
            'features_count', 'countries_count', 'reviews_count','properties', 'cities_count', 'services_count'));
    }


    public function getUsers(Request $request)
    {
        $users = User::query()->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->q . '%')->orWhere('mobile', 'like', '%' . $request->q . '%');
        });
        $users = $users->get();
        $json = [];
        foreach ($users as $user) {
            $json[] = [
                'id' => $user->uuid,
                'text' => $user->name . "($user->mobile)",
            ];
        }
        return json_encode($json);
    }
    public function getProperties(Request $request)
    {
        $users = \App\Models\Property::query()->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->q . '%')->orWhere('code', 'like', '%' . $request->q . '%');
        });
        $users = $users->get();
        $json = [];
        foreach ($users as $user) {
            $json[] = [
                'id' => $user->uuid,
                'text' => $user->name . "($user->code)",
            ];
        }
        return json_encode($json);

    }

}
