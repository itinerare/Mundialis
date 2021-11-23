@extends('layouts.app')

@section('title')
    Pages:
    @yield('pages-title')
@endsection

@section('sidebar')
    @include('pages._sidebar')
@endsection

@section('content')
    @yield('pages-content')
@endsection

@section('scripts')
@parent
@endsection
