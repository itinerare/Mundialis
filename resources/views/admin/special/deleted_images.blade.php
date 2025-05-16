@extends('admin.layout')

@section('admin-title')
    Deleted Images
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin' => 'admin', 'Special/ Deleted Images' => 'admin/special/deleted-images']) !!}

    <h1>Deleted Images</h1>

    <p>This is a list of all deleted images on the site. So long as one or more of the pages they are attached to are not
        permanently deleted (i.e. their parent category is not deleted), they will remain here in perpetuity. Images may be
        restored so long as one or more of their linked page(s) are not currently deleted. Images deleted alongside pages
        (e.g. if they were only linked to the deleted page) are automatically restored with the page if it is restored.</p>

    <div>
        {!! Form::open(['method' => 'GET', 'class' => 'form-inline justify-content-end']) !!}
        <div class="form-group mr-2 mb-3">
            {!! Form::select('user_id', $users, Request::get('creator_id'), [
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

    {!! $images->render() !!}

    <div class="row ml-md-2 mb-4">
        <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
            <div class="col-md-2 font-weight-bold">Image</div>
            <div class="col-md-3 font-weight-bold">Date</div>
            <div class="col-md-2 font-weight-bold">User</div>
            <div class="col-md font-weight-bold">Information</div>
        </div>
        @foreach ($images as $image)
            <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                <div class="col-md-2">
                    <img src="{{ Storage::url($image->thumbnailUrl) }}" class="img-thumbnail mw-100" style="max-height:100px;" />
                </div>
                <div class="col-md-3 align-self-center">
                    <a href="{{ url('admin/special/deleted-images/' . $image->id) }}" data-toggle="tooltip"
                        title="Click to view image at this version and restore if desired">{!! format_date($image->version->created_at) !!}</a>
                </div>
                <div class="col-md-2 align-self-center">{!! $image->version->user->displayName !!}</div>
                <div class="col-md align-self-center">
                    {!! $image->version->reason ? 'Reason: <i>' . nl2br(htmlentities($image->version->reason)) . '</i><br/>' : '' !!}<a class="collapse-toggle collapsed" href="#version-{{ $image->version->id }}"
                        data-toggle="collapse">Show Raw Data <i class="fas fa-caret-right"></i></a></h3>
                    <div class="collapse" id="version-{{ $image->version->id }}">
                        {{ $image->version->getRawOriginal('data') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {!! $images->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $images->total() }} result{{ $images->total() == 1 ? '' : 's' }}
        found.</div>
@endsection
