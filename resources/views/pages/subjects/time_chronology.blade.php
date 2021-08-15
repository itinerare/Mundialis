@extends('pages.layout')

@section('title') Time: {{ $chronology->name }} @endsection

@section('pages-content')
{!! breadcrumbs(['Time & Events' => 'time'] + ($chronology->parent ? [$chronology->parent->name => 'time/chronologies/'.$category->parent->id] : []) + [$chronology->name => 'time/chronologies/'.$chronology->id]) !!}

<h1>{{ $chronology->name }}</h1>

{!! $chronology->description !!}

@if($chronology->children()->count())
<h2>Sub-Categories</h2>
    @include('pages.subjects._time_category_index_content', ['categories' => $category->children()->paginate(10)])

    <hr/>
@endif

<h2>Pages</h2>

@include('pages._page_index_content')

@endsection

@section('scripts')
@include('pages.subjects._time_js')
@include('pages._page_index_js')
@endsection
