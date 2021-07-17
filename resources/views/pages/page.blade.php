@extends('pages.layout')

@section('pages-title') {{ $page->title }} @endsection

@section('pages-content')
{!! breadcrumbs(['Pages' => 'pages', $page->category->subject['name'] => 'pages/'.$page->category->subject['key'], $page->category->name => 'pages/categories/'.$page->category->id, $page->title => $page->url]) !!}

{!! $page->category->subject['term'].' ・ '.$page->category->displayName !!}{!! $page->category->subject['key'] == 'time' && isset($page->data['date']['start']) ? ' ・ '.$dateHelper->formatTimeDate($page->data['date']['start']).(isset($page->data['date']['start']) && isset($page->data['date']['end']) ? '-' : '' ).(isset($page->data['date']['end']) ? $dateHelper->formatTimeDate($page->data['date']['end']) : '') : '' !!}{!! $page->parent ? ' ・ '.$page->parent->displayName : '' !!}
<h1>{{ $page->title }}
    @if(Auth::check() && Auth::user()->canWrite)
        <a href="{{ url('pages/edit/'.$page->id) }}" class="btn btn-secondary float-right">Edit {{ $page->category->subject['term'] }}</a>
    @endif
</h1>


<div class="row">
    <div class="mobile-show col-lg-4 mb-2">
        @include('pages.content_builder._infobox_builder')
    </div>
    <div class="col-lg-12 col-md">
        <!-- INFOBOX -->
        <div class="float-right mobile-hide" style="width:25vw;">
            @include('pages.content_builder._infobox_builder')
        </div>

        <!-- INTRO -->
        {!! isset($page->data['description']) ? $page->data['description'] : '' !!}

        @if(isset($page->category->subject['segments']['general properties']) && View::exists('pages.content_builder._'.$page->category->subject['key'].'_general'))
            @include('pages.content_builder._'.$page->category->subject['key'].'_general')
        @endif

        <!-- MAIN CONTENT -->
        @if(isset($page->category->subject['segments']['sections']) && View::exists('pages.content_builder._'.$category->subject['key'].'_sections'))
            @include('pages.content_builder._'.$page->category->subject['key'].'_sections')
        @endif

        @if(isset($page->category->template['sections']))
            @foreach($page->category->template['sections'] as $sectionKey=>$section)
                <h2>{{ $section['name'] }}</h2>
                @if(isset($page->category->template['fields'][$sectionKey]))
                    @foreach($page->category->template['fields'][$sectionKey] as $fieldKey=>$field)
                        @include('pages.content_builder._body_builder', ['key' => $fieldKey, 'field' => $field])
                    @endforeach
                @endif
            @endforeach
        @endif
    </div>
</div>

@endsection
