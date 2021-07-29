@extends('pages.layout')

@section('pages-title') {{ $category->name }} @endsection

@section('pages-content')
{!! breadcrumbs(['Language' => 'language'] + ($category->parent ? [$category->parent->name => 'language/lexicon/'.$category->parent->id] : []) + [$category->name => 'language/lexicon/'.$category->id]) !!}

<h1>{{ $category->name }}</h1>

{!! $category->description !!}

@if($category->children()->count())
<h2>Sub-Categories</h2>
    @include('pages.subjects._lang_category_index_content', ['categories' => $category->children()->paginate(10)])

    <hr/>
@endif

<h2>
    Entries
    @if(Auth::check() && Auth::user()->canWrite)
        <a href="{{ url('language/lexicon/create?category_id='.$category->id) }}" class="btn btn-secondary float-right"><i class="fas fa-plus"></i> Create New Entry</a>
    @endif
</h2>

@include('pages.subjects._lang_entry_index_content')

@endsection

@section('scripts')
@include('pages.subjects._language_js')

@endsection
