<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Webpatser\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class FeatureController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.features.index');

    }

    public function store(Request $request)
    {
        $rules = [];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:255';
        }
        $this->validate($request, $rules);
        $data = [];
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('public');
            $data['icon'] = $icon;
        }

        Feature::query()->create($data);


        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_added'));

        return redirect('features');
    }

    public function update(Feature $feature, Request $request)
    {
        $rules = [];
        foreach (locales() as $key => $language) {
            $rules['name_' . $key] = 'required|string|max:255';
        }
        $this->validate($request, $rules);
        $data = [];
        foreach (locales() as $key => $language) {
            $data['name'][$key] = $request->get('name_' . $key);
        }
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon')->store('public');
            $data['icon'] = $icon;
        }

        $feature->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_edited'));

        return redirect()->back();
    }

    public function destroy($uuid, Request $request)
    {
        $features = Feature::query()->whereIn('uuid', explode(',', $uuid))->delete();
        return response()->json(['status' => true]);
    }

    public function indexTable(Request $request)
    {
        $features = Feature::query()->orderByDesc('id');
        return Datatables::of($features)
            ->filter(function ($query) use ($request) {
                $name = (urlencode($request->get('name')));
                if ($request->get('name')) {
                    $query->where('name->'.locale(), 'like', "%{$request->get('name')}%");
                }
            })->addColumn('action', function ($feature) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $feature->uuid . '" ';
                $data_attr .= 'data-icon="' . $feature->icon . '" ';
                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-name_' . $key . '="' . $feature->getTranslation('name', $key) . '" ';
                }
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $feature->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })->make(true);
    }

}
