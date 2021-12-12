<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Review;
use App\Models\ReviewReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ReviewReportController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.review_reports.index');

    }

    public function destroy($id, Request $request)
    {
        $review_reports = ReviewReport::query()->whereIn('uuid', explode(',', $id))->delete();
        return response()->json(['status' => true]);
    }

    public function review_reports(Request $request)
    {
        return view('layout.app');

    }


    public function indexTable(Request $request)
    {
        $review_reports = ReviewReport::query()->orderByDesc('id');
        return Datatables::of($review_reports)
            ->filter(function ($query) use ($request) {
                if ($request->filled('rating') || $request->get('user_uuid') || $request->get('comment')) {
                    $query->whereHas('review', function ($q) use ($request){
                        if ($request->filled('rating')){
                            $q->where('rating', $request->rating);
                        }
                        if ($request->get('comment')) {
                            $q->where('comment', 'like', "%{$request->get('comment')}%");
                        }
                        if ($request->get('review_user_uuid')) {
                            $q->where('user_uuid', $request->review_user_uuid);
                        }
                        if ($request->get('property_uuid')) {
                            $q->where('property_uuid', $request->property_uuid);
                        }
                    });
                }
                if ($request->get('report')) {
                    $query->where('report', 'like', "%{$request->get('report')}%");
                }
                if ($request->get('user_uuid')) {
                    $query->where('user_uuid', $request->user_uuid);
                }

//                $request->merge(['length' => -1]);
            })->addColumn('action', function ($review_report) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . @$review_report->uuid . '" ';
                $data_attr .= 'data-user_name="' . @$review_report->user_name . '" ';
                $data_attr .= 'data-user_mobile="' . @$review_report->user_mobile . '" ';
                $data_attr .= 'data-review_user_name="' . @$review_report->user_name . '" ';
                $data_attr .= 'data-review_user_mobile="' . @$review_report->user_mobile . '" ';
                $data_attr .= 'data-property_name="' . @$review_report->review->property_name . '" ';
                $data_attr .= 'data-property_code="' . @$review_report->review->property_code . '" ';
                $data_attr .= 'data-rating="' . @$review_report->review->rating . '" ';
                $data_attr .= 'data-comment="' . @$review_report->review->comment . '" ';
                $data_attr .= 'data-report="' . @$review_report->report . '" ';
                $data_attr .= 'data-create_date="' . @$review_report->create_date . '" ';
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('show') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $review_report->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })
//            ->editColumn('id', 'ID: {{$id}}')

            ->make(true);
    }

}
