@extends('pages.layout')

@section('pages-title') Time: Timeline @endsection

@section('pages-content')
{!! breadcrumbs(['Time & Events' => 'time', 'Timeline' => 'time/timeline']) !!}

<h1>Timeline</h1>

<p>The following is a timeline of events recorded on this site with a set start date and/or chronology. Events are organized by chronology if set, and then by start date if set. The timeline can be filtered by tag(s) if desired.</p>

<div>
    {!! Form::open(['method' => 'GET', 'class' => '']) !!}
        <div class="ml-auto w-50 justify-content-end form-group mb-3">
            {!! Form::select('tags[]', $tags, Request::get('tags'), ['id' => 'tagList', 'class' => 'form-control', 'multiple', 'placeholder' => 'Tag(s)']) !!}
        </div>
        <div class="text-right ml-auto justify-content-end form-group mb-3">
            {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>

@foreach($chronologies as $chronology)
    <div class="row">
        <div class="col-md-6 border-right border-secondary timeline-section-left">
            <h2>{!! $chronology->displayNameFull !!}</h2>
            {!! $chronology->description !!}
        </div>
        <div class="col-md-6 mobile-hide">
        </div>

        @if($eventHelper->timeOrderedEvents(Auth::check() ? Auth::user() : null, $chronology->id, Request::get('tags') ? Request::get('tags') : null))
            @foreach($eventHelper->timeOrderedEvents(Auth::check() ? Auth::user() : null, $chronology->id, Request::get('tags') ? Request::get('tags') : null) as $key=>$group)
                <div class="col-md-6 border-right border-secondary timeline-section-left {{ $loop->even ? 'mobile-hide' : ''}}">
                @if($loop->even)
                    </div>
                    <div class="col-md-6 border-right border-secondary timeline-section-right">
                @endif
                @include('pages.subjects._time_timeline_group', [
                    'key' => $key,
                    'group' => $group,
                    'i' => 0
                    ])
                @if($loop->odd)
                    </div>
                    <div class="col-md-6 mobile-hide">
                    </div>
                @endif
            @endforeach
        @endif
    </div>
@endforeach

@if($eventHelper->timeOrderedEvents(Auth::check() ? Auth::user() : null, null, Request::get('tags') ? Request::get('tags') : null))
    <div class="row">
        <div class="col-md-6 border-right border-secondary timeline-section-left">
            <h2>Current Events</h2>
        </div>
        <div class="col-md-6 mobile-hide">
        </div>

        @foreach($eventHelper->timeOrderedEvents(Auth::check() ? Auth::user() : null, null, Request::get('tags') ? Request::get('tags') : null) as $key=>$group)
            <div class="col-md-6 col-md-6 border-right border-secondary timeline-section-left {{ $loop->even ? 'mobile-hide' : ''}}">
            @if($loop->even)
                </div>
                <div class="col-md-6">
            @endif
            @include('pages.subjects._time_timeline_group', [
                'key' => $key,
                'group' => $group,
                'i' => 0
                ])
            @if($loop->odd)
                </div>
                <div class="col-md-6 mobile-hide">
                </div>
            @endif
        @endforeach
    </div>
@endif

@endsection

@section('scripts')
@parent

<script>
    $(document).ready(function() {
        $('#tagList').selectize({
            maxItems: 10
        });
    });
</script>
@endsection
