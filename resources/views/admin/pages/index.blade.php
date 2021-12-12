@extends('admin.app')
@section('title')
    @lang('pages')
@endsection
@section('styles')
@endsection
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('pages')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">@lang('home')</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{url('/admin/pages')}}">@lang('pages')</a>
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
                                    <h4 class="card-title">@lang('pages')</h4>
                                </div>
                                <div class="text-right">
{{--
                                    <div class="form-gruop">
                                        <button class="btn btn-outline-primary" type="button" data-toggle="modal"
                                                data-target="#create_modal"><span><i class="fa fa-plus"></i> @lang('add_new_record')</span>
                                        </button>
                                        <button disabled="" id="delete_btn"
                                                class="delete-btn btn btn-outline-danger">
                                            <span><i class="fa fa-lg fa-trash-alt" aria-hidden="true"></i> @lang('delete')</span>
                                        </button>

                                    </div>
--}}
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
                                        <th>@lang('title')</th>
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
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="title_{{$key}}">@lang('title') @lang($value)</label>
                                        <input type="text" class="form-control"
                                               placeholder="@lang('title') @lang($value)"
                                               name="title_{{$key}}" id="title_{{$key}}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
                            @foreach(locales() as $key => $value)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="content_{{$key}}">@lang('content') @lang($value)</label>
                                        <textarea name="content_{{$key}}" id="content_{{$key}}"
                                                  class="contents form-control" cols="30" rows="3"
                                                  placeholder="@lang('content') @lang($value)"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
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
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="edit_title_{{$key}}">@lang('title') @lang($value)</label>
                                        <input type="text" class="form-control"
                                               placeholder="@lang('title') @lang($value)"
                                               name="title_{{$key}}" id="edit_title_{{$key}}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
                            @foreach(locales() as $key => $value)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label
                                            for="edit_content_{{$key}}">@lang('content') @lang($value)</label>
                                        <textarea name="content_{{$key}}" id="edit_content_{{$key}}"
                                                  class="contents form-control" cols="30" rows="3"
                                                  placeholder="@lang('content') @lang($value)"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
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
    <script src="https://cdn.ckeditor.com/ckeditor5/25.0.0/classic/ckeditor.js"></script>
    <script>

        var url = '{{url(app()->getLocale()."/admin/pages")}}/';

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
                url: '{{ url(app()->getLocale().'/admin/pages/indexTable')}}',
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
                {data: 'page_title', name: 'title'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $(document).ready(function () {
            let edit_content_en, edit_content_ar;
            ClassicEditor
                .create(document.querySelector('#edit_content_en'))
                .then(editor => {
                    window.editor = editor;
                    edit_content_en = editor;
                })
            ClassicEditor
                .create(document.querySelector('#edit_content_ar'))
                .then(editor => {
                    window.editor = editor;
                    edit_content_ar = editor;
                })


            // ClassicEditor
            //     .create( document.querySelector( '#content_en' ) )
            //     .catch( error => {
            //         console.error( error );
            //     } );
            // ClassicEditor
            //     .create( document.querySelector( '#content_ar' ) )
            //     .catch( error => {
            //         console.error( error );
            //     } );
            // ClassicEditor
            //     .create( document.querySelector( '#edit_content_en' ) )
            //     .catch( error => {
            //         console.error( error );
            //     } );
            // edit_content_ar = ClassicEditor
            //     .create( document.querySelector( '#edit_content_ar' ) )
            //     .catch( error => {
            //         console.error( error );
            //     } );

            $(document).on('click', '.edit_btn', function (event) {
                event.preventDefault();
                console.log(342342)
                var button = $(this)
                var uuid = button.data('uuid')
                $('#edit_form').attr('action', url + uuid)
                @foreach(locales() as $key => $value)
                $('#edit_title_{{$key}}').val(button.data('title_{{$key}}'))
                @endforeach
                $.get("{{url('/admin/pages/get_content')}}/"+uuid, function (data) {
                    edit_content_en.setData(data.content_en);
                    edit_content_ar.setData(data.content_ar);
                });

                {{--$('#edit_content_{{$key}}').val(button.data('content_{{$key}}'))--}}
            });
            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', url);
            });
        });


    </script>

@endsection
