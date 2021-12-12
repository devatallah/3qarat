@extends('admin.app')
@section('title')
    @lang('reviews')
@endsection
@section('styles')
@endsection
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('reviews')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">@lang('home')</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{url('/admin/reviews')}}">@lang('reviews')</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">

            <section id="">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="head-label">
                                    <h4 class="card-title">@lang('reviews')</h4>
                                </div>
                                <div class="text-right">
                                    <div class="form-gruop">
{{--
                                        <button class="btn btn-outline-primary" type="button" data-toggle="modal"
                                                data-target="#create_modal"><span><i class="fa fa-plus"></i> @lang('add_new_record')</span>
                                        </button>
--}}
                                        <button disabled="" id="delete_btn"
                                                class="delete-btn btn btn-outline-danger">
                                            <span><i class="fa fa-lg fa-trash-alt" aria-hidden="true"></i> @lang('delete')</span>
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="search_form">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_comment">@lang('comment')</label>
                                                <input id="s_comment" type="text" class="search_input form-control"
                                                       placeholder="@lang('comment')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_rating">@lang('rating')</label>
                                                <input id="s_rating" type="text" class="search_input form-control"
                                                       placeholder="@lang('rating')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_name">@lang('name')</label>
                                                <input id="s_name" type="text" class="search_input form-control"
                                                       placeholder="@lang('name')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_user_uuid">@lang('user')</label>
                                                <select name="" id="s_user_uuid" class="user_uuid form-control"></select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_property_uuid">@lang('property')</label>
                                                <select name="" id="s_property_uuid" class="property_uuid form-control"></select>
                                            </div>
                                        </div>
                                        <div class="col-3" style="margin-top: 20px">
                                            <div class="form-group">
                                                <button id="search_btn" class="btn btn-outline-info" type="submit">
                                                    <span><i class="fa fa-search"></i> @lang('search')</span>
                                                </button>
                                                <button id="clear_btn" class="btn btn-outline-secondary" type="submit">
                                                    <span><i class="fa fa-undo"></i> @lang('reset')</span>
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive card-datatable">
                                <table class="table" id="datatable">
                                    <thead>
                                    <tr>
                                        <th class="checkbox-column sorting_disabled" rowspan="1" colspan="1"
                                            style="width: 35px;" aria-label=" Record Id ">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       class="table_ids custom-control-input dt-checkboxes"
                                                       id="select_all">
                                                <label class="custom-control-label" for="select_all"></label>
                                            </div>
                                        </th>
                                        <th>@lang('uuid')</th>
                                        <th>@lang('user_name')</th>
                                        <th>@lang('user_mobile')</th>
                                        <th>@lang('property')</th>
                                        <th>@lang('property_code')</th>
                                        <th>@lang('comment')</th>
                                        <th>@lang('rating')</th>
                                        <th>@lang('create_date')</th>
                                        <th style="width: 225px;">@lang('actions')</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('edit')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="edit_form" method="POST"
                          data-reset="true" class="ajax_form form-horizontal" enctype="multipart/form-data"
                          novalidate>
                        {{csrf_field()}}
                        {{method_field('PUT')}}
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="user_name">@lang('user_name')</label>
                                    <input disabled type="text" class="form-control"
                                           placeholder="@lang('user_name')"
                                           name="user_name" id="edit_user_name">
                                    <div class="invalid-review"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="user_mobile">@lang('user_mobile')</label>
                                    <input disabled type="text" class="form-control"
                                           placeholder="@lang('user_mobile')"
                                           name="user_mobile" id="edit_user_mobile">
                                    <div class="invalid-review"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="property_name">@lang('property_name')</label>
                                    <input disabled type="text" class="form-control"
                                           placeholder="@lang('property_name')"
                                           name="property_name" id="edit_property_name">
                                    <div class="invalid-review"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="property_code">@lang('property_code')</label>
                                    <input disabled type="text" class="form-control"
                                           placeholder="@lang('property_code')"
                                           name="property_code" id="edit_property_code">
                                    <div class="invalid-review"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="comment">@lang('comment')</label>
                                    <textarea disabled class="form-control" name="comment" id="edit_comment" cols="30" rows="3"></textarea>
                                    <div class="invalid-review"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="rating">@lang('rating')</label>
                                    <input disabled type="text" class="form-control"
                                           placeholder="@lang('rating')"
                                           name="rating" id="edit_rating">
                                    <div class="invalid-review"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="create_date">@lang('create_date')</label>
                                    <input disabled type="text" class="form-control"
                                           placeholder="@lang('create_date')"
                                           name="create_date" id="edit_create_date">
                                    <div class="invalid-review"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
{{--
                    <button type="submit" form="edit_form" class="submit_btn btn btn-primary">
                        <i class="fa fa-spinner fa-spin" style="display: none;"></i>
                        @lang('save')
                    </button>
--}}
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">@lang('close')</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')

