@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tickets</li>
                </ol>
            </div>
            <h4 class="page-title">All Tickets</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-3">
                <h4 class="header-title">Filters</h4>
            </div>
            <form action="{{route('admin.tickets')}}" method="GET">
                <div class="row">
                    <div class="form-group mb-3 col-md-3">
                        <label for="name">Ticket No<span class="text-danger">*</span></label>
                        <input type="text" name="ticket_no" placeholder="Enter Ticket No" class="form-control" id="name" value="{{request('ticket_no') ?? ''}}">
                    </div>
                    <div class="form-group mb-3 col-md-3">
                        <label for="from">Booking Date From<span class="text-danger">*</span></label>
                        <input type="text" name="from" placeholder="Enter From" class="form-control human_datepicker" id="from" value="{{$from}}">
                    </div>
                    <div class="form-group mb-3 col-md-3">
                        <label for="to">Booking Date To<span class="text-danger">*</span></label>
                        <input type="text" name="to" placeholder="Enter To" class="form-control human_datepicker" id="to" value="{{$to}}">
                    </div>
                    
                    <div class="form-group mb-3 col-md-2 pt-3">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Search
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
                <h4 class="header-title">Tickets</h4>
                <!-- <a href="{{ route('admin.schedules.add') }}" class="btn btn-sm btn-primary">Add Provider Schedule</a> -->
            </div>
            <p class="sub-header">Following is the list of all the Booked Tickets.</p>
            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                <thead>
                    <tr>
                        <th width="20">S.No</th>
                        <th>Ticket #</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Vehicle</th>
                        <th>Booking Date</th>
                        <th>Seats <small>(type: total seats / total price)</small></th>
                        <th>Status</th>
                        <th>Booked By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $k => $ticket)
                    <tr>
                        <td>
                            <p class="m-0 text-center">{{ $k + 1 }}</p>
                        </td>
                        <td><small>{{ $ticket->ticket_no }}</small></td>
                        <td>{{$ticket->schedule->route_from}}</td>
                        <td>{{$ticket->schedule->route_to}}</td>
                        <td class="text-center"><span class="badge badge-info"><i class="{{$ticket->schedule->category->icon ?? ''}}"></i> {{$ticket->schedule->category->name ?? '-'}}</span></td>
                        <td>
                            <p class="m-0"><small>{{ get_date($ticket->booking_date) }}</small></p>
                            <p class="m-0"><small>Arrival Time: {{ get_fulltime($ticket->schedule->arrival_time, 'h:i A') }}</small></p>
                            <p class="m-0"><small>Destination Time: {{ get_fulltime($ticket->schedule->arrival_time, 'h:i A') }}</small></p>
                        </td>
                        <td class="text-center">
                            @foreach($ticket->seats as $seat)
                                <p class="mb-1"><small><strong>{{ucwords($seat->seat_type)}}:</strong> {{$seat->total_seats}} / {{get_price($seat->total_cost)}}</small></p>
                            @endforeach
                        </td>
                        <td class="text-center">
                            @if($ticket->status == 'hold')
                                <span class="badge badge-warning">{{ucwords($ticket->status)}}</span>
                                @if($ticket->transaction_id)
                                <p class="mb-0 mt-1">
                                    <button type="button" onclick="verifyTransaction('{{ $ticket->ticket_no }}', '{{get_price($ticket->total_amount)}}', '{{$ticket->transaction_method}}', '{{$ticket->transaction_id}}')"  class="btn btn-success btn-xs waves-effect waves-light">Verify Transaction</button>
                                </p>
                                @endif
                            @elseif($ticket->status == 'cancelled')
                                <p class="m-0">
                                    <span class="badge badge-danger">{{ucwords($ticket->status)}}</small>
                                </p>
                                
                            @elseif($ticket->status == 'booked')
                                <p class="m-0">
                                    <span class="badge badge-success">Transaction: {{$ticket->transaction_id}}</small>
                                </p>
                                <span class="badge badge-success">{{ucwords($ticket->status)}}</span>
                            @endif
                        </td>
                        <td><small>{{ $ticket->user->full_name ?? '-' }}</small></td>
                        <td>
                            @if($ticket->status == 'cancelled')
                                <span class="badge badge-danger">Cancelled at: {{get_fulltime($ticket->cancelled_at)}}</span>
                            @else
                                @if(strtotime($ticket->booking_date) > time())
                                <button type="button" onclick="cancelTicket('{{ $ticket->ticket_no }}')" class="btn btn-danger btn-xs waves-effect waves-light">
                                    <span class="btn-label"><i class="fa fa-times"></i></span>Cancel Ticket
                                </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="cancel_ticket">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Cancel Ticket # <span class="ticket_no"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <form class="ajaxForm" action="{{route('admin.tickets.cancel')}}" method="POST" novalidate>
            @csrf
            <div class="form-group mb-3">
                <label for="cancel_reason">Cancellation Reason</label>
                <textarea rows="4" placeholder="Enter Cancellation Reason" name="cancel_reason" parsley-trigger="change" minlength="10" class="form-control" id="cancel_reason" required></textarea>
            </div>
            <div class="form-group mb-3 text-right">
                <input type="hidden" class="ticket_no_value" name="ticket_no">
                <button class="btn btn-primary waves-effect waves-light" type="submit">
                    Submit
                </button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Verify transaction Modal -->
<div class="modal fade" id="verify_transaction">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Verify Transaction For Ticket # <span class="ticket_no"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <table class="table table-bordered no-datatable">
            <tr>
                <th>Ticket #</th>
                <td><span class="ticket_no"></span></td>
            </tr>
            <tr>
                <th>Total Cost #</th>
                <td><span class="total_cost"></span></td>
            </tr>
            <tr>
                <th>Transaction Method</th>
                <td><span class="transaction_method"></span></td>
            </tr>
            <tr>
                <th>Transaction #</th>
                <td><span class="transaction_no"></span></td>
            </tr>
        </table>
        <form class="ajaxForm" action="{{route('admin.tickets.verify')}}" method="POST" novalidate>
            @csrf
            <div class="form-group mb-3 text-right">
                <input type="hidden" class="ticket_no_value" name="ticket_no">
                <button class="btn btn-primary waves-effect waves-light" type="submit">
                    Verify Ticket
                </button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
<script>
    function verifyTransaction(ticket_no, total_cost, method, transaction){
        $(".ticket_no_value").val(ticket_no);
        $(".ticket_no").text(ticket_no);
        $(".total_cost").text(total_cost);
        $(".transaction_method").text(method);
        $(".transaction_no").text(transaction);
        $('#verify_transaction').modal('show');
    }

    function cancelTicket(ticket_no, total_cost, method, transaction){
        $(".ticket_no_value").val(ticket_no);
        $(".ticket_no").text(ticket_no);
        $('#cancel_ticket').modal('show');
    }
</script>
@endsection