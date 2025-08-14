@extends('pages.layout')

@section('pages-title')
    Special - All Images
@endsection

@section('pages-content')
    {!! breadcrumbs(['Special' => 'special', 'All Images' => 'special/all-images']) !!}

    <h1>Special: All Images</h1>

    <p>This is a list of all images associated with pages. Click an image's thumbnail for more information about it.</p>

    {!! $images->render() !!}

    <div>
        {!! Form::open(['method' => 'GET', 'class' => '']) !!}
        <div class="form-inline justify-content-end">
            <div class="form-group mb-3">
                {!! Form::text('creator_url', Request::get('creator_url'), [
                    'class' => 'form-control',
                    'placeholder' => 'Creator URL',
                ]) !!}
            </div>
            <div class="form-group ml-3 mb-3">
                {!! Form::select('creator_id', $users, Request::get('creator_id'), [
                    'class' => 'form-control selectize',
                    'placeholder' => 'Creator',
                ]) !!}
            </div>
        </div>
        <div class="form-inline justify-content-end">
            <div class="form-group mr-3 mb-3">
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
        </div>
        {!! Form::close() !!}
    </div>

    {!! $images->render() !!}

    <div class="row">
        @foreach ($images as $image)
            {!! $loop->remaining + 1 == $loop->count % 4 ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            <div class="col-md-3 mb-2">
                <a href="{{ url('special/get-image/' . $image->id) }}" class="image-link"><img
                        src="{{ Storage::url($image->thumbnailUrl) }}" class="img-thumbnail mw-100" /></a>
            </div>
            {!! $loop->count % 4 != 0 && $loop->last ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            {!! $loop->iteration % 4 == 0 ? '<div class="w-100"></div>' : '' !!}
        @endforeach
    </div>

    {!! $images->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $images->total() }} result{{ $images->total() == 1 ? '' : 's' }}
        found.</div>
@endsection

@section('scripts')
    @parent
    @include('pages.images._info_popup_js')
@endsection
