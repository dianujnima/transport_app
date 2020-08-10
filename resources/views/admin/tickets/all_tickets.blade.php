@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Support Tickets</li>
                </ol>
            </div>
            <h4 class="page-title">All Support Tickets</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-xl-4">
        <div class="widget-rounded-circle card-box">
            <a href="{{route('admin.tickets')}}" class="d-block">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-primary">
                            <i class="fe-tag font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{number_format($total_tickets ?? 0)}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Tickets</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </a>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-4">
        <div class="widget-rounded-circle card-box">
            <a href="{{route('admin.tickets')}}" class="d-block">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-warning">
                            <i class="fe-clock font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{number_format($pending_tickets ?? 0)}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Pending Tickets</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </a>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-4">
        <div class="widget-rounded-circle card-box">
            <a href="{{route('admin.tickets', 'closed')}}" class="d-block">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-success">
                            <i class="fe-check-circle font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">{{number_format($closed_tickets ?? 0)}}</span></h3>
                            <p class="text-muted mb-1 text-truncate">Closed Tickets</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </a>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
    
</div>
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <h4 class="header-title mb-4">Manage Tickets</h4>
            <div class="table-responsive">
                <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" id="tickets-table">
                    <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Ticket #</th>
                        <th>Requested By</th>
                        <th>Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th class="hidden-sm">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach ($tickets as $k => $ticket)  
                        <tr>
                            <td>{{$k + 1}}</td>
                            <td>
                                <a href="{{route('admin.tickets.view', $ticket->hashid) }}">
                                    <small>{{$ticket->hashid}}</small>
                                </a>
                            </td>
                            <td>
                                <img src="{{check_file($ticket->user->image, 'user')}}" alt="{{$ticket->user->full_name}}" title="contact-img" class="rounded-circle avatar-xs" />
                                <span class="ml-2">{{$ticket->user->full_name}}</span>
                            </td>

                            <td>
                                {{$ticket->subject}}
                            </td>

                            <td>
                                <span class="badge {{ ticket_priority($ticket->priority) }}">{{ ucwords($ticket->priority) }}</span>
                            </td>

                            <td>
                                <span class="badge badge-{{$ticket->status == 'open' ? 'danger' : 'success'}}">{{ ucwords($ticket->status) }}</span>
                            </td>

                            <td>
                                {{ get_date($ticket->updated_at) }}
                            </td>

                            <td>
                                <a href="{{route('admin.tickets.view', $ticket->hashid) }}" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                        @endforeach
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