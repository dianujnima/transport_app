@extends('layouts.admin')

@section('content')

<style>
    
.hk_custom .widget-rounded-circle {
    padding: 0;
    padding-top: 15px;
    padding-left: 5px;
    padding-right: 5px;
}
.hk_custom h3 {
    font-size: 18px;
    margin-bottom: 4px;
}
.hk_custom p {
    font-size: 12px;
}

#revenue_chart,
#orders_chart{
    max-height: 350px;
}
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->

@endsection

@section('page-scripts')

<!-- Plugins js-->
<script src="{{ asset('admin_assets') }}/libs/flatpickr/flatpickr.min.js"></script>
<script src="{{ asset('admin_assets') }}/libs/jquery-knob/jquery.knob.min.js"></script>
<script src="{{ asset('admin_assets') }}/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="{{ asset('admin_assets') }}/libs/chart-js/chart-js.min.js"></script>

<!-- Dashboar 1 init js-->
@endsection