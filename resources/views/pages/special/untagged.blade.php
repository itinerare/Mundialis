@extends('pages.layout')

@section('pages-title') Special - Untagged Pages @endsection

@section('pages-content')
{!! breadcrumbs(['Special' => 'special', 'Untagged Pages' => 'special/untagged-pages']) !!}

<h1>Special: Untagged Pages</h1>

<p>This is a list of pages with no tags.</p>

{!! $pages->render() !!}

<ul>
    @foreach($pages as $taggedPage)
        <li>
            {!! $taggedPage->displayName !!}
        </li>
    @endforeach
</ul>

{!! $pages->render() !!}

<div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }} found.</div>

@endsection
