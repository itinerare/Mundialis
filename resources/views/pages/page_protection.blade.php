@extends('pages.layout')

@section('pages-title')
    {{ $page->title }} - Page Protection
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
        $page->protection && $page->protection->is_protected ? 'Edit Page Protection' : 'Protect Page' =>
            'pages/' . $page->id . '/protect',
    ]) !!}

    @include('pages._page_header', [
        'section' =>
            $page->protection && $page->protection->is_protected ? 'Edit Page Protection' : 'Protect Page',
    ])

    <p>Here you can view and edit this page's current protection information, as well view protection logs for the page.</p>

    <div class="card mb-4">
        <div class="card-body">
            {!! Form::open(['url' => 'pages/' . $page->id . '/protect']) !!}

            <div class="form-group">
                {!! Form::label('Reason (Optional)') !!} {!! add_help('A short summary why the page\'s protection is being updated.') !!}
                {!! Form::text('reason', $page->protection ? $page->protection->reason : null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::checkbox('is_protected', 1, $page->protection ? $page->protection->is_protected : 0, [
                    'class' => 'form-check-input',
                    'data-toggle' => 'toggle',
                ]) !!}
                {!! Form::label('is_protected', 'Protect Page', ['class' => 'form-check-label ml-3']) !!} {!! add_help('Whether or not the page is protected.') !!}
            </div>

            <div class="text-right">
                {!! Form::submit(
                    $page->protection && $page->protection->is_protected ? 'Edit Page Protection' : 'Protect Page',
                    ['class' => 'btn btn-primary'],
                ) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <a class="collapse-toggle collapsed" href="#history" data-toggle="collapse">Show History <i
                    class="fas fa-caret-right"></i></a>
        </div>
        <div class="collapse show card-body" id="history">
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

            {!! $protections->render() !!}

            <div class="row ml-md-2 mb-4">
                <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
                    <div class="col-md-3 font-weight-bold">Date</div>
                    <div class="col-md-2 font-weight-bold">User</div>
                    <div class="col-md-2 font-weight-bold">Protection Status</div>
                    <div class="col-md font-weight-bold">Reason</div>
                </div>
                @foreach ($protections as $protection)
                    <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                        <div class="col-md-3 align-self-center">{!! format_date($protection->created_at) !!}</div>
                        <div class="col-md-2 align-self-center">{!! $protection->user->displayName !!}</div>
                        <div class="col-md-2 align-self-center">{!! $protection->is_protected
                            ? '<i class="fas fa-lock text-success" data-toggle="tooltip" title="Protected"></i>'
                            : '<i class="fas fa-lock-open text-danger" data-toggle="tooltip" title="Unprotected"></i>' !!}</div>
                        <div class="col-md align-self-center">
                            {!! $protection->reason ? nl2br(htmlentities($protection->reason)) : '<i>No reason given.</i>' !!}
                        </div>
                    </div>
                @endforeach
            </div>

            {!! $protections->render() !!}

            <div class="text-center mt-4 small text-muted">{{ $protections->total() }}
                result{{ $protections->total() == 1 ? '' : 's' }} found.</div>
        </div>
    </div>
@endsection
