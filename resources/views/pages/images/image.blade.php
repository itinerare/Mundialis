@extends('pages.layout')

@section('pages-title')
    {{ $page->title }} - Image #{{ $image->id }}
@endsection

@section('meta-img')
    {{ Storage::url($image->thumbnailUrl) }}
@endsection

@section('meta-desc')
    Image #{{ $image->id }} for the page {{ $page->title }}.
@endsection

@section('pages-content')
    {!! breadcrumbs([
        $page->category->subject['name'] => $page->category->subject['key'],
        $page->category->name => $page->category->subject['key'] . '/categories/' . $page->category->id,
        $page->title => $page->url,
        'Gallery' => 'pages/' . $page->id . '/gallery',
        'Image #' . $image->id => '',
    ]) !!}

    @include('pages._page_header', ['section' => 'Gallery - Image #' . $image->id])

    <div class="text-center">
        <img src="{{ Storage::url($image->imageUrl) }}" class="rounded bg-light mw-100 p-2 mb-2" />
    </div>

    <hr />

    @include('pages.images._info_content')

    <div class="card">
        <div class="card-header">
            <a class="collapse-toggle collapsed" href="#history" data-toggle="collapse">Show History <i
                    class="fas fa-caret-right"></i></a>
        </div>
        <div class="collapse card-body" id="history">
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

            {!! $versions->render() !!}

            <div class="row ml-md-2 mb-4">
                <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
                    <div class="col-md-3 font-weight-bold">Image</div>
                    <div class="col-md-3 font-weight-bold">Version/Date</div>
                    <div class="col-md-2 font-weight-bold">User</div>
                    <div class="col-md-2 font-weight-bold">Type</div>
                    <div class="col-md font-weight-bold">Information</div>
                </div>
                @foreach ($versions as $version)
                    <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                        <div class="col-md-3 text-center">
                            @if ($version->hash)
                                <a href="{{ Storage::url($version->imageUrl) }}"><img
                                        src="{{ Storage::url($version->thumbnailUrl) }}" class="img-thumbnail mw-100"
                                        style="max-height:100px;" /></a>
                            @else
                                <i>No image</i>
                            @endif
                        </div>
                        <div class="col-md-3 align-self-center"><strong>#{{ $version->id }}</strong>
                            {!! format_date($version->created_at) !!}</div>
                        <div class="col-md-2 align-self-center">{!! $version->user->displayName !!}</div>
                        <div class="col-md-2 align-self-center">{{ $version->type }}{!! $version->is_minor ? ' (<abbr data-toggle="tooltip" title="This edit is minor">m</abbr>)' : '' !!}</div>
                        <div class="col-md align-self-center">
                            {!! $version->reason ? 'Reason: <i>' . nl2br(htmlentities($version->reason)) . '</i><br/>' : '' !!}<a class="collapse-toggle collapsed"
                                href="#version-{{ $version->id }}" data-toggle="collapse">Show Raw Data <i
                                    class="fas fa-caret-right"></i></a></h3>
                            <div class="collapse" id="version-{{ $version->id }}">
                                {{ $version->getRawOriginal('data') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {!! $versions->render() !!}

            <div class="text-center mt-4 small text-muted">{{ $versions->total() }}
                result{{ $versions->total() == 1 ? '' : 's' }} found.</div>
        </div>
    </div>
@endsection
