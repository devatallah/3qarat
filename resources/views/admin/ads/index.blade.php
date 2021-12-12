@extends('admin.app')
@section('title')
    @lang('ads')
@endsection
@section('styles')
@endsection
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('ads')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">@lang('home')</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{url('/admin/ads')}}">@lang('ads')</a>
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
                                    <h4 class="card-title">@lang('ads')</h4>
                                </div>
                                <div class="text-right">
                                    <div class="form-gruop">
                                        <button class="btn btn-outline-primary" type="button" data-toggle="modal"
                                                data-target="#create_modal"><span><i class="fa fa-plus"></i> @lang('add_new_record')</span>
                                        </button>
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
                                                <label for="s_text">@lang('text')</label>
                                                <input id="s_text" type="text" class="search_input form-control"
                                                       placeholder="@lang('text')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_place">@lang('place')</label>
                                                <select id="s_place" class="search_input form-control">
                                                    <option value="">@lang('select')</option>
                                                    <option value="main">@lang('main')</option>
                                                    <option value="favorite">@lang('favorite')</option>
                                                    <option value="recent_added">@lang('recent_added')</option>
                                                    <option value="nearby">@lang('nearby')</option>
                                                    <option value="property_details">@lang('property_details')</option>
                                                </select>
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
                                        <th>@lang('image')</th>
                                        <th>@lang('text')</th>
                                        <th>@lang('place')</th>
                                        <th>@lang('start_date')</th>
                                        <th>@lang('end_date')</th>
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
    <div class="modal fade" id="create_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('create')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="create_form" method="POST"
                          data-reset="true" class="ajax_form form-horizontal" enctype="multipart/form-data"
                          novalidate>
                        {{csrf_field()}}
                        <div class="row">
                            @foreach(locales() as $key => $value)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="text_{{$key}}">@lang('text') @lang($value)</label>
                                        <input type="text" class="form-control"
                                               placeholder="@lang('text') @lang($value)"
                                               name="text_{{$key}}" id="text_{{$key}}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="place">@lang('place')</label>
                                    <select name="place" id="place" class="form-control">
                                        <option value="">@lang('select')</option>
                                        <option value="main">@lang('main')</option>
                                        <option value="favorite">@lang('favorite')</option>
                                        <option value="recent_added">@lang('recent_added')</option>
                                        <option value="nearby">@lang('nearby')</option>
                                        <option value="property_details">@lang('property_details')</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="start_date">@lang('start_date')</label>
                                        <input type="date" class="form-control"
                                               placeholder="@lang('start_date')"
                                               name="start_date" id="start_date">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="end_date">@lang('end_date')</label>
                                        <input type="date" class="form-control"
                                               placeholder="@lang('end_date')"
                                               name="end_date" id="end_date">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="icon">@lang('image')</label>
                                    <div class="form-group">
                                        <div class="fileinput fileinput-exists"
                                             data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail"
                                                 data-trigger="fileinput"
                                                 style="width: 200px; height: 150px;">
                                                <img id="" src="" alt=""/>
                                            </div>
                                            <div>
                                                    <span class="btn btn-secondary btn-file">
                                                                <span
                                                                    class="fileinput-new"> @lang('select_image')</span>
                                                                <span
                                                                    class="fileinput-exists"> @lang('select_image')</span>
                                                        <input type="file" name="image"></span>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="create_form" class="submit_btn btn btn-primary">
                        <i class="fa fa-spinner fa-spin" style="display: none;"></i>
                        @lang('save')
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">@lang('close')
                    </button>{{--                            <button type="button" form="create_form" class="btn btn-primary">Send message</button>--}}
                </div>
            </div>
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
                            @foreach(locales() as $key => $value)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="edit_text_{{$key}}">@lang('text') @lang($value)</label>
                                        <input type="text" class="form-control"
                                               placeholder="@lang('text') @lang($value)"
                                               name="text_{{$key}}" id="edit_text_{{$key}}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="edit_place">@lang('place')</label>
                                        <select name="place" id="edit_place" class="form-control">
                                            <option value="">@lang('select')</option>
                                            <option value="main">@lang('main')</option>
                                            <option value="favorite">@lang('favorite')</option>
                                            <option value="recent_added">@lang('recent_added')</option>
                                            <option value="nearby">@lang('nearby')</option>
                                            <option value="property_details">@lang('property_details')</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="edit_start_date">@lang('start_date')</label>
                                        <input type="date" class="form-control"
                                               placeholder="@lang('start_date')"
                                               name="start_date" id="edit_start_date">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="edit_end_date">@lang('end_date')</label>
                                        <input type="date" class="form-control"
                                               placeholder="@lang('end_date')"
                                               name="end_date" id="edit_end_date">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="icon">@lang('image')</label>
                                    <div class="form-group">
                                        <div class="fileinput fileinput-exists"
                                             data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail"
                                                 data-trigger="fileinput"
                                                 style="width: 200px; height: 150px;">
                                                <img id="edit_src_image" src="" alt=""/>
                                            </div>
                                            <div>
                                                    <span class="btn btn-secondary btn-file">
                                                                <span
                                                                    class="fileinput-new"> @lang('select_image')</span>
                                                                <span
                                                                    class="fileinput-exists"> @lang('select_image')</span>
                                                        <input type="file" name="image"></span>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="edit_form" class="submit_btn btn btn-primary">
                        <i class="fa fa-spinner fa-spin" style="display: none;"></i>
                        @lang('save')
                    </button>
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
        var url = '{{url(app()->getLocale()."/admin/ads")}}/';

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
                url: '{{ url(app()->getLocale().'/admin/ads/indexTable')}}',
                data: function (d) {
                    d.text = $('#s_text').val();
                    d.place = $('#s_place').val();
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
                {
                    "render": function (data, type, full, meta) {
                        return `<img width="50" height="50" src="` + full.image + `" class="avatar avatar-sm me-3" alt="user1"></div>`;
                    }
                },
                {data: 'ad_text', name: 'text'},
                {data: 'place_text', name: 'place'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $(document).ready(function () {
            $(document).on('click', '.edit_btn', function (event) {
                var button = $(this)
                var uuid = button.data('uuid')
                $('#edit_form').attr('action', url + uuid)
                $('#edit_place').val(button.data('place')).trigger('change')
                $('#edit_end_date').val(button.data('end_date')).trigger('change')
                $('#edit_start_date').val(button.data('start_date')).trigger('change')
                @foreach(locales() as $key => $value)
                $('#edit_text_{{$key}}').val(button.data('text_{{$key}}'))
                @endforeach
                $('#edit_src_image').attr('src', button.data('image'))
            });
            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', url);
            });
        });

    </script>

@endsection
