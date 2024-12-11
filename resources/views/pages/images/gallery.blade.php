@extends('pages.layout')

@section('pages-title')
    {{ $page->title }} - Gallery
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
        'Gallery' => $page->url . '/gallery',
    ]) !!}

    @include('pages._page_header', ['section' => 'Gallery'])

    <p>The following are all the images associated with this page. Click an image's thumbnail for more information about it.
    </p>

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
                        'sort' => 'Manual Order',
                        'reverse-sort' => 'Manual Order (Reverse)',
                        'newest' => 'Newest First',
                        'oldest' => 'Oldest First',
                    ],
                    Request::get('sort') ?: 'sort',
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
                <a href="{{ url('pages/get-image/' . $page->id . '/' . $image->id) }}" class="image-link"><img
                        src="{{ $image->thumbnailUrl }}" class="img-thumbnail mw-100"
                        style="{{ !$image->pivot->is_valid ? 'filter: grayscale(60%) opacity(50%);' : '' }}" /></a>
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
