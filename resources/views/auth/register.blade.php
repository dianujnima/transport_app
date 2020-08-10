@extends('layouts.front_auth')
@section('title', 'Register')

@section('auth_content')
<form method="POST" action="{{ route('register') }}" class="custom_form" novalidate>
    <div class="heading">
        <h3 class="text-center">Register an account</h3>
        <p class="text-center">Already have an account? <a class="text-thm" href="{{ route('login') }}">Login!</a></p>
    </div>
    @csrf
    <div class="form-group mb-3">
        <label for="first_name">First Name <span class="text-danger">*</span></label>
        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required class="form-control @error('first_name') is-invalid @enderror" autofocus placeholder="Enter your first name">
        @error('first_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="last_name">Last Name <span class="text-danger">*</span></label>
        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required class="form-control @error('last_name') is-invalid @enderror" placeholder="Enter your last name">
        @error('last_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="phone_no">Phone No</label>
        <input id="phone_no" type="text" name="phone_no" value="{{ old('phone_no') }}" class="form-control @error('phone_no') is-invalid @enderror" placeholder="Enter your last name">
        @error('phone_no')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="address">Address <span class="text-danger">*</span></label>
        <input id="address" type="address" name="address" value="{{ old('address') }}" required class="form-control @error('address') is-invalid @enderror" placeholder="Enter your address">
        @error('address')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="city">City <span class="text-danger">*</span></label>
        <input id="city" type="city" name="city" value="{{ old('city') }}" required class="form-control @error('city') is-invalid @enderror" placeholder="Enter your city">
        @error('city')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group ui_kit_select_search mb-3">
        <label for="state">State <span class="text-danger">*</span></label>
        <select class="form-control selectpicker @error('state') is-invalid @enderror" data-live-search="true" data-width="100%" id="state" name="state" required>
            <option value="">Select State</option>
            @foreach (\CommonHelpers::us_state() as $state)
                <option {{ old('state') == $state ? 'selected' : ''  }} value="{{ $state }}">{{ $state }}</option>
            @endforeach
        </select>
        @error('state')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="zipcode">Zipcode <span class="text-danger">*</span></label>
        <input id="zipcode" type="zipcode" name="zipcode" value="{{ old('zipcode') }}" required class="form-control @error('zipcode') is-invalid @enderror" placeholder="Enter your zipcode">
        @error('zipcode')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="emailaddress">E-Mail Address <span class="text-danger">*</span></label>
        <input id="emailaddress" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email">
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="password">Password  <span class="text-danger">*</span></label>
        <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" required placeholder="Enter your password">
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="password_confirmation">Confirm Password  <span class="text-danger">*</span></label>
        <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
        @error('password_confirmation')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group ui_kit_select_search mb-3">
        <label for="user_role">User Type <span class="text-danger">*</span></label>
        <select class="form-control selectpicker @error('user_role') is-invalid @enderror" data-live-search="true" data-width="100%" id="user_role" name="user_role" required>
            <option value="">Select User Type</option>
            <option {{ request('type') == 'cash_buyer' || old('user_role') == 'cash_buyer' ? 'selected' : ''  }} value="cash_buyer">Cash Buyer</option>
            <option {{ request('type') == 'wholesaler' || old('user_role') == 'wholesaler' ? 'selected' : ''  }}  value="wholesaler">Wholesaler</option>
            
            {{-- @foreach(\App\Package::all() as $package)
                <option {{ request('type') == $package->type || old('user_role') == $package->type ? 'selected' : ''  }} value="{{$package->type}}">{{ $package->name }}</option>
             @endforeach --}}
        </select>
        @error('user_role')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    @if ($errors->has('active'))
    <p class="alert alert-danger mt-2">
        <span class="help-block">
            <strong>{{ $errors->first('active') }}</strong>
        </span>
    </p>
    @endif

    <div class="form-group mb-0 text-center">
        <button class="btn btn-log btn-block btn-thm" type="submit">Register</button>
    </div>

</form>
@endsection