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

<p>This is a list of all pages that link to this page. Note that this list only counts links made within page content.</p>

{!! $links->render() !!}

<ul>
    @foreach($links as $link)
        <li>{!! $link->parent->displayName !!}</li>
    @endforeach
</ul>

{!! $links->render() !!}

<div class="text-center mt-4 small text-muted">{{ $links->total() }} result{{ $links->total() == 1 ? '' : 's' }} found.</div>

@endsection
