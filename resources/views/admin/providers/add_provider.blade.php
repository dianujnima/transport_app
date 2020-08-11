@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.providers') }}">Providers</a></li>
                    <li class="breadcrumb-item active">{{ isset($provider) ? 'Edit' : 'Add'}} Provider</li>
                </ol>
            </div>
            <h4 class="page-title">{{ isset($provider) ? 'Edit' : 'Add'}} Provider</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">{{ isset($provider) ? 'Edit' : 'Add'}} Provider</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can {{ isset($provider) ? 'edit' : 'add'}} provider users.
            </p>

            <form action="{{ route('admin.providers.save') }}" class="ajaxForm" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                @if(isset($provider) && $provider->image)
                    <div class="form-group my-3">
                        <img src="{{check_file($provider->image, 'user')}}" alt="{{ $provider->user->full_name ?? 'No Image' }}" class="img-fluid fit-image avatar-xl rounded-circle">
                    </div>
                @endif
                <div class="form-group mb-3">
                    <label>Profile Image</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="profile_img" id="profile_img" accept=".gif, .jpg, .png">
                            <label class="custom-file-label profile_img_label" for="profile_img">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="first_name">First Name<span class="text-danger">*</span></label>
                    <input type="text" name="first_name" parsley-trigger="change" data-parsley-required placeholder="Enter first name" class="form-control" id="first_name" value="{{ $provider->user->first_name ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="last_name">Last Name<span class="text-danger">*</span></label>
                    <input type="text" name="last_name" parsley-trigger="change" data-parsley-required placeholder="Enter last name" class="form-control" id="last_name" value="{{ $provider->user->last_name ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="username">Username<span class="text-danger">*</span></label>
                    <input type="text" @if (!isset($provider)) name="username" parsley-trigger="change" data-parsley-required @else disabled @endif placeholder="Enter username" class="form-control" id="username" value="{{ $provider->user->username ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" placeholder="Enter email" name="email" class="form-control" id="email" value="{{ $provider->user->email ?? '' }}">
                </div>
                @if(!isset($provider))
                <div class="form-group mb-3">
                    <label for="password">Password<span class="text-danger">*</span></label>
                    <input type="password" name="password" parsley-trigger="change" data-parsley-required placeholder="Enter password atleast 8 charactes long" class="form-control" id="password">
                </div>
                @else
                <input type="hidden" value="{{ $provider->hashid }}" name="provider_id" />
                @endif

                <h4 class="border-bottom mb-3 mt-4 pb-2">Company Information</h4>

                <div class="form-group mb-3">
                    <label for="company_name">Company Name<span class="text-danger">*</span></label>
                    <input type="text" placeholder="Enter Company Name" name="company_name" parsley-trigger="change" data-parsley-required class="form-control" id="company_name" value="{{ $provider->name ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="company_ntn">Company NTN #</label>
                    <input type="text" placeholder="Enter Company NTN" name="company_ntn" class="form-control" id="company_ntn" value="{{ $provider->ntn ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="contact_person_name">Contact Person Name<span class="text-danger">*</span></label>
                    <input type="text" placeholder="Enter Contact Person Name" name="contact_person[name]" parsley-trigger="change" data-parsley-required class="form-control" id="contact_person_name" value="{{ $provider->contact_person_info->name ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="contact_person_phone">Contact Person Phone<span class="text-danger">*</span></label>
                    <input type="text" placeholder="Enter Contact Person Phone" name="contact_person[phone]" parsley-trigger="change" data-parsley-required class="form-control" id="contact_person_phone" value="{{ $provider->contact_person_info->phone ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="contact_person_email">Contact Person Email</label>
                    <input type="email" placeholder="Enter Contact Person Email" name="contact_person[email]" class="form-control" id="contact_person_email" value="{{ $provider->contact_person_info->email ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <label for="address">Company Address</label>
                    <input type="text" placeholder="Enter Company Address" name="address" class="form-control" id="address" value="{{ $provider->address ?? '' }}">
                </div>

                <div class="form-group mb-3">
                    <label for="city">Company City</label>
                    <input type="text" placeholder="Enter Company City" name="city" class="form-control" id="city" value="{{ $provider->city ?? '' }}">
                </div>

                <div class="form-group mb-3">
                    <label for="phone_nos">Company Phone Numbers</label>
                    <input type="text" placeholder="Enter Company Phone No: Add multiple by seperating by comma" name="phone_nos" class="form-control" id="phone_nos" value="{{ $provider->phone_nos ?? '' }}">
                </div>

                
                <div class="form-group mb-3">
                    <label>Contract File (For Multiple upload zip file)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input file_uploader" name="contract" id="contract">
                            <label class="custom-file-label image_label" for="contract">Choose file</label>
                        </div>
                    </div>
                </div>
                @if(isset($provider) && $provider->contract)
                    <div class="form-group my-3">
                        <a href="{{check_file($provider->contract)}}" class="mr-2" download>
                            <img src="{{asset('admin_assets/images/file_icon.png')}}" alt="Contract File" class="img-fluid fit-image avatar-xl rounded-circle">
                        </a>
                    </div>
                @endif

                <div class="form-group mb-3 text-right">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        Submit
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@if(isset($provider))
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 mb-3">Update Password For provider</h4>

            <form action="{{ route('admin.providers.update_password') }}" class="ajaxForm" method="post">
                @csrf
                <div class="form-group mb-3">
                    <label for="new_password">Password<span class="text-danger">*</span></label>
                    <input type="password" name="password" parsley-trigger="change" data-parsley-minlength="8" data-parsley-required placeholder="Enter password atleast 8 charactes long" class="form-control" id="new_password">
                </div>
                <div class="form-group mb-3">
                    <label for="password_confirmation">Confirm Password<span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" data-parsley-minlength="8" parsley-trigger="change" data-parsley-equalto="#new_password" data-parsley-required placeholder="Enter confirm password atleast 8 charactes long" class="form-control" id="password_confirmation">
                </div>
                <div class="form-group mb-3 text-right">
                    <input type="hidden" value="{{ $provider->user_id }}" name="provider_user_id" />
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        Submit
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('page-scripts')
<script>
    $('#profile_img').change(function() {
        var filename = $('#profile_img').val();
        if (filename.substring(3,11) == 'fakepath') {
            filename = filename.substring(12);
        }
        if(filename && filename != ''){
            $('.profile_img_label').html(filename);
        }else{
            $('.profile_img_label').html('Choose file');
        }
   });
</script>
@endsection