@extends('pages.layout')

@section('pages-title') Special - Most Tagged Pages @endsection

@section('pages-content')
{!! breadcrumbs(['Special' => 'special', 'Most Tagged Pages' => 'special/tagged-pages']) !!}

<h1>Special: Most Tagged Pages</h1>

<p>This is a list of pages with the most tags.</p>

{!! $pages->render() !!}

<ul>
    @foreach($pages as $taggedPage)
        <li>
            {!! $taggedPage->displayName !!} ({{ $taggedPage->tags->count() }} tag{{ $taggedPage->tags->count() != 1 ? 's' : '' }}) <a class="collapse-toggle collapsed" href="#group-{{ $taggedPage->id }}" data-toggle="collapse">Show <i class="fas fa-caret-right"></i></a></h3>
            <div class="collapse" id="group-{{ $taggedPage->id }}">
                <ul>
                    @foreach($taggedPage->tags as $tag)
                        <li>{!! $tag->displayName !!}</li>
                    @endforeach
                </ul>
            </div>
        </li>
    @endforeach
</ul>

{!! $pages->render() !!}

<div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }} found.</div>

@endsection
