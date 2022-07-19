@extends('pages.layout')

@section('pages-title')
    Special - Unwatched Pages
@endsection

@section('pages-content')
    {!! breadcrumbs(['Special' => 'special', 'Unwatched Pages' => 'special/unwatched-pages']) !!}

    <h1>Special: Unwatched Pages</h1>

    <p>This is a list of pages with no watchers.</p>

    {!! $pages->render() !!}

    <ul>
        @foreach ($pages as $watchedPage)
            <li>
                {!! $watchedPage->displayName !!}
            </li>
        @endforeach
    </ul>

    {!! $pages->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }}
        found.</div>
@endsection
