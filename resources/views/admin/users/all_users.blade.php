@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">users</li>
                </ol>
            </div>
            <h4 class="page-title">All Users</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Users</h4>
            </div>
            <p class="sub-header">Following is the list of all the users.</p>
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="20">S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th width="40">Phone Number</th>
                        <th>Type</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th>Subscription Ends On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $k => $user)
                    <tr>
                        <td>
                            <p class="m-0 text-center">{{ $k + 1 }}</p>
                        </td>
                        <td>{{ $user->fullname }}</td>
                        <td><small>{{ $user->email }}</small></td>
                        <td><small>{{ $user->phone_no }}</small></td>
                        <td><small class="badge badge-{{user_type_colors($user->user_role)}}">{{ $user->user_type }}</small></td>
                        <td>
                            <p class="m-0"><small>{{ get_date($user->created_on) }}</small></p>
                        </td>
                        
                        <td class="text-center">
                            <p class="mb-1">
                                <span class="badge badge-{{$user->is_active ? 'success' : 'danger'}}">{{$user->is_active ? 'Active' : 'Disabled'}}</span>
                            </p>
                            <p class="m-0">
                                <input type="checkbox" class="nopopup" onchange="ajaxRequest(this)" data-url="{{ route('admin.users.change_status', $user->hashid) }}" {{ $user->is_active ? 'checked' : ''}} data-toggle="switchery" data-size="small" data-color="#1bb99a" />
                            </p>
                        </td>
                        <td>
                            <p class="m-0"><small>{{ get_date($user->subscription_ends_at) }}</small></p>
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