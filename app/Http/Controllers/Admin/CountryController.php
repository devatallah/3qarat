<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Webpatser\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.countries.index');

    }

    public function store(Request $request)
    {
        $rules = [
            'order' => 'required|unique:countries'
        ];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:255';
        }
        $this->validate($request, $rules);
        $data = $request->only('order');
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }

        Country::query()->create($data);


        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_added'));

        return redirect('countries');
    }

    public function update(Country $country, Request $request)
    {
        $rules = [
            'order' => 'required|unique:countries'
        ];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:255';
        }
        $this->validate($request, $rules);
        $data = $request->only('order');
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }

        $country->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_edited'));

        return redirect()->back();
    }

    public function destroy($uuid, Request $request)
    {
        $countries = Country::query()->whereIn('uuid', explode(',', $uuid))->delete();
        return response()->json(['status' => true]);
    }

    public function indexTable(Request $request)
    {
        $countries = Country::query()->orderBy('order');
        return Datatables::of($countries)
            ->filter(function ($query) use ($request) {
                $name = (urlencode($request->get('name')));
                if ($request->get('name')) {
                    $query->where('name->'.locale(), 'like', "%{$request->get('name')}%");
                }
            })->addColumn('action', function ($country) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $country->uuid . '" ';
                $data_attr .= 'data-image="' . $country->image . '" ';
                $data_attr .= 'data-order="' . $country->order . '" ';
                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-name_' . $key . '="' . $country->getTranslation('name', $key) . '" ';
                }
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $country->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })->make(true);
    }

}
