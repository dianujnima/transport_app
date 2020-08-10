@extends('errors.layout')
@section('content')
<section class="our-error bgc-f7">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 text-center">
                <div class="error_page footer_apps_widget">
                    @hasSection('icon')
                        <img class="img-fluid" src="{{ asset('frontend_assets') }}/images/resource/@yield('icon')" alt="error.png">
                    @else
                        <img class="img-fluid" src="{{ asset('frontend_assets') }}/images/resource/general_error.png" alt="error.png">
                    @endif
                    <div class="erro_code mt-3"><h1>@yield('code')</h1></div>
                    <h4 class="text-dark mt-3 px-5" style="line-height:1.67">@yield('message')</h4>
                </div>
                <a class="btn btn_error btn-thm" href="{{route('front.home')}}">Back To Home</a>
            </div>
        </div>
    </div>
</section>
@endsection