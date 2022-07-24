@extends('pages.layout')

@section('pages-title')
    Special - {{ ucfirst($mode) }} Revised Pages
@endsection

@section('pages-content')
    {!! breadcrumbs(['Special' => 'special', 'Revised Pages' => 'special/' . $mode . '-revised-pages']) !!}

    <h1>Special: {{ ucfirst($mode) }} Revised Pages</h1>

    <p>This is a list of pages with the {{ $mode == 'least' ? 'fewest' : 'most' }} revisions.</p>

    {!! $pages->render() !!}

    <ul>
        @foreach ($pages as $versionPage)
            <li>
                {!! $versionPage->displayName !!} ({{ $versionPage->versions->count() }}
                revision{{ $versionPage->versions->count() != 1 ? 's' : '' }})
            </li>
        @endforeach
    </ul>

    {!! $pages->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }}
        found.</div>
@endsection