@endsection
@section('scripts')
    <script>
        var url = '{{url(app()->getLocale()."/admin/reviews")}}/';

        var oTable = $('#datatable').DataTable({
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            "oLanguage": {
                @if(app()->isLocale('ar'))
                "sEmptyTable": "ليست هناك بيانات متاحة في الجدول",
                "sLoadingRecords": "جارٍ التحميل...",
                "sProcessing": "جارٍ التحميل...",
                "sLengthMenu": "أظهر _MENU_ مدخلات",
                "sZeroRecords": "لم يعثر على أية سجلات",
                "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                "sInfoPostFix": "",
                "sSearch": "ابحث:",
                "oAria": {
                    "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
                    "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
                },

                @endif// "oPaginate": {"sPrevious": '<-', "sNext": '->'},
                "oPaginate": {
                    // remove previous & next text from pagination
                    "sPrevious": '&nbsp;',
                    "sNext": '&nbsp;'
                }
            },
            'columnDefs': [
                {
                    "targets": 1,
                    "visible": false
                },
                {
                    'targets': 0,
                    "searchable": false,
                    "orderable": false
                },
            ],
            // dom: 'lrtip',
            "order": [[1, 'asc']],
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '{{ url(app()->getLocale().'/admin/reviews/indexTable')}}',
                data: function (d) {
                    d.comment = $('#s_comment').val();
                    d.rating = $('#s_rating').val();
                    d.user_uuid = $('#s_user_uuid').val();
                    d.property_uuid = $('#s_property_uuid').val();
                }
            },
            columns: [
                {
                    "render": function (data, type, full, meta) {
                        return `<td class="checkbox-column sorting_1">
                                       <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="table_ids custom-control-input dt-checkboxes"
                                         name="table_ids[]" value="` + full.uuid + `" id="checkbox` + full.uuid + `" >
                                    <label class="custom-control-label" for="checkbox` + full.uuid + `"></label>
                                </div></td>`;
                    }
                },

                {data: 'uuid', name: 'uuid'},
                {data: 'user_name', name: 'user_name'},
                {data: 'user_mobile', name: 'user_mobile'},
                {data: 'property_name', name: 'property_name'},
                {data: 'property_code', name: 'property_code'},
                {data: 'comment', name: 'comment'},
                {data: 'rating', name: 'rating'},
                {data: 'create_date', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $(document).ready(function () {
            $('.user_uuid').select2({
                dir: 'ltr',
                placeholder: "@lang('search')",
                minimumInputLength: 3,
                ajax: {
                    url: '{{url('admin/get_users')}}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true,
                }
            });
            $('.property_uuid').select2({
                dir: 'ltr',
                placeholder: "@lang('search')",
                minimumInputLength: 3,
                ajax: {
                    url: '{{url('admin/get_properties')}}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true,
                }
            });
            $(document).on('click', '.edit_btn', function (event) {
                var button = $(this)
                $('#edit_user_name').val(button.data('user_name'))
                $('#edit_user_mobile').val(button.data('user_mobile'))
                $('#edit_property_name').val(button.data('property_name'))
                $('#edit_property_code').val(button.data('property_code'))
                $('#edit_rating').val(button.data('rating'))
                $('#edit_comment').val(button.data('comment'))
                $('#edit_create_date').val(button.data('create_date'))
            });
            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', url);
            });
        });

    </script>

@endsection
