@extends('pages.layout')

@section('pages-title') {{ $subject['name'] }} @endsection

@section('pages-content')
{!! breadcrumbs(['Pages' => 'categories', $subject['name'] => 'categories/'.$subject['key']]) !!}

<h1>{{ $subject['name'] }}</h1>

<p>This is a list of all categories for this subject. Categories can contain both sub-categories and/or {{ strtolower($subject['term']) }}s.</p>

<div class="text-right mb-3">
    <div class="btn-group">
        <button type="button" class="btn btn-secondary active category-grid-view-button" data-toggle="tooltip" title="Grid View" alt="Grid View"><i class="fas fa-th"></i></button>
        <button type="button" class="btn btn-secondary category-list-view-button" data-toggle="tooltip" title="List View" alt="List View"><i class="fas fa-bars"></i></button>
    </div>
</div>

@include('pages._category_index_content')

@endsection

@section('scripts')
@include('pages._category_index_js')
@endsection
