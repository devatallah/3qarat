<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Feature;
use App\Models\ImageProperty;
use App\Models\Property;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PropertyController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::all();
        $countries = Country::all();
        $services = Service::all();
        $features = Feature::all();
        return view('admin.properties.index', compact('categories', 'countries', 'services', 'features'));

    }

    public function store(Request $request)
    {
        $rules = [
            'owner_name' => 'nullable|string|max:255|min:3',
            'category_uuid' => 'required',
            'price' => 'nullable',
            'service_uuid' => 'required',
            'features' => 'nullable|array',
            'features.*' => 'required',
            'area' => 'required|numeric',
            'bathrooms' => 'nullable|integer',
            'rooms' => 'nullable|integer',
            'mobile' => 'nullable|digits_between:8,14',
            'whatsapp' => 'nullable|digits_between:8,14',
            'lat' => 'required|string',
            'lng' => 'required|string',
            'image' => 'required|image',
            'images' => 'required|array',
            'images.*' => 'required|image',
            'country_uuid' => 'required',
            'city_uuid' => 'required',
        ];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:255';
            $rules['description_' . $key] = 'required|string';
        }
        $this->validate($request, $rules);
        $data = $request->only(['owner_name', 'category_uuid', 'price', 'service_uuid', 'area', 'bathrooms', 'rooms',
            'mobile', 'whatsapp', 'lat', 'lng', 'image', 'country_uuid', 'city_uuid', 'user_uuid']);
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
            $data['description'][$key] = $request->get('description_' . $key);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }
        $data['code'] = getGeneratedCode('Property', 'code');
        $property = Property::query()->create($data);
        if ($request->features) {
            $property->features()->sync($request->features);
        }
        foreach ($request->images as $image) {
            $image = $image->store('public');
            ImageProperty::query()->create(['property_uuid' => $property->uuid, 'image' => $image]);
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_added'));

        return redirect('properties');
    }

    public function update(Property $property, Request $request)
    {
        $rules = [
            'owner_name' => 'nullable|string|max:255|min:3',
            'category_uuid' => 'required',
            'price' => 'nullable',
            'service_uuid' => 'required',
            'features' => 'nullable|array',
            'features.*' => 'required',
            'area' => 'required|numeric',
            'bathrooms' => 'nullable|integer',
            'rooms' => 'nullable|integer',
            'mobile' => 'nullable|digits_between:8,14',
            'whatsapp' => 'nullable|digits_between:8,14',
            'lat' => 'required|string',
            'lng' => 'required|string',
            'image' => 'nullable|image',
            'images' => 'nullable|array',
            'images.*' => 'required|image',
            'country_uuid' => 'required',
            'city_uuid' => 'required',
        ];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:255';
            $rules['description_' . $key] = 'required|string';
        }
        $this->validate($request, $rules);
        $data = $request->only(['owner_name', 'category_uuid', 'price', 'service_uuid', 'area', 'bathrooms', 'rooms',
            'mobile', 'whatsapp', 'lat', 'lng', 'image', 'country_uuid', 'city_uuid', 'user_uuid']);
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
            $data['description'][$key] = $request->get('description_' . $key);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }
        $property->update($data);
        if ($request->features) {
            $property->features()->sync($request->features);
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_edited'));

        return redirect()->back();
    }

    public function destroy($uuid, Request $request)
    {
        $properties = Property::query()->whereIn('uuid', explode(',', $uuid))->delete();
        return response()->json(['status' => true]);
    }

    public function indexTable(Request $request)
    {
        $properties = Property::query()->orderByDesc('id');
        return Datatables::of($properties)
            ->filter(function ($query) use ($request) {
                $name = (urlencode($request->get('name')));
                if ($request->get('owner_name')) {
                    $query->where('owner_name', 'like', "%$request->owner_name%");
                }
                if ($request->get('mobile')) {
                    $query->where('mobile', 'like', "%$request->mobile%");
                }
                if ($request->get('name')) {
                    $query->where('name->'.locale(), 'like', "%$request->name%");
                }
                if ($request->get('code')) {
                    $query->where('code', 'like', "%$request->code%");
                }
                if ($request->get('user_uuid')) {
                    $query->where('user_uuid', $request->user_uuid);
                }
                if ($request->get('country_uuid')) {
                    $query->where('country_uuid', $request->country_uuid);
                }
                if ($request->get('city_uuid')) {
                    $query->where('city_uuid', $request->city_uuid);
                }
                if ($request->get('category_uuid')) {
                    $query->where('category_uuid', $request->category_uuid);
                }
                if ($request->get('service_uuid')) {
                    $query->where('service_uuid', $request->service_uuid);
                }
            })->addColumn('action', function ($property) {
//                dd($property->features);
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $property->uuid . '" ';
                $data_attr .= 'data-owner_name="' . $property->owner_name . '" ';
                $data_attr .= 'data-category_uuid="' . $property->category_uuid . '" ';
                $data_attr .= 'data-service_uuid="' . $property->service_uuid . '" ';
                $data_attr .= 'data-country_uuid="' . $property->country_uuid . '" ';
                $data_attr .= 'data-city_uuid="' . $property->city_uuid . '" ';
                $data_attr .= 'data-price="' . $property->price . '" ';
                $data_attr .= 'data-area="' . $property->area . '" ';
                $data_attr .= 'data-rooms="' . $property->rooms . '" ';
                $data_attr .= 'data-bathrooms="' . $property->bathrooms . '" ';
                $data_attr .= 'data-mobile="' . $property->mobile . '" ';
                $data_attr .= 'data-whatsapp="' . $property->whatsapp . '" ';
                $data_attr .= 'data-image="' . $property->image . '" ';
                $data_attr .= 'data-lat="' . $property->lat . '" ';
                $data_attr .= 'data-lng="' . $property->lng . '" ';
                $data_attr .= 'data-user_uuid="' . $property->user_uuid . '" ';
                $data_attr .= 'data-user_name="' . $property->user_name . '" ';
                $feature_uuids = implode(',',$property->features()->pluck('uuid')->toArray()).",";
                $data_attr .= 'data-features="' . $feature_uuids . '"';
                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-name_' . $key . '="' . $property->getTranslation('name', $key) . '" ';
                    $data_attr .= 'data-description_' . $key . '="' . $property->getTranslation('description', $key) . '" ';
                }
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                $string .= ' <a href="'.url("admin/property_images?property_uuid=$property->uuid").'" class="btn btn-sm btn-outline-primary">' . __('images') . '</a>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $property->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })->make(true);
    }

}
