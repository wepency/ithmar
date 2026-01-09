@extends('layouts.contract-front')

@section('title', '')

@section('content')
    <div id="app">
        <router-view />
    </div>

    <script src="{{asset('js/app.js')}}"></script>
@endsection
