@extends('layouts.main.main')

@section('title', 'Dashboard')

@section('wrapper_class', 'wallet-open active')

@push('styles')
	<link rel="stylesheet" href="{{asset('vendor/bootstrap-select-country/css/bootstrap-select-country.min.css')}}">
	<link rel="stylesheet" href="{{asset('vendor/jquery-nice-select/css/nice-select.css')}}">
	<link href="{{asset('vendor/datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
	<link href="{{asset('vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('vendor/swiper/css/swiper-bundle.min.css')}}">
@endpush

@section('content')
	@include('components.graphic')
	@include('dashboard.walletbar')
@endsection

@push('scripts')
	<script src="{{asset('vendor/chart.js/Chart.bundle.min.js')}}"></script>
	<script src="{{asset('vendor/apexchart/apexchart.js')}}"></script>
	<script src="{{asset('vendor/peity/jquery.peity.min.js')}}"></script>
	<script src="{{asset('vendor/jquery-nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{asset('vendor/swiper/js/swiper-bundle.min.js')}}"></script>
	<script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('js/plugins-init/datatables.init.js')}}"></script>
	<script src="{{asset('js/dashboard/dashboard-1.js')}}"></script>
	<script src="{{asset('vendor/bootstrap-datetimepicker/js/moment.js')}}"></script>
	<script src="{{asset('vendor/datepicker/js/bootstrap-datepicker.min.js')}}"></script>
	<script src="{{asset('vendor/bootstrap-select-country/js/bootstrap-select-country.min.js')}}"></script>
@endpush