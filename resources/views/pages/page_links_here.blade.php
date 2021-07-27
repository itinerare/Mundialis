@extends('pages.layout')

@section('pages-title') {{ $page->title }} - What Links Here @endsection

@section('meta-img')
    {{ $page->image ? $page->image->thumbnailUrl : asset('images/logo.png') }}
@endsection

@section('meta-desc')
    {{ $page->summary ? $page->summary : Config::get('mundialis.settings.site_desc') }}
@endsection

@section('pages-content')
{!! breadcrumbs([$page->category->subject['name'] => 'pages/'.$page->category->subject['key'], $page->category->name => $page->category->subject['key'].'/categories/'.$page->category->id, $page->title => $page->url, 'What Links Here' => 'pages/'.$page->id.'/links-here']) !!}

@include('pages._page_header', ['section' => 'What Links Here'])

<p>This is a list of all pages that link to this page.</p>

{!! $links->render() !!}

<ul>
    @foreach($links as $link)
        @if($link->page->is_visible || (Auth::check() && Auth::user()->canWrite))
            <li>{!! $link->page->displayName !!}</li>
        @endif
    @endforeach
</ul>

{!! $links->render() !!}

@endsection
