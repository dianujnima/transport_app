@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </div>
            <h4 class="page-title">All Categories</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title mb-4">{{isset($category) ? 'Edit' : 'Add' }} Category</h4>
            <form action="{{ route('admin.categories.save') }}" class="ajaxForm" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                @if(isset($category->image) && $category->image)
                    <div class="form-group my-3">
                        <img src="{{check_file($category->image, 'user')}}" alt="{{ $category->full_name ?? 'No Image' }}" class="img-fluid fit-image avatar-xl rounded-circle">
                    </div>
                @endif
                <div class="form-group mb-3">
                    <label>Category Image</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input file_uploader" name="category_image" id="category_image" accept=".gif, .jpg, .png">
                            <label class="custom-file-label image_label" for="category_image">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="row align-items-end">
                    <div class="form-group mb-3 col-md-5">
                        <label for="name">Category Name<span class="text-danger">*</span></label>
                        <input type="text" name="name" parsley-trigger="change" data-parsley-required placeholder="Enter Category name" class="form-control" id="name" value="{{ $category->name ?? '' }}">
                    </div>
                    
                    <div class="form-group mb-3 col-md-2">
                        @if(isset($category))
                        <input type="hidden" value="{{ $category->hashid }}" name="category_id" />
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
                <h4 class="header-title">Categories</h4>
            </div>
            <p class="sub-header">Following is the list of all the categories.</p>
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="20">S.No</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Added On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $k => $category)
                    <tr>
                        <td>
                            <p class="m-0 text-center">{{ $k + 1 }}</p>
                        </td>
                        <td><img src="{{ check_file($category->image, 'categories') }}" width="60" alt="{{$category->name}}"/></td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>
                            <p class="m-0"><small>{{ get_date($category->created_on) }}</small></p>
                        </td>
                        
                        <td class="text-center">
                            <p class="mb-1">
                                <span class="badge badge-{{$category->is_active ? 'success' : 'danger'}}">{{$category->is_active ? 'Active' : 'Disabled'}}</span>
                            </p>
                            <p class="m-0 text-center">
                                <input type="checkbox" class="nopopup" onchange="ajaxRequest(this)" data-url="{{ route('admin.categories.update_status', $category->hashid) }}" {{ $category->is_active ? 'checked' : ''}} data-toggle="switchery" data-size="small" data-color="#1bb99a" />
                            </p>
                        </td>
                        <td>
                            <a href="{{route('admin.categories.edit', $category->hashid)}}" class="btn btn-warning btn-xs waves-effect waves-light">
                                <span class="btn-label"><i class="fa fa-edit"></i></span>Edit
                            </a>
                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.categories.delete', $category->hashid) }}"  class="btn btn-danger btn-xs waves-effect waves-light">
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