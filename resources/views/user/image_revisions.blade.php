@extends('user.layout')

@section('profile-title') {{ $user->name }}'s Profile @endsection

@section('meta-img') {{ asset('/images/avatars/'.$user->avatar) }} @endsection

@section('profile-content')
{!! breadcrumbs(['Users' => 'users', $user->name => $user->url, 'Image Revisions' => $user->url.'/image-revisions']) !!}

@if($user->is_banned)
    <div class="alert alert-danger">This user has been banned.</div>
@endif

<h1>{!! $user->displayName !!}'s Image Revisions</h1>

<p>The following are all of {{ $user->name }}'s image revisions, ordered from most to least recent.</p>

{!! $versions->render() !!}

<div class="row ml-md-2 mb-4">
    <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
    <div class="col-md-1 font-weight-bold">Image</div>
    <div class="col-md-2 font-weight-bold">Image Changes</div>
    <div class="col-md-3 font-weight-bold">Version/Date</div>
    <div class="col-md-2 font-weight-bold">Type</div>
    <div class="col-md font-weight-bold">Information</div>
    </div>
    @foreach($versions as $version)
    <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
        <div class="col-md-1 text-center align-self-center">
            @if($version->image)
                <a href="{{ url('special/get-image/'.$version->image->id) }}" class="image-link mw-100"><img src="{{ $version->image->thumbnailUrl }}" class="img-thumbnail mw-100"/></a>
            @else
                Deleted image
            @endif
        </div>
        <div class="col-md-2 text-center align-self-center">
            @if($version->hash)
                <a href="{{ $version->imageUrl }}"><img src="{{ $version->thumbnailUrl }}" class="img-thumbnail mw-100" style="max-height:100px;" /></a>
            @else
                <i>No image</i>
            @endif
        </div>
        <div class="col-md-3 align-self-center"><strong>#{{ $version->id }}</strong> {!! format_date($version->created_at) !!}</div>
        <div class="col-md-2 align-self-center">{{ $version->type }}{!! $version->is_minor ? ' (<abbr data-toggle="tooltip" title="This edit is minor">m</abbr>)' : '' !!}</div>
        <div class="col-md align-self-center">
            {!! $version->reason ? 'Reason: <i>'.nl2br(htmlentities($version->reason)).'</i><br/>' : '' !!}<a class="collapse-toggle collapsed" href="#version-{{ $version->id }}" data-toggle="collapse">Show Raw Data <i class="fas fa-caret-right"></i></a></h3>
            <div class="collapse" id="version-{{ $version->id }}">
                {{ $version->getRawOriginal('data') }}
            </div>
        </div>
    </div>
    @endforeach
</div>

{!! $versions->render() !!}

<div class="text-center mt-4 small text-muted">{{ $versions->total() }} result{{ $versions->total() == 1 ? '' : 's' }} found.</div>

@endsection
