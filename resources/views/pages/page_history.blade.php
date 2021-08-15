@extends('pages.layout')

@section('pages-title') {{ $page->title }} - History @endsection

@section('meta-img')
    {{ $page->image ? $page->image->thumbnailUrl : asset('images/logo.png') }}
@endsection

@section('meta-desc')
    {{ $page->summary ? $page->summary : Config::get('mundialis.settings.site_desc') }}
@endsection

@section('pages-content')
{!! breadcrumbs([$page->category->subject['name'] => $page->category->subject['key'], $page->category->name => $page->category->subject['key'].'/categories/'.$page->category->id, $page->title => $page->url, 'History' => 'pages/'.$page->id.'/history']) !!}

@include('pages._page_header', ['section' => 'History'])

<div>
    {!! Form::open(['method' => 'GET', 'class' => 'form-inline justify-content-end']) !!}
        <div class="form-group mr-2 mb-3">
            {!! Form::select('user_id', $users, Request::get('user_id'), ['class' => 'form-control selectize', 'placeholder' => 'Select a User']) !!}
        </div>
        <div class="form-group mr-2 mb-3">
            {!! Form::select('sort', [
                'newest'         => 'Newest First',
                'oldest'         => 'Oldest First',
            ], Request::get('sort') ? : 'newest', ['class' => 'form-control']) !!}
        </div>
        <div class="form-group mb-3">
            {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>

{!! $versions->render() !!}

<div class="row ml-md-2 mb-4">
    <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
      <div class="col-md-3 font-weight-bold">Version/Date</div>
      <div class="col-md-2 font-weight-bold">User</div>
      <div class="col-md-2 font-weight-bold">Type</div>
      <div class="col-md font-weight-bold">Information</div>
    </div>
    @foreach($versions as $version)
    <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
        <div class="col-md-3">
            <a href="{{ url('pages/'.$page->id.'/history/'.$version->id) }}" data-toggle="tooltip" title="Click to view page at this version{{ Auth::check() && Auth::user()->canEdit($page) ? ' and reset to this version if desired.' : '' }}">
                <strong>#{{ $version->id }}</strong> {!! format_date($version->created_at) !!}
            </a>
        </div>
        <div class="col-md-2">{!! $version->user->displayName !!}</div>
        <div class="col-md-2">{{ $version->type }}{!! $version->is_minor ? ' (<abbr data-toggle="tooltip" title="This edit is minor">m</abbr>)' : '' !!}</div>
        <div class="col-md">
            {!! $version->lengthString !!} - {!! $version->reason ? 'Reason: <i>'.nl2br(htmlentities($version->reason)).'</i><br/>' : '' !!}<a class="collapse-toggle collapsed" href="#version-{{ $version->id }}" data-toggle="collapse">Show Raw Data <i class="fas fa-caret-right"></i></a></h3>
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
