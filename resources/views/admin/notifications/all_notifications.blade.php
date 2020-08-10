@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notifcations</li>
                </ol>
            </div>
            <h4 class="page-title">All Notifcations</h4>
        </div>
    </div>    
</div>
<div class="row">
    <div class="col-12">
        <div class="card-box">
            @if(newCount($notifications) > 0)
            <button onclick="ajaxRequest(this)" data-url="{{ route('admin.notifications.delete_all') }}" type="button" class="btn btn-sm btn-danger waves-effect waves-light float-right">
                <i class="fa fa-trash"></i> Delete All
            </button>
            <button onclick="ajaxRequest(this)" data-url="{{ route('admin.notifications.all_read') }}" type="button" class="btn btn-sm btn-success waves-effect waves-light float-right mr-2">
                <i class="mdi mdi-check"></i> Mark All as Read
            </button>
            @endif
            <h4 class="header-title mb-4">Manage Notifcations</h4>
            <div class="table-resposnsive mt-3">
                <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100">
                    <thead class="thead-light">
                        <tr>
                            <th width="20">S.No</th>
                            <th>Notification</th>
                            <th width="120">Received</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(newCount($notifications) > 0)
                            @foreach ($notifications as $k => $notification)
                                <tr class="{{ $notification->read_at == null ? 'notification_active_tr' : '' }}">
                                    <th class="title" scope="row">{{$k+1}}</th>
                                    <td>
                                        <a class="{{ $notification->read_at == null ? 'font-weight-bold' : '' }}" href="{{ route('admin.home') }}/{{$notification->data['link'] ?? ''}}">{{ $notification->data['msg'] ?? 'New Notification' }}</a>
                                    </td>
                                    <td class="para">{{ $notification->created_at->diffForHumans() }}</td>
                                    <td>
                                        @if($notification->read_at == null)
                                            <a href="javascript:void(0)" onclick="ajaxRequest(this)" data-url="{{ route('admin.notifications.mark_as_read', $notification->id) }}"><span class="fa fa-envelope-open"></span></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="7">You don't have any notification at this moment</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- end col -->
</div>
<!-- end row -->
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
@endsection