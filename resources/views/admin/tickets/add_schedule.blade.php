@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.schedules') }}">Provider Schedules</a></li>
                    <li class="breadcrumb-item active">Add Provider</li>
                </ol>
            </div>
            <h4 class="page-title">Add Provider</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">Add Provider Schedule</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can add provider schedule.
            </p>

            <form action="{{ route('admin.schedules.save') }}" class="ajaxForm" method="post" enctype="multipart/form-data" novalidate>
                @csrf

                @if($user->is_admin)
                <div class="form-group mb-3">
                    <label for="provider">Provider<span class="text-danger">*</span></label>
                    <select name="provider" parsley-trigger="change" class="form-control" id="provider">
                        <option value="">Select Provider</option>
                        @foreach($providers as $provider)
                            <option value="{{$provider->id}}">{{$provider->name}}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="form-group mb-3">
                    <label for="vehicle_type">Vehicle Type<span class="text-danger">*</span></label>
                    <select name="vehicle_type" parsley-trigger="change" class="form-control" id="vehicle_type">
                        <option value="">Select Vehicle Type</option>
                        <option value="standard">Standard</option>
                        <option value="luxury">Luxury</option>
                        <option value="super_luxury">Super Luxury</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="category">Category<span class="text-danger">*</span></label>
                    <select onchange="change_vehicle_type(this)" name="category" parsley-trigger="change" class="form-control" id="category">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option data-type="{{($category->seat_types)}}" value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3 seats_settings border-bottom border-dark pb-3" style="display:none">
                    <div id="seats_settings" class="row"></div>
                </div>

                <div class="form-group mb-3">
                    <label for="route_from">Route From<span class="text-danger">*</span></label>
                    <select data-toggle="select2" name="route_from" parsley-trigger="change" class="form-control" id="route_from">
                        <option value="">Select Route From</option>
                        @foreach($cities as $city)
                            <option value="{{$city->name}}">{{$city->name}} - ({{$city->province}})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="route_to">Route To<span class="text-danger">*</span></label>
                    <select data-toggle="select2" name="route_to" parsley-trigger="change" class="form-control" id="route_to">
                        <option value="">Select Route To</option>
                        @foreach($cities as $city)
                            <option value="{{$city->name}}">{{$city->name}} - ({{$city->province}})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="schedule_date">Schedule Date<span class="text-danger">*</span></label>
                    <input type="text" name="schedule_date" parsley-trigger="change" data-parsley-required placeholder="Enter Schedule Date" class="form-control human_datepicker" id="schedule_date">
                </div>

                <div class="form-group mb-3">
                    <label for="arrival_time">Arrival Time<span class="text-danger">*</span></label>
                    <input type="text" name="arrival_time" parsley-trigger="change" data-parsley-required placeholder="Enter Vehicle Arrival Time" class="form-control human_timepicker" id="arrival_time">
                </div>

                <div class="form-group mb-3">
                    <label for="destination_time">Destination Time<span class="text-danger">*</span></label>
                    <input type="text" name="destination_time" parsley-trigger="change" data-parsley-required placeholder="Enter Vehicle Destination Time" class="form-control human_timepicker" id="destination_time">
                </div>

                <div class="form-group mb-3">
                    <label>Vehicle Image (if any)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input file_uploader" name="image" id="image">
                            <label class="custom-file-label image_label" for="image">Choose file</label>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="desc">Description</label>
                    <textarea rows="4" placeholder="Enter Schedule Description" name="desc" parsley-trigger="change" minlength="10" class="form-control" id="desc"></textarea>
                </div>

                <div class="form-group mb-3 text-right">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        Submit
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('admin_assets') }}/libs/flatpickr/flatpickr.min.js"></script>
<script>
    $(function() {        
        $(".human_datepicker").flatpickr({
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: '{{date("Y-m-d", strtotime("+2 Days"))}}',
            maxDate: '{{date("Y-m-d", strtotime("+365 Days"))}}'
        });

        $(".human_timepicker").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        })
    })
</script>
<script>
    function change_vehicle_type(ele){
        $(".seats_settings").hide();
        let types = $('option:selected',ele).data('type');
        let _html = '';
        for (const type in types) {
            _html += `
                <div class="col">
                    <div class="d-flex flex-wrap align-items-center">
                        <input onchange="add_seat_validation(this)" type="checkbox" name="seats[${type}][check]" id="type_checkbox_${type}" data-parsley-multiple="type_checkbox" value="${type}" data-parsley-mincheck="1" data-parsley-required data-parsley-error-message="Please choose at least 1 type of seats" /> 
                        <label for="type_checkbox_${type}" class="seat_type_label ml-2">${types[type].name} Seats</label>
                    </div>
                    <p class="m-0">Per Seat Price (in PKR)</p>
                    <input type="number" step="any" name="seats[${type}][price]" placeholder="Enter Seat Price" class="form-control seats_inputs mb-2" disabled>
                    <p class="m-0">Total No Of Seats</p>
                    <input type="number" name="seats[${type}][qty]" placeholder="Enter Total Seats" class="form-control seats_inputs" disabled>
                </div>
            `;
        }

        if(_html != ''){
            $("#seats_settings").show().html(_html);
            $(".seats_settings").show();
        }
    }

    function add_seat_validation(ele){
        if($(ele).is(':checked')){
            $(ele).parent().parent().find('.seats_inputs').removeAttr('disabled').attr('data-parsley-required', true).attr('required', 'required');
        }else{
            $(ele).parent().parent().find('.seats_inputs').attr('disabled', 'disabled').removeAttr('data-parsley-required').removeAttr('required');
        }
    }

</script>
@endsection