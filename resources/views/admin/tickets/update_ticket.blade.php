@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Ticket #{{$ticket->hashid}}</li>
                </ol>
            </div>
            <h4 class="page-title">Ticket #{{$ticket->hashid}}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">

            <!-- project card -->
            <div class="card d-block">
                <div class="card-body">
                    <h4 class="mb-3 mt-0 font-18">{{$ticket->subject}}</h4>

                    <div class="clerfix"></div>

                    <div class="row">
                        <div class="col-md-4">
                            <!-- Ticket type -->
                            <label class="mt-2 mb-1">Ticket Type :</label>
                            <p>
                                <i class="fa fa-ticket font-18 text-success mr-1 align-middle"></i> {{ucwords($ticket->type)}}  Support
                            </p>
                            <!-- end Ticket Type -->
                        </div>
                    </div> <!-- end row -->

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Reported by -->
                            <label class="mt-2 mb-1">Reported By :</label>
                            <div class="media">
                                <img src="{{check_file($ticket->user->image, 'user')}}" alt="{{$ticket->user->fullname}}" class="rounded-circle mr-2" height="24">
                                <div class="media-body">
                                    <p> {{$ticket->user->fullname}} </p>
                                </div>
                            </div>
                            <!-- end Reported by -->
                        </div> <!-- end col -->

                        <div class="col-md-6">
                            <!-- assignee -->
                            <label class="mt-2 mb-1">Resolved On :</label>
                            <div class="media">
                                @if($ticket->closed_at !== null)
                                    <p>{{get_fulltime($ticket->closed_at)}}</p>
                                @else 
                                    <span class="badge badge-danger">Not Resolved Yet!</span>
                                @endif
                            </div>
                            <!-- end assignee -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                    <div class="row">
                        <div class="col-md-6">
                            <!-- assignee -->
                            <label class="mt-2 mb-1">Created On :</label>
                            <p>{{get_fulltime($ticket->created_at)}}</p>
                            <!-- end assignee -->
                        </div> <!-- end col -->

                        <div class="col-md-6">
                            <!-- assignee -->
                            <label class="mt-2 mb-1">Updated On :</label>
                            <p>{{get_fulltime($ticket->updated_at)}}</p>
                            <!-- end assignee -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Status -->
                            <label class="mt-2 mb-1">Status :</label>
                            <div class="form-row">
                                <div class="col-auto">
                                    <span class="badge badge-{{$ticket->status == 'open' ? 'danger' : 'success'}}">{{ ucwords($ticket->status) }}</span>
                                    @if($ticket->closed_at === null)
                                    <div class="form-row mt-2">
                                        <div class="col-auto">
                                            <select onchange="changeStatus('{{$ticket->hashid}}', 'status', this.value)" class="form-control">
                                                <option {{$ticket->status == 'open' ? 'selected' : ''}} value="open">Open</option>
                                                <option {{$ticket->status == 'in progress' ? 'selected' : ''}} value="in progress">In Progress</option>
                                                <option {{$ticket->status == 'closed' ? 'selected' : ''}} value="closed">Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                    
                                </div>
                            </div>
                            <!-- end Status -->
                        </div> <!-- end col -->

                        <div class="col-md-6">
                            <!-- Priority -->
                            <label class="mt-2 mb-1">Priority :</label>
                            <div class="form-row">
                                <div class="col-auto">
                                    <span class="badge {{ ticket_priority($ticket->priority) }}">{{ ucwords($ticket->priority) }}</span>
                                    @if($ticket->closed_at === null)
                                    <div class="form-row mt-1">
                                        <div class="col-auto">
                                            <select onchange="changeStatus('{{$ticket->hashid}}', 'priority', this.value)" class="form-control">
                                                <option {{$ticket->priority == 'low' ? 'selected' : ''}} value="low">Low</option>
                                                <option {{$ticket->priority == 'medium' ? 'selected' : ''}} value="medium">Medium</option>
                                                <option {{$ticket->priority == 'high' ? 'selected' : ''}} value="high">High</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <!-- end Priority -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                    <label class="mt-4 mb-1">Overview :</label>

                    <p class="text-muted mb-0">
                        {{$ticket->msg}}
                    </p>

                </div> <!-- end card-body-->
                
            </div> <!-- end card-->

            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="mb-4 mt-0 font-16">Discussion ({{newCount($discussions)}})</h4>

                    <div class="clerfix"></div>
                    <div class="scroll_discussion slimscroll noti-scroll custom-scrollbar">
                        @forelse ($discussions as $discussion)
                            <div class="media @if(!$loop->first) mt-3 @endif px-3">
                                @if($discussion->is_support)
                                    <img class="mr-2 rounded-circle" src="{{ asset('frontend_assets') }}/images/favicon.png" alt="Cash Investors Support" height="32">
                                @else
                                    <img class="mr-2 rounded-circle" src="{{ check_file($discussion->user->iamge, 'user') }}" alt="{{ $discussion->user->full_name }}" height="32">
                                @endif
                                <div class="media-body">
                                    <h5 class="mt-0 mb-1">{{ $discussion->is_support ? 'Cash Investors Support' : $discussion->user->full_name }} <small class="text-muted float-right">{{ $discussion->created_at->diffForHumans() }}</small></h5>
                                    {{ $discussion->msg }}
                                    <br>
                                </div>
                            </div>
                        @empty
                            
                        @endforelse
                    </div>
                    @if($ticket->closed_at === null)
                    <div class="border rounded mt-4">
                        <form action="{{route('admin.tickets.send_message')}}" class="ajaxForm nopopup" method="POST" class="comment-area-box">
                            @csrf
                            <textarea rows="3" name="msg" class="form-control border-0 resize-none" placeholder="Your message..."></textarea>
                            <div class="p-2 bg-light text-right">
                                <input name="ticket_id" type="hidden" value="{{ $ticket->hashid }}" />
                                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-paper-plane mr-1"></i>Submit</button>
                            </div>
                        </form>
                    </div> <!-- end .border-->
                    @endif

                </div> <!-- end card-body-->
            </div>
            <!-- end card-->
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
    function changeStatus(id, type, value){
        var url = "{{route('admin.tickets.update_status')}}";
        var params = {
            ticket_id: id,
            type: type,
            value: value
        };
        getAjaxRequests(url, params, 'post', function(res){
            toast(res.msg, "Success!", 'success');
            setTimeout(function(){
                window.location.reload();     
            }, 1200);
        });
    }
</script>
@endsection