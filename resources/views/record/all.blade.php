@extends('layouts.app')

@section('content')
    <h1>monthly report for all user</h1>
    <script type="text/javascript" src="{{ url('js/d3.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/crossfilter.js') }}"></script>

    <script type="text/javascript" src="{{ url('js/dc.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/reductio.min.js') }}"></script>

    <div id="monthlyReportChart"></div>
    <script src="{{ url('js/record-report-all.js') }}"></script>
@endsection