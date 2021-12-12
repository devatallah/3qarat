@extends('admin.app')
@section('title')
    @lang('notifications')
@endsection
@section('styles')
@endsection
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('notifications')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">@lang('home')</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{url('/admin/notifications')}}">@lang('notifications')</a>
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
                                    <h4 class="card-title">@lang('notifications')</h4>
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
                                                <label for="s_title">@lang('title')</label>
                                                <input id="s_title" type="text" class="search_input form-control"
                                                       placeholder="@lang('title')">
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
                                        <th>@lang('title')</th>
{{--                                        <th>@lang('country')</th>--}}
{{--                                        <th>@lang('city')</th>--}}
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
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title">@lang('title')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('title')"
                                           name="title" id="title">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="content">@lang('content')</label>
                                    <textarea class="form-control" name="content" id="content" cols="30" rows="3"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="country_uuid">@lang('country')</label>
                                    <select name="country_uuid" id="country_uuid" class="form-control">
                                        <option value="">@lang('select')</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->uuid}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="city_uuid">@lang('city')</label>
                                    <select name="city_uuid" id="city_uuid" class="form-control">
                                        <option value="">@lang('select')</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="users">@lang('users')</label>
                                    <select name="users" id="users" class="form-control users" multiple>
                                        <option value="">@lang('all')</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="image">@lang('image')</label>
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
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="edit_title">@lang('title')</label>
                                    <input disabled type="text" class="form-control"
                                           placeholder="@lang('title')"
                                           name="title" id="edit_title">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="edit_content">@lang('content')</label>
                                    <textarea disabled class="form-control" name="content" id="edit_content"
                                              cols="30" rows="3"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_country_uuid">@lang('country')</label>
                                    <select disabled name="country_uuid" id="edit_country_uuid" class="form-control">
                                        <option value="">@lang('select')</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->uuid}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_city_uuid">@lang('city')</label>
                                    <select disabled name="city_uuid" id="edit_city_uuid" class="form-control">
                                        <option value="">@lang('select')</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="edit_users">@lang('users')</label>
                                    <select disabled name="users" id="edit_users" class="form-control users" multiple>
                                        <option value="">@lang('all')</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="image">@lang('image')</label>
                                <div class="form-group">
                                    <div class="fileinput fileinput-exists"
                                         data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail"
                                             data-trigger="fileinput"
                                             style="width: 200px; height: 150px;">
                                            <img id="edit_src_image" src="" alt=""/>
                                        </div>
{{--
                                        <div>
                                                    <span class="btn btn-secondary btn-file">
                                                                <span
                                                                    class="fileinput-new"> @lang('select_image')</span>
                                                                <span
                                                                    class="fileinput-exists"> @lang('select_image')</span>
                                                        <input type="file" name="image"></span>
                                        </div>
--}}
                                        <div class="invalid-feedback"></div>
                                    </div>
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
        var url = '{{url(app()->getLocale()."/admin/notifications")}}/';

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
                url: '{{ url(app()->getLocale().'/admin/notifications/indexTable')}}',
                data: function (d) {
                    d.title = $('#s_title').val();
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
                        return `<img width="50" height="50" src="` + full.image + `" class="avatar avatar-sm me-3" alt="user1">`;
                    }
                },
                {data: 'title', name: 'title'},
                // {data: 'country_name', name: 'country_uuid'},
                // {data: 'city_name', name: 'city_uuid'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $(document).ready(function () {
            var cities_list = {
                @foreach($countries as $country)
                'country_{{$country->uuid}}': [
                        @foreach($country->cities as $city)
                    {
                        id: '{{$city->uuid}}',
                        text: '{{$city->name}}',
                    },

                    @endforeach
                ],
                @endforeach
            };

            $('.users').select2({
                dir: 'ltr',
                placeholder: "@lang('all')",
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
            $(document).on("change", "#country_uuid", function (e) {
                var value = $(this).val();
                $("#city_uuid").html('<option selected value="">@lang('select')</option>')
                $("#city_uuid").select2({
                    data: cities_list['country_' + value]
                }).trigger("change");
            });
            $(document).on("change", "#s_country_uuid", function (e) {
                var value = $(this).val();
                $("#s_city_uuid").html('<option selected value="">@lang('select')</option>')
                $("#s_city_uuid").select2({
                    data: cities_list['country_' + value]
                }).trigger("change");
            });
            $(document).on("change", "#edit_country_uuid", function (e) {
                var value = $(this).val();
                $("#edit_city_uuid").html('<option selected value="">@lang('select')</option>')
                $("#edit_city_uuid").select2({
                    data: cities_list['country_' + value]
                }).trigger("change");
            });

            $(document).on('click', '.edit_btn', function (event) {
                $('#edit_users').html('').trigger('change');
                var newOption = new Option('all', '', true, false);
                $('#edit_users').append(newOption).trigger('change');
                var button = $(this)
                var uuid = button.data('uuid')
                $('#edit_form').attr('action', url + uuid)
                $('#edit_title').val(button.data('title'))
                $('#edit_content').val(button.data('content'))
                $('#edit_country_uuid').val(button.data('country_uuid')).trigger('change')
                $('#edit_city_uuid').val(button.data('city_uuid')).trigger('change')
                $('#edit_src_image').attr('src', button.data('image'))
                var user_names = (button.data('user_names').split('___'))
                var user_uuids = (button.data('user_uuids').split('___'))
                $.each(user_names, function(i, val) {
                    var newOption = new Option(val, user_uuids[i], true, true);
                    $('#edit_users').append(newOption).trigger('change');
                });
            });
            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', url);
            });
        });

    </script>

@endsection
