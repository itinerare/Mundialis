@extends('pages.layout')

@section('pages-title') {{ $page->title }} @endsection

@section('meta-img')
    {{ $page->image ? $page->image->thumbnailUrl : asset('images/logo.png') }}
@endsection

@section('meta-desc')
    {{ $page->summary ? $page->summary : Config::get('mundialis.settings.site_desc') }}
@endsection

@section('pages-content')
{!! breadcrumbs([$page->category->subject['name'] => 'pages/'.$page->category->subject['key'], $page->category->name => $page->category->subject['key'].'/categories/'.$page->category->id, $page->title => $page->url]) !!}

@include('pages._page_header')

@include('pages._page_content')

@endsection

@section('scripts')
@parent
    @include('pages.images._info_popup_js', ['gallery' => false])
@endsection
