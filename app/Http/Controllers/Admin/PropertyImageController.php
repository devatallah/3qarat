<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImageProperty;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PropertyImageController extends Controller
{

    public function index(Request $request)
    {
        $property = Property::query()->find($request->property_uuid);
        if (!$property) {
            return redirect('admin/properties');
        }
        return view('admin.property_images.index', compact('property'));

    }

    public function store(Request $request)
    {
        $rules = [
            'property_uuid' => 'required|exists:properties,uuid',
            'images' => 'required|array',
            'image' => 'required|image'
        ];
        $this->validate($request, $rules);
        if ($request->images) {
            foreach ($request->images as $image) {
                $image = $image->store('public');
                ImageProperty::query()->create(['property_uuid' => $request->property_uuid, 'image' => $image]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_added'));

        return redirect('property_images');
    }

    public function update(ImageProperty $property_image, Request $request)
    {
        $rules = [
            'image' => 'required|image'
        ];
        $this->validate($request, $rules);
        $data = [];
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }

        $property_image->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_edited'));

        return redirect()->back();
    }

    public function destroy($uuid, Request $request)
    {
        $property_images = ImageProperty::query()->whereIn('uuid', explode(',', $uuid))->delete();
        return response()->json(['status' => true]);
    }

    public function indexTable(Request $request)
    {
        $property_images = ImageProperty::query()->where('property_uuid', $request->property_uuid)
            ->orderByDesc('id');
        return Datatables::of($property_images)
            ->filter(function ($query) use ($request) {
            })->addColumn('action', function ($property_image) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $property_image->uuid . '" ';
                $data_attr .= 'data-image="' . $property_image->image . '" ';
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $property_image->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })->make(true);
    }

}
