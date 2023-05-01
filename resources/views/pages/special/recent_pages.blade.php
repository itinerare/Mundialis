@extends('pages.layout')

@section('pages-title')
    Special - Recent Page Changes
@endsection

@section('pages-content')
    {!! breadcrumbs(['Special' => 'special', 'Revised Pages' => 'special/recent-pages']) !!}

    <h1>Special: Recent Page Changes</h1>

    <p>This is a list of recent revisions to pages.</p>

    <p class="text-right">
        View:
        @foreach ([
            1 => '1 Day',
            3 => '3 Days',
            7 => '7 Days',
            30 => '30 Days',
            50 => '50 Days',
            'all' => 'All Time',
        ] as $mode => $label)
            @if (Request::url() . (Request::get('mode') ? '?mode=' . Request::get('mode') : '') ==
                    url('special/recent-pages?mode=' . $mode))
                {{ $label }}
            @else
                <a href="{{ url('special/recent-pages?mode=' . $mode) }}">{{ $label }}</a>
            @endif
            {{ !$loop->last ? '|' : '' }}
        @endforeach
    </p>

    {!! $pages->render() !!}

    <div class="row ml-md-2 mb-4">
        <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
            <div class="col-md-2 font-weight-bold">Page</div>
            <div class="col-md-3 font-weight-bold">Version/Date</div>
            <div class="col-md-1 font-weight-bold">User</div>
            <div class="col-md-2 font-weight-bold">Type</div>
            <div class="col-md font-weight-bold">Information</div>
        </div>
        @foreach ($pages as $version)
            <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                <div class="col-md-2">
                    {!! $version->page ? $version->page->displayName : 'Deleted Page' !!}
                </div>
                <div class="col-md-3">
                    <a href="{{ url('pages/' . $version->page->id . '/history/' . $version->id) }}" data-toggle="tooltip"
                        title="Click to view page at this version{{ Auth::check() && Auth::user()->canEdit($version->page) ? ' and reset to this version if desired.' : '' }}">
                        <strong>#{{ $version->id }}</strong> {!! format_date($version->created_at) !!}
                    </a>
                </div>
                <div class="col-md-1">{!! $version->user->displayName !!}</div>
                <div class="col-md-2">{{ $version->type }}{!! $version->is_minor ? ' (<abbr data-toggle="tooltip" title="This edit is minor">m</abbr>)' : '' !!}</div>
                <div class="col-md">
                    {!! $version->lengthString !!} - {!! $version->reason ? 'Reason: <i>' . nl2br(htmlentities($version->reason)) . '</i><br/>' : '' !!}<a class="collapse-toggle collapsed"
                        href="#version-{{ $version->id }}" data-toggle="collapse">Show Raw Data <i
                            class="fas fa-caret-right"></i></a></h3>
                    <div class="collapse" id="version-{{ $version->id }}">
                        {{ $version->getRawOriginal('data') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {!! $pages->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }}
        found.</div>
@endsection
