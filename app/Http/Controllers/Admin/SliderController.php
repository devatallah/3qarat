<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Webpatser\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.sliders.index');

    }

    public function store(Request $request)
    {
        $rules = [];
        foreach (locales() as $key => $language) {
            $rules['text_' . $key] = 'required|string|max:255';
        }
        $this->validate($request, $rules);
        $data = [];
        foreach (locales() as $key => $language) {
            $data['text'][$key] = $request->get('text_' . $key);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }

        Slider::query()->create($data);


        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_added'));

        return redirect('sliders');
    }

    public function update(Slider $slider, Request $request)
    {
        $rules = [];
        foreach (locales() as $key => $language) {
            $rules['text_' . $key] = 'required|string|max:255';
        }
        $this->validate($request, $rules);
        $data = [];
        foreach (locales() as $key => $language) {
            $data['text'][$key] = $request->get('text_' . $key);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }

        $slider->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_edited'));

        return redirect()->back();
    }

    public function destroy($uuid, Request $request)
    {
        $sliders = Slider::query()->whereIn('uuid', explode(',', $uuid))->delete();
        return response()->json(['status' => true]);
    }

    public function indexTable(Request $request)
    {
        $sliders = Slider::query()->orderByDesc('id');
        return Datatables::of($sliders)
            ->filter(function ($query) use ($request) {
                $text = (urlencode($request->get('text')));
                if ($request->get('text')) {
                    $query->where('text->'.locale(), 'like', "%{$request->get('text')}%");
                }
            })->addColumn('action', function ($slider) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $slider->uuid . '" ';
                $data_attr .= 'data-image="' . $slider->image . '" ';
                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-text_' . $key . '="' . $slider->getTranslation('text', $key) . '" ';
                }
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $slider->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })->make(true);
    }

}
