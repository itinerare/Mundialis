@extends('pages.layout')

@section('pages-title')
    Special - Protected Pages
@endsection

@section('pages-content')
    {!! breadcrumbs(['Special' => 'special', 'Protected Pages' => 'special/protected-pages']) !!}

    <h1>Special: Protected Pages</h1>

    <p>This is a list of protected pages.</p>

    {!! $pages->render() !!}

    <ul>
        @foreach ($pages as $protectedPage)
            <li>
                {!! $protectedPage->displayName !!}
            </li>
        @endforeach
    </ul>

    {!! $pages->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }}
        found.</div>
@endsection
