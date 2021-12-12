<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('admin.cities.index', compact('countries'));
    }

    public function store(Request $request)
    {
        $rules = [
            'country_uuid' => 'required|exists:countries,uuid',
        ];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:255';
        }
        $this->validate($request, $rules);
        $data = $request->only('country_uuid');
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        City::query()->create($data);
        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_added'));
        return redirect('admin/cities');
    }

    public function update($id, Request $request)
    {
        $city = City::query()->find($id);
        $rules = [
            'country_uuid' => 'required|exists:countries,uuid',
        ];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:255';
        }
        $this->validate($request, $rules);
        $data = $request->only('country_uuid');
        foreach (locales() as $key => $language) {
            if ($request->get('name_' . $key)) {
                $data['name'][$key] = $request->get('name_' . $key);
            }
        }
        $city->update($data);
        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_edited'));
        return redirect()->back();
    }

    public function destroy($uuid)
    {
        try {
            City::query()->whereIn('uuid', explode(',', $uuid))->delete();
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
        return response()->json(['status' => true]);
    }

    public function indexTable(Request $request)
    {
        $cities = City::query();
        return Datatables::of($cities)
            ->filter(function ($query) use ($request) {
                if ($request->name) {
                    $locale = app()->getLocale();
                    $query->where("name->$locale", 'Like', "%" . $request->name . "%");
                }
                if ($request->country_uuid) {
                    $query->where('country_uuid', $request->country_uuid);
                }
            })->addColumn('action', function ($city) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $city->uuid . '" ';
                $data_attr .= 'data-country_uuid="' . $city->country_uuid . '" ';
                foreach(locales() as $key => $value){
                    $data_attr .= 'data-name_'.$key.'="'.$city->getTranslation('name', $key).'" ';
                }

                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $city->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })->make(true);
    }

}
