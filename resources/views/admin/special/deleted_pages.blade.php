@extends('admin.layout')

@section('admin-title')
    Deleted Pages
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin' => 'admin', 'Special/ Deleted Pages' => 'admin/special/deleted-pages']) !!}

    <h1>Deleted Pages</h1>

    <p>This is a list of all deleted pages on the site. So long as the categorie(s) they are in are not deleted, they will
        remain here in perpetuity, and may be restored at any time.</p>

    <div>
        {!! Form::open(['method' => 'GET', 'class' => 'form-inline justify-content-end']) !!}
        <div class="form-group mr-2 mb-3">
            {!! Form::select('user_id', $users, Request::get('user_id'), [
                'class' => 'form-control selectize',
                'placeholder' => 'Select a User',
            ]) !!}
        </div>
        <div class="form-group mr-2 mb-3">
            {!! Form::select(
                'sort',
                [
                    'newest' => 'Newest First',
                    'oldest' => 'Oldest First',
                ],
                Request::get('sort') ?: 'newest',
                ['class' => 'form-control'],
            ) !!}
        </div>
        <div class="form-group mb-3">
            {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>

    {!! $pages->render() !!}

    <div class="row ml-md-2 mb-4">
        <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
            <div class="col-md-2 font-weight-bold">Page</div>
            <div class="col-md-3 font-weight-bold">Date</div>
            <div class="col-md-2 font-weight-bold">User</div>
            <div class="col-md font-weight-bold">Information</div>
        </div>
        @foreach ($pages as $page)
            <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                <div class="col-md-2">{{ $page->title }} ({{ $page->category->subject['term'] }})</div>
                <div class="col-md-3">
                    <a href="{{ url('admin/special/deleted-pages/' . $page->id) }}" data-toggle="tooltip"
                        title="Click to view page at this version and restore if desired">{!! format_date($page->version->created_at) !!}</a>
                </div>
                <div class="col-md-2">{!! $page->version->user->displayName !!}</div>
                <div class="col-md">
                    {!! $page->version->lengthString !!} - {!! $page->version->reason ? 'Reason: <i>' . nl2br(htmlentities($page->version->reason)) . '</i><br/>' : '' !!}<a class="collapse-toggle collapsed"
                        href="#version-{{ $page->version->id }}" data-toggle="collapse">Show Raw Data <i
                            class="fas fa-caret-right"></i></a></h3>
                    <div class="collapse" id="version-{{ $page->version->id }}">
                        {{ $page->version->getRawOriginal('data') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {!! $pages->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }}
        found.</div>
@endsection
