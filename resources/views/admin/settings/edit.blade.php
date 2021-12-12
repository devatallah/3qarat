@extends('admin.app')
@section('title')
    @lang('settings')
@endsection
@section('styles')
    {{--    <script type="text/javascript"--}}
    {{--            src="http://maps.google.com/maps/api/js?key=AIzaSyBIwzALxUPNbatRBj3Xi1Uhp0fFzwWNBkE&sensor=false&libraries=drawing"></script>--}}

@endsection
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('settings')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">@lang('home')</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{url('/admin/settings')}}">@lang('settings')</a>
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
                            <div class="card-body">
                                <form action="{{url(app()->getLocale()."/admin/settings")}}" id="basic-form"
                                      method="post"
                                      data-reset="false" class="ajax_form form-horizontal" enctype="multipart/form-data"
                                      novalidate>
                                    {{csrf_field()}}
                                    {{method_field('PUT')}}
                                    <div class="row">
                                        <div class="offset-1 col-10">
                                            <div class="form-row">
                                                <div class="col-4 form-group">
                                                    <label for="email">@lang('email')</label>
                                                    <input type="email" class="form-control" name="email"
                                                           value="{{$setting->email}}"
                                                           placeholder="@lang('email')">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="mobile">@lang('mobile')</label>
                                                    <input type="text" class="form-control" name="mobile"
                                                           value="{{$setting->mobile}}"
                                                           placeholder="@lang('mobile')">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="whatsapp">@lang('whatsapp')</label>
                                                    <input type="text" class="form-control" name="whatsapp"
                                                           value="{{$setting->whatsapp}}"
                                                           placeholder="@lang('whatsapp')">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="facebook">@lang('google_store')</label>
                                                    <input type="text" class="form-control" name="google_store"
                                                           value="{{$setting->google_store}}"
                                                           placeholder="@lang('google_store')">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="facebook">@lang('apple_store')</label>
                                                    <input type="text" class="form-control" name="apple_store"
                                                           value="{{$setting->apple_store}}"
                                                           placeholder="@lang('apple_store')">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="facebook">@lang('facebook')</label>
                                                    <input type="text" class="form-control" name="facebook"
                                                           value="{{$setting->facebook}}"
                                                           placeholder="@lang('facebook')">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="twitter">@lang('twitter')</label>
                                                    <input type="text" class="form-control" name="twitter"
                                                           value="{{$setting->twitter}}"
                                                           placeholder="@lang('twitter')">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="instagram">@lang('instagram')</label>
                                                    <input type="text" class="form-control" name="instagram"
                                                           value="{{$setting->instagram}}"
                                                           placeholder="@lang('instagram')">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="linkedin">@lang('linkedin')</label>
                                                    <input type="text" class="form-control" name="linkedin"
                                                           value="{{$setting->linkedin}}"
                                                           placeholder="@lang('linkedin')">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label for="snapchat">@lang('snapchat')</label>
                                                    <input type="text" class="form-control" name="snapchat"
                                                           value="{{$setting->snapchat}}"
                                                           placeholder="@lang('snapchat')">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="offset-10 col-2 form-group">
                                            <button type="submit" class="submit_btn btn btn-primary">
                                                <i class="fa fa-spinner fa-spin" style="display: none;"></i>
                                                @lang('save')
                                            </button>
                                            <a href="{{url('/admin/settings')}}" id="cancel_btn"
                                               class="btn btn-outline-danger">
                                                @lang('cancel')
                                            </a>

                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>


@endsection
@section('js')
@endsection
@section('scripts')

@endsection
