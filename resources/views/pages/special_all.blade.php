@extends('pages.layout')

@section('pages-title') Special: All Pages @endsection

@section('pages-content')
{!! breadcrumbs(['Pages' => 'pages', 'Special: All Pages' => 'special/all-pages']) !!}

<h1>Special: All Pages</h1>

<p>This is a list of all pages that currently exist on the site.</p>

<div class="text-right mb-3">
    <div class="btn-group">
        <button type="button" class="btn btn-secondary active page-grid-view-button" data-toggle="tooltip" title="Grid View" alt="Grid View"><i class="fas fa-th"></i></button>
        <button type="button" class="btn btn-secondary page-list-view-button" data-toggle="tooltip" title="List View" alt="List View"><i class="fas fa-bars"></i></button>
    </div>
</div>

@include('pages._page_index_content')

@endsection

@section('scripts')
@include('pages._category_index_js')
@include('pages._page_index_js')
@endsection
