@extends('pages.layout')

@section('pages-title')
    Special - {{ $tag['name'] }}
@endsection

@section('pages-content')
    {!! breadcrumbs(['Special' => 'special', $tag['name'] => 'special/' . $tag['name']]) !!}

    <h1>Special: {{ $tag['name'] }}</h1>

    <p>This is a list of all pages with the {{ $tag['label'] }} maintenance tag.</p>

    @include('pages._page_index_content')
@endsection

@section('scripts')
    @include('pages._page_index_js')
@endsection
