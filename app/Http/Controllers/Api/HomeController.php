<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category;
use App\Models\City;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Feature;
use App\Models\ImageProperty;
use App\Models\Page;
use App\Models\Property;
use App\Models\ReviewReport;
use App\Models\Review;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{

    public function features(Request $request)
    {
        $features = Feature::query()->get();
        return mainResponse(true, __('api.ok'), $features, []);
    }

    public function services(Request $request)
    {
        $services = Service::query()->get();
        return mainResponse(true, __('api.ok'), $services, []);
    }

    public function sliders(Request $request)
    {
        $sliders = Slider::query()->get();
        return mainResponse(true, __('api.ok'), $sliders, []);
    }

    public function categories(Request $request)
    {
        $categories = Category::query()->get();
        return mainResponse(true, __('api.ok'), $categories, []);
    }

    public function countries(Request $request)
    {
        $countries = Country::query();

        if ($request->with_cities) {
            $countries->with('cities');
        }
        $countries = $countries->get();
        return mainResponse(true, __('ok'), $countries, [], 200);
    }

    public function cities(Request $request)
    {
        $cities = City::query()->where('country_uuid', $request->country_uuid)->get();
        return mainResponse(true, __('ok'), $cities, [], 200);
    }

    public function userDetails(Request $request)
    {
        $property = Property::query()->find($request->property_uuid);
        if ($property) {
            if ($property->user_uuid) {
                $user = User::query()->find(@$property->user_uuid)->only('name', 'image', 'city_name', 'country_name', 'whatsapp', 'mobile', 'about');
                $properties = Property::query()->where('user_uuid', @$property->user_uuid)->paginate();
            } else {
                $settings = Setting::query()->first();
                $user = new User();
                $user->name = 'Aqar App';
                $user->image = asset('placeholder.jpg');
                $user->city_name = @$settings->city_name;
                $user->country_name = @$settings->country_name;
                $user->whatsapp = @$settings->whatsapp;
                $user->mobile = @$settings->mobile;
                $user->about = 'Aqar App';
                $properties = Property::query()->whereNull('user_uuid')->paginate();

            }
        } else {
            $user = (object)[];
            $properties = [];

        }
        return mainResponse(true, __('ok'), compact('properties', 'user'), [], 200);
    }

    public function addProperty(Request $request)
    {
        $rules = [
            'owner_name' => 'nullable|string|max:255|min:3',
            'name_en' => 'required|string|max:255|min:3',
            'name_ar' => 'required|string|max:255|min:3',
            'category_uuid' => 'required',
            'price' => 'nullable',
            'service_uuid' => 'required',
            'features' => 'nullable|array',
            'features.*' => 'required',
            'area' => 'required|numeric',
            'bathrooms' => 'nullable|integer',
            'rooms' => 'nullable|integer',
            'mobile' => 'nullable',
            'whatsapp' => 'nullable',
            'lat' => 'required|string',
            'lng' => 'required|string',
            'image' => 'required|image',
            'images' => 'nullable|array',
            'images.*' => 'required|image',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'country_uuid' => 'required',
            'city_uuid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }
        $data = $request->only(['owner_name', 'category_uuid', 'price', 'service_uuid', 'area', 'bathrooms', 'rooms',
            'mobile', 'whatsapp', 'lat', 'lng', 'image', 'country_uuid', 'city_uuid']);
        $data['name'] = ['en' => $request->name_en, 'ar' => $request->name_ar];
        $data['description'] = ['en' => $request->description_en, 'ar' => $request->description_ar];
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }
        if ($request->hasFile('360_image')) {
            $image_360 = $request->file('360_image')->store('public');
            $data['360_image'] = $image_360;
        }
        $data['user_uuid'] = auth()->id();
        $data['code'] = getGeneratedCode('Property', 'code');
        $property = Property::query()->create($data);
        if ($request->features) {
            $property->features()->sync($request->features);
        }
        foreach ($request->images as $image) {
            $image = $image->store('public');
            ImageProperty::query()->create(['property_uuid' => $property->uuid, 'image' => $image]);
        }

        return mainResponse(true, __('ok'), $property, [], 200);
    }

    public function editProperty(Request $request)
    {
        $rules = [
            'owner_name' => 'nullable|string|max:255|min:3',
            'name_en' => 'nullable|string|max:255|min:3',
            'name_ar' => 'nullable|string|max:255|min:3',
            'category_uuid' => 'nullable',
            'price' => 'nullable|string|max:255|min:3',
            'service_uuid' => 'nullable',
            'features' => 'nullable|array',
            'features.*' => 'required',
            'area' => 'nullable|numeric',
            'bathrooms' => 'nullable|integer',
            'rooms' => 'nullable|integer',
            'mobile' => 'nullable',
            'whatsapp' => 'nullable',
            'lat' => 'nullable|string',
            'lng' => 'nullable|string',
            'image' => 'nullable|image',
            'images' => 'nullable|array',
            'images.*' => 'required|image',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'country_uuid' => 'nullable',
            'city_uuid' => 'nullable',
            'status' => 'nullable|in:active,inactive',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }
        $data = [];
        $property = Property::query()->where('user_uuid', auth()->id())->find($request->property_uuid);
        if ($property) {
            if ($request->owner_name) {
                $data['owner_name'] = $request->owner_name;
            }
            if ($request->name_en) {
                $data['name']['en'] = $request->name_en;
            }
            if ($request->name_ar) {
                $data['name']['ar'] = $request->name_ar;
            }
            if ($request->category_uuid) {
                $data['category_uuid'] = $request->category_uuid;
            }
            if ($request->price) {
                $data['price'] = $request->price;
            }
            if ($request->service_uuid) {
                $data['service_uuid'] = $request->service_uuid;
            }
            if ($request->area) {
                $data['area'] = $request->area;
            }
            if ($request->bathrooms) {
                $data['bathrooms'] = $request->bathrooms;
            }
            if ($request->rooms) {
                $data['rooms'] = $request->rooms;
            }
            if ($request->mobile) {
                $data['mobile'] = $request->mobile;
            }
            if ($request->whatsapp) {
                $data['whatsapp'] = $request->whatsapp;
            }
            if ($request->lat) {
                $data['lat'] = $request->lat;
            }
            if ($request->lng) {
                $data['lng'] = $request->lng;
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('public');
                $data['image'] = $image;
            }
            if ($request->description_en) {
                $data['description']['en'] = $request->description_en;
            }
            if ($request->description_ar) {
                $data['description']['ar'] = $request->description_ar;
            }
            if ($request->country_uuid) {
                $data['country_uuid'] = $request->country_uuid;
            }
            if ($request->city_uuid) {
                $data['city_uuid'] = $request->city_uuid;
            }
            if ($request->status) {
                $data['status'] = $request->status;
            }
            if (count($data)) {
                $property->update($data);
            }
            if ($request->features) {
                $property->features()->sync($request->features);
            }
            if ($request->deleted_images_uuid) {
                if (is_array($request->deleted_images_uuid)) {
                    ImageProperty::query()->whereIn('uuid', $request->deleted_images_uuid)->delete();
                }
            }
            if ($request->images) {
                foreach ($request->images as $image) {
                    $image = $image->store('public');
                    ImageProperty::query()->create(['property_uuid' => $property->uuid, 'image' => $image]);
                }

            }
        }
        $property = Property::query()->with(['category', 'country', 'city', 'service', 'features', 'images'])
            ->find($request->property_uuid);
        $property->reviews = Review::query()->where('property_uuid', $request->property_uuid)->limit(2)->get();

        return mainResponse(true, __('ok'), $property, [], 200);
    }

    public function home(Request $request)
    {
        $recent_added = Property::query()->where('status', 'active')->orderByDesc('id')->limit(5)->get();
        $near_you = Property::query()->where('status', 'active')->orderByDesc('id')->limit(5)->get();
        $ad = Ad::query()->where('start_date', '<=', date('Y-m-d'))
            ->where('end_date', '>=', date('Y-m-d'))
            ->where('place', 'main')->inRandomOrder()->first();
        $user = User::query()->find(auth('sanctum')->id());
        if (!$user) {
            $user = (object)[];
        }
        return mainResponse(true, __('ok'), compact('recent_added', 'near_you', 'ad', 'user'), [], 200);
    }

    public function properties(Request $request)
    {
        $ad = 0;
        $properties = Property::query()->where('status', 'active');

        if ($request->search) {
            $properties->where(function ($q) use ($request) {
                $q->where('name->en', 'like', "%$request->search%")
                    ->orWhere('name->ar', 'like', "%$request->search%")
                    ->orWhere('description->en', 'like', "%$request->search%")
                    ->orWhere('description->ar', 'like', "%$request->search%")
                    ->orWhere('code', 'like', "%$request->search%");
            });
        }
        if ($request->categories_uuid) {
            $properties->whereIn('category_uuid', $request->categories_uuid);
        }
        if ($request->country_uuid) {
            $properties->where('country_uuid', $request->country_uuid);
        }
        if ($request->cities_uuid) {
            $properties->whereIn('city_uuid', $request->cities_uuid);
        }
        if ($request->service_uuid) {
            $properties->where('service_uuid', $request->service_uuid);
        }
        if ($request->features_uuid) {
            $properties->whereHas('features', function ($q) use ($request) {
                $q->whereIn('feature_uuid', $request->features_uuid);
            });
        }
        if ($request->price_from && $request->price_to) {
            $properties->whereBetween('price', [$request->price_from, $request->price_to]);
        }
        if ($request->area_from && $request->area_to) {
            $properties->whereBetween('area', [$request->area_from, $request->area_to]);
        }
        if ($request->sort_by === 'date') {
            $ad = Ad::query()->where('start_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->where('place', 'recent_added')->inRandomOrder()->first();
            $properties->orderByDesc('created_at');
        }
        if ($request->lat && $request->lng) {
            $ad = Ad::query()->where('start_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->where('place', 'nearby')->inRandomOrder()->first();
        }
        if (!$ad) {
            $ad = Ad::query()->where('start_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->where('place', 'main')->inRandomOrder()->first();
        }
        $properties = $properties->paginate();
        return mainResponse(true, __('ok'), compact('properties', 'ad'), [], 200);
    }

    public function myProperties(Request $request)
    {
        $user_uuid = auth()->id();
        $properties = Property::query()->where('user_uuid', $user_uuid);

        if ($request->search) {
            $properties->where(function ($q) use ($request) {
                $q->where('name->en', 'like', "%$request->search%")
                    ->orWhere('name->ar', 'like', "%$request->search%")
                    ->orWhere('description->en', 'like', "%$request->search%")
                    ->orWhere('description->ar', 'like', "%$request->search%");
            });
        }
        if ($request->categories_uuid) {
            $properties->whereIn('category_uuid', $request->categories_uuid);
        }
        if ($request->country_uuid) {
            $properties->where('country_uuid', $request->country_uuid);
        }
        if ($request->city_uuid) {
            $properties->where('city_uuid', $request->city_uuid);
        }
        if ($request->service_uuid) {
            $properties->where('service_uuid', $request->service_uuid);
        }
        if ($request->status) {
            $properties->where('status', $request->status);
        }
        if ($request->features_uuid) {
            $properties->whereHas('features', function ($q) use ($request) {
                $q->whereIn('feature_uuid', $request->features_uuid);
            });
        }
        if ($request->price_from && $request->price_to) {
            $properties->whereBetween('price', [$request->price_from, $request->price_to]);
        }
        if ($request->area_from && $request->area_to) {
            $properties->whereBetween('area', [$request->area_from, $request->area_to]);
        }
        $properties = $properties->get();
        return mainResponse(true, __('ok'), $properties, [], 200);
    }

    public function favorites(Request $request)
    {
        $user = User::query()->find(auth()->id());
        $favorites = $user->favorites;
        $ad = Ad::query()->where('start_date', '<=', date('Y-m-d'))
            ->where('end_date', '>=', date('Y-m-d'))
            ->where('place', 'favorite')->inRandomOrder()->first();

        return mainResponse(true, __('ok'), compact('favorites', 'ad'), [], 200);
    }

    public function deleteProperty(Request $request)
    {
        Property::query()->where('user_uuid', auth()->id())->find($request->property_uuid)->delete();
        return mainResponse(true, __('ok'), [], [], 200);

    }

    public function property(Request $request)
    {
        $property = Property::query()->with(['category', 'country', 'city', 'service', 'features', 'images'])
            ->find($request->property_uuid);
        $property->reviews = Review::query()->where('property_uuid', $request->property_uuid)->limit(2)->get();

        $ad = Ad::query()->where('start_date', '<=', date('Y-m-d'))
            ->where('end_date', '>=', date('Y-m-d'))
            ->where('place', 'property_details')->inRandomOrder()->first();
        return mainResponse(true, __('ok'), compact('property', 'ad'), [], 200);
    }


    public function toggleFavorite(Request $request)
    {
        User::query()->find(auth()->id())->favorites()->toggle([$request->property_uuid]);
        $property = Property::query()->find($request->property_uuid);
        return mainResponse(true, __('ok'), $property, [], 200);
    }

    public function addReview(Request $request)
    {
        $rules = [
            'comment' => 'required|string|max:255|min:3',
            'rating' => 'required',
            'property_uuid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }
        $data = $request->only(['comment', 'rating', 'property_uuid']);
        $data['user_uuid'] = auth()->id();
        $review = Review::query()->create($data);

        return mainResponse(true, __('ok'), $review, [], 200);
    }
    public function reportReview(Request $request)
    {
        $rules = [
            'report' => 'required|string|max:255|min:3',
            'review_uuid' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }
        $data = $request->only('report', 'review_uuid');
        $data['user_uuid'] = auth('sanctum')->id();
        ReviewReport::query()->create($data);
        return mainResponse(true, __('ok'), [], [], 200);
    }

    public function reviews(Request $request)
    {
        $property = Property::query()->find($request->property_uuid);
        $reviews_count = $property->reviews_count;
        $rating = $property->rating;
        $reviews = Review::query()->where('property_uuid', $request->property_uuid)->paginate();
        return mainResponse(true, __('ok'), compact('reviews', 'reviews_count', 'rating'), [], 200);
    }

    public function user($id)
    {
        $user = User::query()->with(['images', 'invitations'])->find($id);
        return mainResponse($user ? true : false, __('api.ok'), $user, []);
    }

    public function notifications(Request $request)
    {
        $user = User::query()->find(auth('sanctum')->id());
        $notifications = $user->notifications()->orderByDesc('id')->paginate();
        return mainResponse(true, __('api.ok'), $notifications, []);
    }

    public function sendMessage(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255|min:3',
            'mobile' => 'required',
            'message' => 'required|string|min:3',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }
        $data = $request->only(['name', 'mobile', 'message']);
        Contact::query()->create($data);
        return mainResponse(true, __('api.ok'), [], []);

    }

    public function userProfile(Request $request)
    {
        $user = User::query()->with(['images', 'invitations'])
            ->find(auth('user_api')->id());
        $user->update(['last_login_at' => Carbon::now()]);
        return mainResponse(true, __('api.ok'), $user, []);
    }

    public function appDetails()
    {
        $settings = Setting::query()->first();
        $about = @Page::query()->where('id', 1)->first()->page_content;
        $terms_use = @Page::query()->where('id', 2)->first()->page_content;
        $privacy_policy = @Page::query()->where('id', 3)->first()->page_content;
        $email = @$settings->email;
        $mobile = @$settings->mobile;
        $whatsapp = @$settings->whatsapp;
        $facebook = @$settings->facebook;
        $twitter = @$settings->twitter;
        $instagram = @$settings->instagram;
        $linkedin = @$settings->linkedin;
        $snapchat = @$settings->snapchat;
        $country_uuid = @$settings->country_uuid;
        $country_name = @$settings->country_name;
        $city_uuid = @$settings->city_uuid;
        $city_name = @$settings->city_name;
        return mainResponse(true, __('api.ok'), compact('about', 'terms_use', 'privacy_policy',
            'email', 'mobile', 'whatsapp', 'facebook', 'twitter', 'instagram', 'linkedin', 'snapchat',
            'country_uuid', 'country_name', 'city_uuid', 'city_name'), []);
    }
}
