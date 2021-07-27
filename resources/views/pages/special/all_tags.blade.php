@extends('pages.layout')

@section('pages-title') Special - All Tags @endsection

@section('pages-content')
{!! breadcrumbs(['Special' => 'special', 'All Tags' => 'special/all-tags']) !!}

<h1>Special: All Tags</h1>

<p>This is a list of all tags that have been applied to pages.</p>

{!! $tags->render() !!}

<ul>
    @foreach($tags as $group)
        <li>
            {!! $group->first()->displayNameBase !!}
        </li>
    @endforeach
</ul>

{!! $tags->render() !!}

<div class="text-center mt-4 small text-muted">{{ $tags->total() }} result{{ $tags->total() == 1 ? '' : 's' }} found.</div>

@endsection
