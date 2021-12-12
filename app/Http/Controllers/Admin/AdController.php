<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class AdController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.ads.index');

    }

    public function store(Request $request)
    {
        $rules = [
            'place' => 'required|in:main,favorite,recent_added,nearby,property_details',
            'image' => 'required|image',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date'
        ];
        foreach (locales() as $key => $language) {
            $rules['text_' . $key] = 'required|string|max:255';
        }
        $this->validate($request, $rules);
        $data = $request->only('place', 'start_date', 'end_date');
        foreach (locales() as $key => $language) {
            $data['text'][$key] = $request->get('text_' . $key);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }

        Ad::query()->create($data);


        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_added'));

        return redirect('ads');
    }

    public function update(Ad $ad, Request $request)
    {
        $rules = [
            'place' => 'required|in:main,favorite,recent_added,nearby,property_details',
            'image' => 'nullable|image',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date'
        ];
        foreach (locales() as $key => $language) {
            $rules['text_' . $key] = 'required|string|max:255';
        }
        $this->validate($request, $rules);
        $data = $request->only('place', 'start_date', 'end_date');
        foreach (locales() as $key => $language) {
            $data['text'][$key] = $request->get('text_' . $key);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $data['image'] = $image;
        }

        $ad->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_edited'));

        return redirect()->back();
    }

    public function destroy($uuid, Request $request)
    {
        $ads = Ad::query()->whereIn('uuid', explode(',', $uuid))->delete();
        return response()->json(['status' => true]);
    }

    public function indexTable(Request $request)
    {
        $ads = Ad::query()->orderByDesc('id');
        return Datatables::of($ads)
            ->filter(function ($query) use ($request) {
                $text = (urlencode($request->get('text')));
                if ($request->get('text')) {
                    $query->where('text->' . locale(), 'like', "%{$request->get('text')}%");
                }
                if ($request->get('place')) {
                    $query->where('place', $request->place);
                }
            })->addColumn('action', function ($ad) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $ad->uuid . '" ';
                $data_attr .= 'data-place="' . $ad->place . '" ';
                $data_attr .= 'data-start_date="' . $ad->start_date . '" ';
                $data_attr .= 'data-end_date="' . $ad->end_date . '" ';
                $data_attr .= 'data-image="' . $ad->image . '" ';
                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-text_' . $key . '="' . $ad->getTranslation('text', $key) . '" ';
                }
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $ad->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })->make(true);
    }

}
