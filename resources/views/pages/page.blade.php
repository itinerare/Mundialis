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

@if($page->utilityTags)
    @foreach($page->utilityTags()->where('tag', '!=', 'stub')->get() as $tag)
        <div class="alert alert-secondary border-danger" style="border-width:0 0 0 10px;">
            {{ Config::get('mundialis.utility_tags.'.$tag->tag.'.message') }}
            @if(Auth::check() && Auth::user()->canWrite)
                Consider <a href="{{ url('pages/'.$page->id.'/edit') }}">{{ Config::get('mundialis.utility_tags.'.$tag->tag.'.verb') }} it</a>.
            @endif
        </div>
    @endforeach
@endif

@include('pages._page_content', ['data' => $page->data])

@if($page->utilityTags()->where('tag', 'stub')->first())
    <p><i>
        {{ Config::get('mundialis.utility_tags.stub.message') }}
        @if(Auth::check() && Auth::user()->canWrite)
            Consider <a href="{{ url('pages/'.$page->id.'/edit') }}">{{ Config::get('mundialis.utility_tags.stub.verb') }} it</a>.
        @endif
    </i></p>
@endif

@if($page->tags->count())
    @foreach($page->tags as $tag)
        @if($tag->hasNavbox)
            @include('pages.tags._navbox', ['tag' => $tag, 'navbox' => $tag->navboxInfo])
        @endif
    @endforeach

    <div class="alert alert-secondary">
        <strong>Tags:</strong>
        @foreach($page->tags as $tag)
            {!! $tag->displayName !!}{{ !$loop->last ? ',' : '' }}
        @endforeach
    </div>
@endif

@endsection

@section('scripts')
@parent
    @include('pages.images._info_popup_js', ['gallery' => false])
@endsection
