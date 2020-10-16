@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Schedules</li>
                </ol>
            </div>
            <h4 class="page-title">All Schedules</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="header-title">Schedules</h4>
                <a href="{{ route('admin.schedules.add') }}" class="btn btn-sm btn-primary">Add Provider Schedule</a>
            </div>
            <p class="sub-header">Following is the list of all the schedules.</p>
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="20">S.No</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Date</th>
                        <th>Seats (type: total seats / price)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $k => $schedule)
                    <tr>
                        <td>
                            <p class="m-0 text-center">{{ $k + 1 }}</p>
                        </td>
                        <td><img src="{{ check_file($schedule->image, 'vehicle') }}" width="50" alt="{{ $schedule->name }}"/></td>
                        <td><small>{{ $schedule->provider->name }}</small></td>
                        <td>{{$schedule->route_from}}</td>
                        <td>{{$schedule->route_to}}</td>
                        <td>
                            <p class="m-0"><small>{{ get_date($schedule->date) }}</small></p>
                            <p class="m-0"><small>Time: {{ get_fulltime($schedule->arrival_time, 'h:i A') }}</small></p>
                        </td>
                        
                        <td class="text-center">
                            @foreach($schedule->seats as $seat)
                                <p class="mb-1"><small><strong>{{ucwords($seat->seat_type)}}:</strong> {{$seat->total_seats}} / {{get_price($seat->cost)}}</small></p>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{route('admin.schedules.edit', $schedule->hashid)}}" class="btn btn-warning btn-xs waves-effect waves-light">
                                <span class="btn-label"><i class="fa fa-edit"></i></span>Edit
                            </a>
                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.schedules.delete', $schedule->hashid) }}"  class="btn btn-danger btn-xs waves-effect waves-light">
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