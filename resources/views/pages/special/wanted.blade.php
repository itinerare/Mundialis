@extends('pages.layout')

@section('pages-title') Special - Wanted Pages @endsection

@section('pages-content')
{!! breadcrumbs(['Special' => 'special', 'Wanted Pages' => 'special/wanted-pages']) !!}

<h1>Special: Wanted Pages</h1>

<p>This is a list of all wanted pages.</p>

{!! $pages->render() !!}

<ul>
    @foreach($pages as $group)
        <li>
            <span class="text-danger">{{ $group->first()->title }}</span> ({{ $group->count() }} link{{ $group->count() != 1 ? 's' : '' }}) <a class="collapse-toggle collapsed" href="#group-{{ $group->first()->id }}" data-toggle="collapse">Show <i class="fas fa-caret-right"></i></a></h3>
            <div class="collapse" id="group-{{ $group->first()->id }}">
                <ul>
                    @foreach($group as $link)
                        <li>{!! $link->page->displayName !!}</li>
                    @endforeach
                </ul>
            </div>
        </li>
    @endforeach
</ul>

{!! $pages->render() !!}

<div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }} found.</div>

@endsection
