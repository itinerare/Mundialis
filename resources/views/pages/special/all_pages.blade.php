@extends('pages.layout')

@section('pages-title')
    Special - All Pages
@endsection

@section('pages-content')
    {!! breadcrumbs(['Special' => 'special', 'All Pages' => 'special/all-pages']) !!}

    <h1>Special: All Pages</h1>

    <p>This is a list of all pages that currently exist on the site.</p>

    @include('pages._page_index_content')
@endsection

@section('scripts')
    @include('pages._page_index_js')
@endsection
