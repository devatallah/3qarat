@extends('admin.app')
@section('title')
    @lang('properties')
@endsection
@section('styles')
    <style>
        .pac-container {
            z-index: 1051 !important;
        }

    </style>
@endsection
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('properties')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">@lang('home')</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{url('/admin/properties')}}">@lang('properties')</a>
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
                                    <h4 class="card-title">@lang('properties')</h4>
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
                                                <label for="s_name">@lang('name')</label>
                                                <input id="s_name" type="text" class="search_input form-control"
                                                       placeholder="@lang('name')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_code">@lang('code')</label>
                                                <input id="s_code" type="text" class="search_input form-control"
                                                       placeholder="@lang('code')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_owner_name">@lang('owner_name')</label>
                                                <input id="s_owner_name" type="text" class="search_input form-control"
                                                       placeholder="@lang('owner_name')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_mobile">@lang('mobile')</label>
                                                <input id="s_mobile" type="text" class="search_input form-control"
                                                       placeholder="@lang('mobile')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_category_uuid">@lang('category')</label>
                                                <select name="s_category_uuid" id="s_category_uuid" class="form-control">
                                                    <option value="">@lang('select')</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{$category->uuid}}">{{$category->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_service_uuid">@lang('service')</label>
                                                <select name="s_service_uuid" id="s_service_uuid" class="form-control">
                                                    <option value="">@lang('select')</option>
                                                    @foreach($services as $service)
                                                        <option value="{{$service->uuid}}">{{$service->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_country_uuid">@lang('country')</label>
                                                <select name="s_country_uuid" id="s_country_uuid" class="form-control">
                                                    <option value="">@lang('select')</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{$country->uuid}}">{{$country->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_city_uuid">@lang('city')</label>
                                                <select name="s_city_uuid" id="s_city_uuid" class="form-control">
                                                    <option value="">@lang('select')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_user_uuid">@lang('user')</label>
                                                <select name="" id="s_user_uuid" class="user_uuid form-control"></select>
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
                                        <th>@lang('owner_name')</th>
                                        <th>@lang('property')</th>
                                        <th>@lang('code')</th>
                                        <th>@lang('category')</th>
                                        <th>@lang('service')</th>
                                        <th>@lang('country')</th>
                                        <th>@lang('city')</th>
                                        <th>@lang('price')</th>
                                        <th>@lang('area')</th>
                                        <th>@lang('user')</th>
                                        <th>@lang('mobile')</th>
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
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="user_uuid">@lang('user')</label>
                                    <select name="user_uuid" id="user_uuid" class="form-control user_uuid">
                                        <option value="">@lang('select')</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="owner_name">@lang('owner_name')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('owner_name')"
                                           name="owner_name" id="owner_name">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            @foreach(locales() as $key => $value)
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name_{{$key}}">@lang('name') @lang($value)</label>
                                        <input type="text" class="form-control"
                                               placeholder="@lang('name') @lang($value)"
                                               name="name_{{$key}}" id="name_{{$key}}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
                            @foreach(locales() as $key => $value)
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="description_{{$key}}">@lang('description') @lang($value)</label>
                                        <textarea class="form-control" name="description_{{$key}}" id="description_{{$key}}"
                                                  cols="30" rows="3"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="category_uuid">@lang('category')</label>
                                    <select class="category_uuid form-control" id="category_uuid" name="category_uuid" required>
                                        <option value="">@lang('select')</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->uuid}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="service_uuid">@lang('service')</label>
                                    <select class="service_uuid form-control" id="service_uuid" name="service_uuid" required>
                                        <option value="">@lang('select')</option>
                                        @foreach($services as $service)
                                            <option value="{{$service->uuid}}">{{$service->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="features">@lang('features')</label>
                                    <select class="features form-control" multiple id="features" name="features[]" required>
                                        <option value="">@lang('select')</option>
                                        @foreach($features as $feature)
                                            <option value="{{$feature->uuid}}">{{$feature->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="country_uuid">@lang('country')</label>
                                    <select class="country_uuid form-control" id="country_uuid" name="country_uuid" required>
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
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="price">@lang('price')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('price')"
                                           name="price" id="price">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="area">@lang('area')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('area')"
                                           name="area" id="area">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bathrooms">@lang('bathrooms')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('bathrooms')"
                                           name="bathrooms" id="bathrooms">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="rooms">@lang('rooms')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('rooms')"
                                           name="rooms" id="rooms">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="mobile">@lang('mobile')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('mobile')"
                                           name="mobile" id="mobile">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="whatsapp">@lang('whatsapp')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('whatsapp')"
                                           name="whatsapp" id="whatsapp">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="address" class="col-md-2 col-form-label">{{__('address')}}</label>
                                    <div class="col-md-12">
                                        <input type="search" name="address" placeholder="{{__('address')}}"
                                               id="address" class="form-control" value=""/>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="lat">@lang('lat')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('lat')"
                                           name="lat" id="lat">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="lng">@lang('lng')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('lng')"
                                           name="lng" id="lng">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="map" id="map" style="width: 100%; height: 300px;"></div>
                                    <div class="form-group">
{{--
                                        <div class="col-md-6">
                                            <input type="hidden" class="form-control" name="lat"
                                                   id="lat" placeholder=" {{__('lat')}}">
                                            <input type="hidden" class="form-control" name="lng"
                                                   id="lng" placeholder=" {{__('lng')}}">
                                        </div>
--}}
                                    </div>
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
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="images">@lang('images')</label>
                                    <input type="file" accept="image/*" class="form-control"
                                           multiple placeholder="@lang('images')"
                                           name="images[]" id="images">
                                    <div class="invalid-feedback"></div>
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
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_user_uuid">@lang('user')</label>
                                    <select name="user_uuid" id="edit_user_uuid" class="form-control user_uuid">
                                        <option value="">@lang('select')</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_owner_name">@lang('owner_name')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('owner_name')"
                                           name="owner_name" id="edit_owner_name">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            @foreach(locales() as $key => $value)
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="edit_name_{{$key}}">@lang('name') @lang($value)</label>
                                        <input type="text" class="form-control"
                                               placeholder="@lang('name') @lang($value)"
                                               name="name_{{$key}}" id="edit_name_{{$key}}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
                            @foreach(locales() as $key => $value)
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="edit_description_{{$key}}">@lang('description') @lang($value)</label>
                                        <textarea class="form-control" name="description_{{$key}}" id="edit_description_{{$key}}"
                                                  cols="30" rows="3"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_category_uuid">@lang('category')</label>
                                    <select class="category_uuid form-control" id="edit_category_uuid" name="category_uuid" required>
                                        <option value="">@lang('select')</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->uuid}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_service_uuid">@lang('service')</label>
                                    <select class="service_uuid form-control" id="edit_service_uuid" name="service_uuid" required>
                                        <option value="">@lang('select')</option>
                                        @foreach($services as $service)
                                            <option value="{{$service->uuid}}">{{$service->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_features">@lang('features')</label>
                                    <select class="features form-control" multiple id="edit_features" name="features[]" required>
                                        <option value="">@lang('select')</option>
                                        @foreach($features as $feature)
                                            <option value="{{$feature->uuid}}">{{$feature->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_country_uuid">@lang('country')</label>
                                    <select class="country_uuid form-control" id="edit_country_uuid" name="country_uuid" required>
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
                                    <select name="city_uuid" id="edit_city_uuid" class="form-control">
                                        <option value="">@lang('select')</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_price">@lang('price')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('price')"
                                           name="price" id="edit_price">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_area">@lang('area')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('area')"
                                           name="area" id="edit_area">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_bathrooms">@lang('bathrooms')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('bathrooms')"
                                           name="bathrooms" id="edit_bathrooms">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_rooms">@lang('rooms')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('rooms')"
                                           name="rooms" id="edit_rooms">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_mobile">@lang('mobile')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('mobile')"
                                           name="mobile" id="edit_mobile">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_whatsapp">@lang('whatsapp')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('whatsapp')"
                                           name="whatsapp" id="edit_whatsapp">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_lat">@lang('lat')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('lat')"
                                           name="lat" id="edit_lat">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="edit_lng">@lang('lng')</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('lng')"
                                           name="lng" id="edit_lng">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="edit_address" class="col-md-2 col-form-label">{{__('address')}}</label>
                                    <div class="col-md-12">
                                        <input type="search" name="address" placeholder="{{__('address')}}"
                                               id="edit_address" class="form-control" value=""/>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="map" id="edit_map" style="width: 100%; height: 300px;"></div>
                                    <div class="form-group">
{{--
                                        <div class="col-md-6">
                                            <input type="hidden" class="form-control" name="lat"
                                                   id="edit_lat" placeholder=" {{__('lat')}}">
                                            <input type="hidden" class="form-control" name="lng"
                                                   id="edit_lng" placeholder=" {{__('lng')}}">
                                        </div>
--}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="edit_image">@lang('image')</label>
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
        var url = '{{url(app()->getLocale()."/admin/properties")}}/';

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
                url: '{{ url(app()->getLocale().'/admin/properties/indexTable')}}',
                data: function (d) {
                    d.name = $('#s_name').val();
                    d.code = $('#s_code').val();
                    d.user_uuid = $('#s_user_uuid').val();
                    d.service_uuid = $('#s_service_uuid').val();
                    d.mobile = $('#s_mobile').val();
                    d.owner_name = $('#s_owner_name').val();
                    d.category_uuid = $('#s_category_uuid').val();
                    d.country_uuid = $('#s_country_uuid').val();
                    d.city_uuid = $('#s_city_uuid').val();
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
                {data: 'owner_name', name: 'owner_name'},
                {data: 'property_name', name: 'name'},
                {data: 'code', name: 'code'},
                {data: 'category_name', name: 'category_uuid'},
                {data: 'service_name', name: 'service_uuid'},
                {data: 'country_name', name: 'country_uuid'},
                {data: 'city_name', name: 'city_uuid'},
                {data: 'price', name: 'price'},
                {data: 'area', name: 'area'},
                {data: 'user_name', name: 'user_uuid'},
                {data: 'mobile', name: 'mobile'},
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
                var button = $(this)
                var uuid = button.data('uuid')
                $('#edit_form').attr('action', url + uuid)
                @foreach(locales() as $key => $value)
                $('#edit_name_{{$key}}').val(button.data('name_{{$key}}'))
                $('#edit_description_{{$key}}').val(button.data('description_{{$key}}'))
                @endforeach
                var user_uuid = button.data('user_uuid')
                var user_name = button.data('user_name')
                $('#edit_user_uuid').append(`<option value="` + user_uuid + `" selected>` + user_name + `</option>`)
                $('#edit_owner_name').val(button.data('owner_name')).trigger('change')
                $('#edit_category_uuid').val(button.data('category_uuid')).trigger('change')
                $('#edit_service_uuid').val(button.data('service_uuid')).trigger('change')
                $('#edit_country_uuid').val(button.data('country_uuid')).trigger('change')
                $('#edit_city_uuid').val(button.data('city_uuid')).trigger('change')
                $('#edit_price').val(button.data('price'))
                $('#edit_area').val(button.data('area'))
                $('#edit_rooms').val(button.data('rooms'))
                $('#edit_bathrooms').val(button.data('bathrooms'))
                $('#edit_mobile').val(button.data('mobile'))
                $('#edit_whatsapp').val(button.data('whatsapp'))
                $('#edit_src_image').attr('src', button.data('image'))
                $('#edit_address').val(address).trigger('change')
                var features = button.data('features')+''
                if (features.indexOf(",") >= 0){
                    features = (button.data('features').split(','))
                    features = features.filter(item => item);
                }
                $("#edit_features").val(features).trigger('change');
                edit_myLatlng = new google.maps.LatLng(button.data('lat'), button.data('lng'))
                edit_placeMarker(edit_myLatlng)
            });
            $(document).on('click', '#create_btn', function (event) {
                $('#create_form').attr('action', url);
            });
        });

    </script>
    <script>
        function initMap(lat, lng) {
            address_map();
            edit_address_map();
        }

        function address_map() {
            var input = document.getElementById('address');
            var lat = parseFloat(document.getElementById('lat').value);
            var lng = parseFloat(document.getElementById('lng').value);
            var autocomplete = new google.maps.places.Autocomplete(input, {
                // types: ['(cities)'],
                componentRestrictions: {
                    country: 'sa'
                }
            });
            var geocoder = new google.maps.Geocoder;
            var infowindow = new google.maps.InfoWindow;
            var uluru = {lat: lat || 24.625943230187133, lng: lng || 46.70977277351633};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 6,
                center: uluru
            });
            autocomplete.bindTo('bounds', map);

            // Set the data fields to return when the user selects a place.
            autocomplete.setFields(
                ['address_components', 'geometry', 'icon', 'name']);

            autocomplete.addListener('place_changed', function () {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);  // Why 17? Because it looks good.
                }
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
                document.getElementById('lat').value = place.geometry.location.lat();
                document.getElementById('lng').value = place.geometry.location.lng();
            });

            if (isNaN(lat) && isNaN(lng)) {
                marker = new google.maps.Marker({
                    map: map,
                });
            } else {
                marker = new google.maps.Marker({
                    position: uluru,
                    map: map,
                });
            }
            google.maps.event.addListener(map, "click", function (event) {
                placeMarker(event.latLng)
                getAddress(event.latLng)
            });

            function placeMarker(location) {
                if (marker === null) {
                    marker = new google.maps.Marker({
                        position: location,
                        map: map,
                    });
                } else {
                    marker.setPosition(location);
                }
                var latlng = {lat: parseFloat(location.lat()), lng: parseFloat(location.lng())};

                $('#lat').val(latlng.lat)
                $('#lng').val(latlng.lng)
                $('#latlngs').change()

                getAddress(location);
            }

            function getAddress(latLng) {
                geocoder.geocode({'latLng': latLng},
                    function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                console.log(results[0].formatted_address);
                                document.getElementById("address").value = results[0].formatted_address;
                            } else {
                                document.getElementById("address").value = "No results";
                            }
                        } else {
                            document.getElementById("address").value = status;
                        }
                    });
            }

        }

        var edit_input = '', edit_lat = '', edit_lng = '', edit_autocomplete = '', edit_geocoder = '',
            edit_infowindow = '',
            edit_myLatlng = '', edit_uluru = '';

        function edit_address_map() {
            edit_input = document.getElementById('edit_address');
            edit_lat = parseFloat(document.getElementById('edit_lat').value);
            edit_lng = parseFloat(document.getElementById('edit_lng').value);
            edit_autocomplete = new google.maps.places.Autocomplete(edit_input, {
                // types: ['(cities)'],
                componentRestrictions: {
                    country: 'sa'
                }
            });
            edit_geocoder = new google.maps.Geocoder;
            edit_infowindow = new google.maps.InfoWindow;
            edit_myLatlng = new google.maps.LatLng(24.625943230187133, 46.70977277351633)
            edit_uluru = {lat: 24.625943230187133, lng: 46.70977277351633};
            var edit_map = new google.maps.Map(document.getElementById('edit_map'), {
                zoom: 6,
                center: edit_uluru
            });
            edit_autocomplete.bindTo('bounds', edit_map);

            // Set the data fields to return when the user selects a place.
            edit_autocomplete.setFields(
                ['address_components', 'geometry', 'icon', 'name']);

            edit_autocomplete.addListener('place_changed', function () {
                edit_infowindow.close();
                edit_marker.setVisible(false);
                var edit_place = edit_autocomplete.getPlace();
                if (!edit_place.geometry) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (edit_place.geometry.viewport) {
                    edit_map.fitBounds(edit_place.geometry.viewport);
                } else {
                    edit_map.setCenter(edit_place.geometry.location);
                    edit_map.setZoom(17);  // Why 17? Because it looks good.
                }
                edit_marker.setPosition(edit_place.geometry.location);
                edit_marker.setVisible(true);
                document.getElementById('edit_lat').value = edit_place.geometry.location.lat();
                document.getElementById('edit_lng').value = edit_place.geometry.location.lng();
            });

            if (isNaN(edit_lat) && isNaN(edit_lng)) {
                edit_marker = new google.maps.Marker({
                    map: edit_map,
                });
            } else {
                edit_marker = new google.maps.Marker({
                    position: edit_uluru,
                    map: edit_map,
                });
            }
            google.maps.event.addListener(edit_map, "click", function (event) {
                edit_placeMarker(event.latLng)
                edit_getAddress(event.latLng)
            });


        }

        function edit_getAddress(edit_latLng) {
            edit_geocoder.geocode({'latLng': edit_latLng},
                function (edit_results, edit_status) {
                    if (edit_status === google.maps.GeocoderStatus.OK) {
                        if (edit_results[0]) {
                            console.log(edit_results[0].formatted_address);
                            document.getElementById("edit_address").value = edit_results[0].formatted_address;
                        } else {
                            document.getElementById("edit_address").value = "No results";
                        }
                    } else {
                        document.getElementById("edit_address").value = status;
                    }
                });
        }

        function edit_placeMarker(edit_location) {
            if (edit_marker === null) {
                edit_marker = new google.maps.Marker({
                    position: edit_location,
                    map: edit_map,
                });
            } else {
                edit_marker.setPosition(edit_location);
            }
            var edit_latlng = {lat: parseFloat(edit_location.lat()), lng: parseFloat(edit_location.lng())};

            $('#edit_lat').val(edit_latlng.lat)
            $('#edit_lng').val(edit_latlng.lng)
            $('#edit_latlngs').change()

            edit_getAddress(edit_location);
        }

    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD9wmTJeQspHyBabX7npNkHrbAN7fXshmo&libraries=places&callback=initMap"
        async defer></script>

@endsection
