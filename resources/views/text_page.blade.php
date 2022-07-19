@extends('layouts.app')

@section('title')
    {{ $page->title }}
@endsection

@section('content')
    {!! breadcrumbs([$page->title => $page->key]) !!}

    <div class="mb-4">
        <h1>{{ $page->title }}</h1>
        <p>Last updated {{ $page->updated_at->toFormattedDateString() }}</p>
    </div>

    {!! $page->text !!}
@endsection
