@extends('pages.layout')

@section('pages-title') {{ $page->title }} - Image #{{ $image->id }}  @endsection

@section('meta-img')
    {{ $image->thumbnailUrl }}
@endsection

@section('meta-desc')
    Image #{{ $image->id }} for the page {{ $page->title }}.
@endsection

@section('pages-content')
{!! breadcrumbs([$page->category->subject['name'] => $page->category->subject['key'], $page->category->name =>  $page->category->subject['key'].'/categories/'.$page->category->id, $page->title => $page->url, 'Gallery' => 'pages/'.$page->id.'/gallery', 'Image #'.$image->id => '']) !!}

@include('pages._page_header', ['section' => 'Gallery - Image #'.$image->id])

<img src="{{ $image->imageUrl }}" class="rounded bg-light mw-100 p-2 mb-2"/>

<hr/>

@include('pages.images._info_content')

@endsection
