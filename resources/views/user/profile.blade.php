@extends('user.layout')

@section('profile-title')
    {{ $user->name }}'s Profile
@endsection

@section('meta-img')
    {{ Storage::url('/images/avatars/' . $user->avatar) }}
@endsection

@section('profile-content')
    {!! breadcrumbs(['Users' => 'users', $user->name => $user->url]) !!}

    @if ($user->is_banned)
        <div class="alert alert-danger">This user has been banned.</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3 text-center d-block d-md-none mb-4">
            <img src="{{ Storage::url('/images/avatars/' . $user->avatar) }}" class="userAvatar"
                style="width:200px;height:200px;">
        </div>
        <div class="col-md card my-2">
            <img src="{{ Storage::url('/images/avatars/' . $user->avatar) }}" class="userAvatar d-none d-md-block"
                style="position:absolute;width:200px;height:200px;margin-top:-10px; margin-left:-10px;
        margin-right:25px;">
            <div>
                <span class="userAvatar float-left d-none d-md-block"
                    style="width:175px;height:175px;
            margin-right:25px;"></span>
                <div class="borderhr my-2">
                    <h1>
                        {!! $user->displayName !!}
                    </h1>
                    <div class="mb-1">
                        <div class="row">
                            <div class="col-md-2 col-4">
                                <h5>Rank:</h5>
                            </div>
                            <div class="col-md-10 col-8">{!! $user->rank->name !!} {!! add_help($user->rank->description) !!}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-4">
                                <h5>Joined:</h5>
                            </div>
                            <div class="col-md-10 col-8">{!! format_date($user->created_at, false) !!}
                                ({{ $user->created_at->diffForHumans() }})</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            {!! $user->profile_text ? $user->profile_text : '<i>No profile text provided.</i>' !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Recent Page Revisions</h4>
                </div>
                <div class="card-body">
                    <div class="row ml-md-2 mb-4">
                        <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
                            <div class="col-md font-weight-bold">Page</div>
                            <div class="col-md font-weight-bold">Date</div>
                            <div class="col-md font-weight-bold">Type</div>
                        </div>
                        @foreach ($pageVersions as $version)
                            <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                                <div class="col-md">
                                    {!! $version->page->displayName !!}
                                </div>
                                <div class="col-md">
                                    {!! pretty_date($version->created_at) !!}
                                </div>
                                <div class="col-md">{{ $version->type }}{!! $version->is_minor ? ' (<abbr data-toggle="tooltip" title="This edit is minor">m</abbr>)' : '' !!}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-right">
                        <a href="{{ url($user->url . '/page-revisions') }}">View More...</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Recent Image Revisions</h4>
                </div>
                <div class="card-body">
                    <div class="row ml-md-2 mb-4">
                        <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
                            <div class="col-md font-weight-bold">Image</div>
                            <div class="col-md font-weight-bold">Date</div>
                            <div class="col-md font-weight-bold">Type</div>
                        </div>
                        @foreach ($imageVersions as $version)
                            <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                                <div class="col-md text-center align-self-center">
                                    <a href="{{ url('special/get-image/' . $version->image->id) }}"
                                        class="image-link mw-100"><img
                                            src="{{ Storage::url($version->image->thumbnailUrl) }}"
                                            class="img-thumbnail mw-100" /></a>
                                </div>
                                <div class="col-md align-self-center">{!! pretty_date($version->created_at) !!}</div>
                                <div class="col-md align-self-center">{{ $version->type }}{!! $version->is_minor ? ' (<abbr data-toggle="tooltip" title="This edit is minor">m</abbr>)' : '' !!}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-right">
                        <a href="{{ url($user->url . '/image-revisions') }}">View More...</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    @include('pages.images._info_popup_js')
@endsection
