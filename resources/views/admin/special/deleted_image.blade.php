@extends('admin.layout')

@section('admin-title')
    Image #{{ $image->id }}
@endsection

@section('admin-content')
    {!! breadcrumbs([
        'Admin' => 'admin',
        'Special/ Deleted Images' => 'admin/special/deleted-images',
        'Image #' . $image->id => 'admin/special/deleted-images/' . $image->id,
    ]) !!}

    <h1>
        Deleted Image (#{{ $image->id }})
        @if ($image->pages()->count())
            <a href="#" class="btn btn-info float-right restore-image-button">Restore Image</a>
        @endif
    </h1>

    <div class="alert alert-danger">
        This image was deleted at {!! format_date($image->version->created_at) !!} by
        {!! $image->version->user->displayName !!}{{ $image->version->reason ? ' for the reason: ' . $image->version->reason : '' }}. It can
        be
        restored as long as one or more of its linked pages are not currently deleted.
    </div>

    <img src="{{ $image->imageUrl }}" class="rounded bg-light mw-100 p-2 mb-2" />

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
                                <a href="{{ $version->imageUrl }}"><img src="{{ $version->thumbnailUrl }}"
                                        class="img-thumbnail mw-100" style="max-height:100px;" /></a>
                            @else
                                <i>No image</i>
                            @endif
                        </div>
                        <div class="col-md-3 align-self-center"><strong>#{{ $version->id }}</strong>
                            {!! format_date($version->created_at) !!}</div>
                        <div class="col-md-2 align-self-center">{!! $version->user->displayName !!}</div>
                        <div class="col-md-2 align-self-center">{{ $version->type }}{!! $version->is_minor ? ' (<abbr data-toggle="tooltip" title="This version is minor">m</abbr>)' : '' !!}</div>
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

@section('scripts')
    @parent

    <script>
        $(document).ready(function() {
            $('.restore-image-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/special/deleted-images') }}/{{ $image->id }}/restore",
                    'Restore Image');
            });
        });
    </script>
@endsection
