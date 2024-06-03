@extends('pages.layout')

@section('pages-title')
    {{ $page->title }} - History
@endsection

@section('meta-img')
    {{ $page->image ? $page->image->thumbnailUrl : asset('images/logo.png') }}
@endsection

@section('meta-desc')
    {{ $page->summary ? $page->summary : config('mundialis.settings.site_desc') }}
@endsection

@section('pages-content')
    {!! breadcrumbs([
        $page->category->subject['name'] => $page->category->subject['key'],
        $page->category->name => $page->category->subject['key'] . '/categories/' . $page->category->id,
        $page->title => $page->url,
        'History' => 'pages/' . $page->id . '/history',
        'Version #' . $version->id => 'pages/' . $page->id . '/history/' . $version->id,
    ]) !!}

    <div class="alert alert-warning">
        You are viewing a version of this page created at {!! format_date($version->created_at) !!} by {!! $version->user->displayName !!}. This
        is{{ $version->id != $page->version->id ? ' not' : '' }} this page's most recent version.
    </div>

    @if (Auth::check() && Auth::user()->canEdit($page) && $version->id != $page->version->id)
        <a href="#" class="btn btn-warning float-right mt-4 ml-2 reset-page-button">Reset
            {{ $page->category->subject['term'] }}</a>
    @endif

    @include('pages._page_header', ['section' => 'Version #' . $version->id])

    @include('pages._page_content', ['data' => $version->data['data']['parsed']])
@endsection

@section('scripts')
    @parent
    @include('pages.images._info_popup_js', ['gallery' => false])

    <script>
        $(document).ready(function() {
            $('.reset-page-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('pages') }}/{{ $page->id }}/history/{{ $version->id }}/reset",
                    'Reset Page');
            });

            // Taken from https://css-tricks.com/swapping-out-text-five-different-ways/
            $(".section-collapse").on("click", function() {
                var el = $(this);
                el.text() == el.data("text-swap") ?
                    el.text(el.data("text-original")) :
                    el.text(el.data("text-swap"));
            });
        });
    </script>
@endsection
