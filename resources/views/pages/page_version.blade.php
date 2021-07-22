@extends('pages.layout')

@section('pages-title') {{ $page->title }} - History @endsection

@section('meta-img')
    {{ $page->image ? $page->image->thumbnailUrl : asset('images/logo.png') }}
@endsection

@section('meta-desc')
    {{ $page->summary ? $page->summary : Config::get('mundialis.settings.site_desc') }}
@endsection

@section('pages-content')
{!! breadcrumbs([$page->category->subject['name'] => 'pages/'.$page->category->subject['key'], $page->category->name => $page->category->subject['key'].'/categories/'.$page->category->id, $page->title => $page->url, 'History' => 'pages/'.$page->id.'/history', 'Version #'.$version->id => 'pages/'.$page->id.'/history/'.$version->id]) !!}

<div class="alert alert-warning">
    You are viewing a version of this page created at {!! format_date($version->created_at) !!}. This is{{ $version->id != $page->version->id ? ' not' : '' }} this page's most recent version.
</div>
<!--@if((Auth::check() && Auth::user()->canWrite) && $page->version->id != $version->id)@endif-->
@if((Auth::check() && Auth::user()->canWrite))
    <a href="#" class="btn btn-warning float-right reset-page-button">Reset {{ $page->category->subject['term'] }}</a>
@endif
@include('pages._page_header', ['section' => 'Version #'.$version->id])

@include('pages._page_content')

@endsection

@section('scripts')
@parent
    @include('pages.images._info_popup_js', ['gallery' => false])

    <script>
        $( document ).ready(function() {
            $('.reset-page-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('pages') }}/{{ $page->id }}/history/{{ $version->id }}/reset", 'Reset Page');
            });
        });

    </script>
@endsection
