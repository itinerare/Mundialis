@extends('pages.layout')

@section('pages-title')
    {{ $tag }}
@endsection

@section('pages-content')
    {!! breadcrumbs(['Page Tags' => 'special/all-tags', $tag => 'pages/tags/' . $tag]) !!}

    <h1>{{ $tag }}</h1>

    <p>This is a list of all pages with the tag {{ $tag }}.</p>

    @include('pages._page_index_content')
@endsection

@section('scripts')
    @include('pages._page_index_js')
@endsection
