@extends('pages.layout')

@section('pages-title')
    {{ $category->name }}
@endsection

@section('pages-content')
    {!! breadcrumbs(
        [$category->subject['name'] => $category->subject['key']] +
            ($category->parent
                ? [$category->parent->name => $category->subject['key'] . '/categories/' . $category->parent->id]
                : []) + [$category->name => $category->subject['key'] . '/categories/' . $category->id],
    ) !!}

    <h1>{{ $category->name }}
        @if (Auth::check() && Auth::user()->canWrite)
            <a href="{{ url('pages/create/' . $category->id) }}" class="btn btn-secondary float-right"><i
                    class="fas fa-plus"></i> Create New {{ $category->subject['term'] }}</a>
        @endif
    </h1>

    {!! $category->description !!}

    @if ($category->children()->count())
        <h2>Sub-Categories</h2>
        @include('pages.subjects._category_index_content', [
            'categories' => $category->children()->paginate(10),
        ])

        <hr />
    @endif

    <h2>Pages</h2>

    @include('pages._page_index_content')
@endsection

@section('scripts')
    @include('pages.subjects._category_index_js')
    @include('pages._page_index_js')
@endsection
