<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.reviews.index');

    }

    public function destroy($id, Request $request)
    {
        $reviews = Review::query()->whereIn('uuid', explode(',', $id))->delete();
        return response()->json(['status' => true]);
    }

    public function reviews(Request $request)
    {
        return view('layout.app');

    }


    public function indexTable(Request $request)
    {
        $reviews = Review::query()->orderByDesc('id');
        return Datatables::of($reviews)
            ->filter(function ($query) use ($request) {
                if ($request->get('comment')) {
                    $query->where('comment', 'like', "%{$request->get('comment')}%");
                }
                if ($request->filled('rating')) {
                    $query->where('rating', $request->rating);
                }
                if ($request->get('user_uuid')) {
                    $query->where('user_uuid', $request->user_uuid);
                }
                if ($request->get('property_uuid')) {
                    $query->where('property_uuid', $request->property_uuid);
                }

//                $request->merge(['length' => -1]);
            })->addColumn('action', function ($review) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $review->uuid . '" ';
                $data_attr .= 'data-user_name="' . $review->user_name . '" ';
                $data_attr .= 'data-user_mobile="' . $review->user_mobile . '" ';
                $data_attr .= 'data-property_name="' . $review->property_name . '" ';
                $data_attr .= 'data-property_code="' . $review->property_code . '" ';
                $data_attr .= 'data-rating="' . $review->rating . '" ';
                $data_attr .= 'data-comment="' . $review->comment . '" ';
                $data_attr .= 'data-create_date="' . $review->create_date . '" ';
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('show') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $review->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })
//            ->editColumn('id', 'ID: {{$id}}')

            ->make(true);
    }

}
