@extends('pages.layout')

@section('pages-title')
    Special - Unlinked Pages
@endsection

@section('pages-content')
    {!! breadcrumbs(['Special' => 'special', 'Wanted Pages' => 'special/unlinked-pages']) !!}

    <h1>Special: Unlinked-To Pages</h1>

    <p>This is a list of unlinked-to pages. Note that this list only counts links made within page content.</p>

    {!! $pages->render() !!}

    <ul>
        @foreach ($pages as $versionPage)
            <li>
                {!! $versionPage->displayName !!}
            </li>
        @endforeach
    </ul>

    {!! $pages->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }}
        found.</div>
@endsection
