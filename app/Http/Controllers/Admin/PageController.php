<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Page;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.pages.index');

    }

    public function store(Request $request)
    {
        $rules = [];
        foreach (locales() as $key => $language) {
            $rules['title_' . $key] = 'required|string|max:255';
            $rules['content_' . $key] = 'required|string';
        }
        $this->validate($request, $rules);
        $data = $request->only('price', 'duration');
        foreach (locales() as $key => $language) {
            $data['title'][$key] = $request->get('title_' . $key);
            $data['content'][$key] = $request->get('content_' . $key);
        }

        Page::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_added'));

        return redirect('pages');
    }

    public function update(Page $page, Request $request)
    {
        $rules = [];
        foreach (locales() as $key => $language) {
            $rules['title_' . $key] = 'required|string|max:255';
            $rules['content_' . $key] = 'required|string';
        }
        $this->validate($request, $rules);
        $data = $request->only('price', 'duration');
        foreach (locales() as $key => $language) {
            $data['title'][$key] = $request->get('title_' . $key);
            $data['content'][$key] = $request->get('content_' . $key);
        }

        $page->update($data);
        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }
        Session::flash('success_message', __('item_edited'));

        return redirect()->back();
    }

    public function destroy($uuid, Request $request)
    {
        Page::query()->whereIn('uuid', explode(',', $uuid))->delete();
        return response()->json(['status' => true]);
    }

    public function getContent(Page $page){
        $content_en = $page->getTranslation('content', 'en');
        $content_ar = $page->getTranslation('content', 'ar');
        $status = true;
        return response()->json(compact('status', 'content_en', 'content_ar'));

    }
    public function indexTable(Request $request)
    {
        $pages = Page::query()->orderByDesc('id');
        return Datatables::of($pages)
            ->filter(function ($query) use ($request) {
                if ($request->get('title')) {
                    $query->where('title', 'like', "%{$request->get('title')}%");
                }
            })->addColumn('action', function ($page) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $page->uuid . '" ';
                $data_attr .= 'data-image="' . $page->image . '" ';
                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-title_' . $key . '="' . $page->getTranslation('title', $key) . '" ';
//                    $data_attr .= 'data-content_' . $key . '="' . $page->getTranslation('content', $key) . '" ';
                }
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';
/*                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $page->uuid .
                    '">' . __('delete') . '</button>';*/
                return $string;
            })->make(true);
    }

}
