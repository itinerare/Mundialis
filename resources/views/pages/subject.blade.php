@extends('pages.layout')

@section('pages-title') {{ $subject['name'] }} @endsection

@section('pages-content')
{!! breadcrumbs([$subject['name'] => $subject['key']]) !!}

<h1>{{ $subject['name'] }}</h1>

<p>This is a list of all categories for this subject. Categories can contain both sub-categories and/or {{ strtolower($subject['term']) }}s.</p>

@include('pages._category_index_content')

@endsection

@section('scripts')
@include('pages._category_index_js')
@endsection
