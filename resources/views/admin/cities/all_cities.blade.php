@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Cities</li>
                </ol>
            </div>
            <h4 class="page-title">All Cities</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title mb-4">{{isset($city) ? 'Edit' : 'Add' }} City</h4>
            <form action="{{ route('admin.cities.save') }}" class="ajaxForm" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="row align-items-end">
                    <div class="form-group mb-3 col-md-5">
                        <label for="name">City Name<span class="text-danger">*</span></label>
                        <input type="text" name="name" parsley-trigger="change" data-parsley-required placeholder="Enter City name" class="form-control" id="name" value="{{ $city->name ?? '' }}">
                    </div>
                    <div class="form-group mb-3 col-md-5">
                        <label for="province">Province<span class="text-danger">*</span></label>
                        <select name="province" id="province" class="form-control" required>
                            <option value="">Select Province</option>
                            @foreach (get_provinces() as $item)
                                <option value="{{$item}}">{{$item}}</option>    
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group mb-3 col-md-2">
                        @if(isset($city))
                        <input type="hidden" value="{{ $city->hashid }}" name="city_id" />
                        @endif
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Cities</h4>
            </div>
            <p class="sub-header">Following is the list of all the cities.</p>
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="20">S.No</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Added On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cities as $k => $city)
                    <tr>
                        <td>
                            <p class="m-0 text-center">{{ $k + 1 }}</p>
                        </td>
                        <td>{{ $city->name }}</td>
                        <td>{{ $city->slug }}</td>
                        <td>
                            <p class="m-0"><small>{{ get_date($city->created_on) }}</small></p>
                        </td>
                        <td>
                            <a href="{{route('admin.cities.edit', $city->hashid)}}" class="btn btn-warning btn-xs waves-effect waves-light">
                                <span class="btn-label"><i class="fa fa-edit"></i></span>Edit
                            </a>
                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.cities.delete', $city->hashid) }}"  class="btn btn-danger btn-xs waves-effect waves-light">
                                <span class="btn-label"><i class="fa fa-trash"></i></span>Delete
                            </button>
                        </td>
                    </tr>
                
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable', ['load_swtichery' => true])
@endsection