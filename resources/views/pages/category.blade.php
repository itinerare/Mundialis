@extends('pages.layout')

@section('pages-title') {{ $category->name }} @endsection

@section('pages-content')
{!! breadcrumbs(['Pages' => 'pages', $category->subject['name'] => 'pages/'.$category->subject['key']] + ($category->parent ? [$category->parent->name => 'pages/'.$category->subject['key'].'/categories/'.$category->parent->id] : []) + [$category->name => 'pages/'.$category->subject['key'].'/categories/'.$category->id]) !!}

<h1>{{ $category->name }}
    @if(Auth::check() && Auth::user()->canWrite)
        <a href="{{ url('pages/create/'.$category->id) }}" class="btn btn-secondary float-right"><i class="fas fa-plus"></i> Create New {{ $category->subject['term'] }}</a>
    @endif
</h1>

{!! $category->description !!}

@if($category->children()->count())
<h2>Sub-Categories</h2>
    <div class="text-right mb-3">
        <div class="btn-group">
            <button type="button" class="btn btn-secondary active category-grid-view-button" data-toggle="tooltip" title="Grid View" alt="Grid View"><i class="fas fa-th"></i></button>
            <button type="button" class="btn btn-secondary category-list-view-button" data-toggle="tooltip" title="List View" alt="List View"><i class="fas fa-bars"></i></button>
        </div>
    </div>

    @include('pages._category_index_content', ['categories' => $category->children()->paginate(10)])

    <hr/>
@endif

<h2>Pages</h2>

@include('pages._page_index_content')

@endsection

@section('scripts')
@include('pages._category_index_js')
@include('pages._page_index_js')
@endsection
