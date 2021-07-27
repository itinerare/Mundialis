@extends('pages.layout')

@section('pages-title') Special Pages @endsection

@section('pages-content')
{!! breadcrumbs(['Special' => 'special']) !!}

<h1>Special Pages</h1>

<p>
    This is a list of all special pages. Special pages are automatically populated and serve various purposes, such as providing maintenance reports and lists of pages.
    @if(Auth::check() && Auth::user()->isAdmin)
        Special pages listed in <strong>bold</strong> are admin-only. While they are also listed in the <a href="{{ url('admin') }}">admin panel</a>, they are included here in the interests of convenience and completeness.
    @endif
</p>

<h2>Maintenance Reports</h2>

<div class="row">
    <div class="col-md">
        <ul>
            <li><a href="{{ url('special/untagged-pages') }}">Untagged Pages</a></li>
            <li><a href="{{ url('special/tagged-pages') }}">Pages with the Most Tags</a></li>
            <li><a href="{{ url('special/least-revised-pages') }}">Pages with the Fewest Revisions</a></li>
            <li><a href="{{ url('special/most-revised-pages') }}">Pages with the Most Revisions</a></li>
            <li><a href="{{ url('special/linked-pages') }}">Most Linked-To Pages</a></li>
            <li>Protected Pages</li>
        </ul>
    </div>
    <div class="col-md">
        <ul>
            <li><a href="{{ url('special/wanted-pages') }}">Wanted Pages</a></li>
            @foreach(Config::get('mundialis.utility_tags') as $key=>$tag)
                <li><a href="{{ url('special/'.$key.'-pages') }}" class="{{ set_active('special/'.$key.'-pages') }}">{{ $tag['name'] }}</a></li>
            @endforeach
            @if(Auth::check() && Auth::user()->isAdmin)
                <li><strong>Unwatched Pages</strong></li>
            @endif
        </ul>
    </div>
</div>

<h2>Lists of Pages, Etc.</h2>

<ul>
    <li><a href="{{ url('special/all-pages') }}">All Pages</a></li>
    <li><a href="{{ url('special/all-tags') }}">All Tags</a></li>
    <li><a href="{{ url('special/all-images') }}">All Images</a></li>
    @if(Auth::check() && Auth::user()->isAdmin)
        <li><strong><a href="{{ url('admin/special/deleted-pages') }}">Deleted Pages</a></strong></li>
        <li><strong><a href="{{ url('admin/special/deleted-images') }}">Deleted Images</a></strong></li>
    @endif
</ul>

<h2>Users</h2>

<ul>
    <li>User List</li>
    <li>User Contributions</li>
</ul>

<h2>Other</h2>

<ul>
    <li><a href="{{ url('special/random-page') }}">Random Page</a></li>
</ul>

@endsection
