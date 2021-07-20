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

<div class="row">
    @if($page->image || isset($page->category->template['infobox']) || (isset($page->category->subject['segments']['infobox']) && View::exists('pages.content_builder._'.$page->category->subject['key'].'_infobox')))
        <div class="mobile-show col-lg-4 mb-2">
            @include('pages.content_builder._infobox_builder')
        </div>
    @endif
    <div class="col-lg-12 col-md">
        <!-- INFOBOX -->
        @if($page->image || isset($page->category->template['infobox']) || (isset($page->category->subject['segments']['infobox']) && View::exists('pages.content_builder._'.$page->category->subject['key'].'_infobox')))
            <div class="float-right mobile-hide" style="width:25vw;">
                @include('pages.content_builder._infobox_builder')
            </div>
        @endif

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

@section('scripts')
@parent
    @include('pages.images._info_popup_js', ['gallery' => false])
@endsection
