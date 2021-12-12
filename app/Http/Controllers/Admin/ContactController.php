<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Webpatser\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.contacts.index');
    }

    public function destroy($uuid, Request $request)
    {
        $contacts = Contact::query()->whereIn('uuid', explode(',', $uuid))->delete();
        return response()->json(['status' => true]);
    }

    public function indexTable(Request $request)
    {
        $contacts = Contact::query()->orderByDesc('id');
        return Datatables::of($contacts)
            ->filter(function ($query) use ($request) {
                $name = (urlencode($request->get('name')));
                if ($request->get('name')) {
                    $query->where('name', 'like', "%{$request->get('name')}%");
                }
                if ($request->get('mobile')) {
                    $query->where('mobile', 'like', "%{$request->get('mobile')}%");
                }
            })->addColumn('action', function ($contact) {
                $data_attr = '';
                $data_attr .= 'data-uuid="' . $contact->uuid . '" ';
                $data_attr .= 'data-name="' . $contact->name . '" ';
                $data_attr .= 'data-mobile="' . $contact->mobile . '" ';
                $data_attr .= 'data-message="' . $contact->message . '" ';
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('show') . '</button>';
                $string .= ' <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $contact->uuid .
                    '">' . __('delete') . '</button>';
                return $string;
            })->make(true);
    }

}
