@extends('pages.layout')

@section('pages-title')
    {{ $subject['name'] }}
@endsection

@section('pages-content')
    {!! breadcrumbs([$subject['name'] => $subject['key']]) !!}

    <h1>{{ $subject['name'] }}</h1>

    <p>This is a list of all categories for this subject. Categories can contain both sub-categories and/or
        {{ strtolower($subject['term_plural'] ?? $subject['name']) }}.</p>

    @include('pages.subjects._category_index_content')

    @if (View::exists('pages.subjects._' . $subject['key']))
        <hr />
        @include('pages.subjects._' . $subject['key'])
    @endif
@endsection

@section('scripts')
    @include('pages.subjects._category_index_js')

    @if (View::exists('pages.subjects._' . $subject['key'] . '_js'))
        @include('pages.subjects._' . $subject['key'] . '_js')
    @endif
@endsection
