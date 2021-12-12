@extends('admin.app')
@section('title')
    @lang('home')
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('dashboard/app-assets/css'.rtl_assets().'/pages/dashboard-ecommerce.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/css/style'.rtl_assets().'.css')}}">
@endsection
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Ecommerce Starts -->
            <section id="dashboard-ecommerce">
                <div class="row">
                    <div class="col-xl-3 col-md-6 ">
                        <div class="card card-statistics">
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-primary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="users" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{$users_count}}</h4>
                                                <p class="card-text font-small-3 mb-0">@lang('users')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 ">
                        <div class="card card-statistics">
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-primary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="map-pin" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{$properties_count}}</h4>
                                                <p class="card-text font-small-3 mb-0">@lang('properties')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6  ">
                        <div class="card card-statistics">
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-primary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="grid" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{$categories_count}}</h4>
                                                <p class="card-text font-small-3 mb-0">@lang('categories')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6  ">
                        <div class="card card-statistics">
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-primary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="command" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{$features_count}}</h4>
                                                <p class="card-text font-small-3 mb-0">@lang('features')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6  ">
                        <div class="card card-statistics">
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-primary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="command" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{$services_count}}</h4>
                                                <p class="card-text font-small-3 mb-0">@lang('services')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 ">
                        <div class="card card-statistics">
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-primary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="flag" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{$countries_count}}</h4>
                                                <p class="card-text font-small-3 mb-0">@lang('countries')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 ">
                        <div class="card card-statistics">
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-primary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="flag" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{$cities_count}}</h4>
                                                <p class="card-text font-small-3 mb-0">@lang('cities')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6  ">
                        <div class="card card-statistics">
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="media">
                                            <div class="avatar bg-light-primary mr-2">
                                                <div class="avatar-content">
                                                    <i data-feather="twitch" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="media-body my-auto">
                                                <h4 class="font-weight-bolder mb-0">{{$reviews_count}}</h4>
                                                <p class="card-text font-small-3 mb-0">@lang('reviews')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Statistics Card -->
                </div>
                <div class="row match-height">
                    <!-- Company Table Card -->
                    <div class="col-lg-12 col-12">
                        <div class="card card-company-table">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>@lang('user')</th>
                                            <th>@lang('owner_name')</th>
                                            <th>@lang('code')</th>
                                            <th>@lang('area')</th>
                                            <th>@lang('price')</th>
                                            <th>@lang('category')</th>
                                            <th>@lang('service')</th>
                                            <th>@lang('country')</th>
                                            <th>@lang('city')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($properties as $property)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <div class="font-weight-bolder">{{$property->user_name}}</div>
                                                            <div class="font-small-2 text-muted">{{$property->user_mobile}}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{$property->owner_name}}</td>
                                                <td>{{$property->code}}</td>
                                                <td>{{$property->area}}</td>
                                                <td>{{$property->price}}</td>
                                                <td>{{$property->category_name}}</td>
                                                <td>{{$property->service_name}}</td>
                                                <td>{{$property->country_name}}</td>
                                                <td>{{$property->city_name}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </section>
            <!-- Dashboard Ecommerce ends -->

        </div>
    </div>


@endsection
@section('scripts')
{{--    <script src="{{asset('dashboard/app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>--}}
{{--    <script src="{{asset('dashboard/app-assets/js/scripts/pages/dashboard-ecommerce.js')}}"></script>--}}
@endsection
