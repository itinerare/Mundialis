@extends('account.layout')

@section('account-title') Settings @endsection

@section('account-content')
{!! breadcrumbs(['My Account' => Auth::user()->url, 'Watched Pages' => 'account/watched-pages']) !!}

<h1>Watched Pages</h1>

<p>This is a list of your watched pages. Your watched pages are only visible to you, and you will be notified on any change made to them (with the exception of your own changes). You can watch pages by clicking the "Watch Page" link in the sidebar of a page.</p>

<div>
    {!! Form::open(['method' => 'GET', 'class' => '']) !!}
        <div class="form-inline justify-content-end">
            <div class="form-group mb-3">
                {!! Form::text('title', Request::get('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
            </div>
            @if(!isset($category))
                <div class="form-group ml-3 mb-3">
                    {!! Form::select('category_id', $categoryOptions, Request::get('category_id'), ['class' => 'form-control', 'placeholder' => 'Select a Category']) !!}
                </div>
            @endif
        </div>
        <div class="ml-auto w-50 justify-content-end form-group mb-3">
            {!! Form::select('tags[]', $tags, Request::get('tags'), ['id' => 'tagList', 'class' => 'form-control', 'multiple', 'placeholder' => 'Tag(s)']) !!}
        </div>
        <div class="form-inline justify-content-end">
            <div class="form-group mr-3 mb-3">
                {!! Form::select('sort', [
                    'newest'         => 'Newest First',
                    'oldest'         => 'Oldest First',
                    'alpha'          => 'Sort Alphabetically (A-Z)',
                    'alpha-reverse'  => 'Sort Alphabetically (Z-A)',
                ], Request::get('sort') ? : 'alpha', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group mb-3">
                {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}
</div>

{!! $pages->render() !!}

<div class="row ml-md-2 mb-4">
    <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
      <div class="col-md-2 font-weight-bold">Page</div>
      <div class="col-md-3 font-weight-bold">Subject/Category</div>
      <div class="col-md font-weight-bold">Last Edited</div>
    </div>
    @foreach($pages as $page)
    <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
        <div class="col-md-2">
            {!! $page ? $page->displayName : 'Deleted Page' !!}
        </div>
        <div class="col-md-3"><a href="{{ url($page->category->subject['key']) }}">{{ $page->category->subject['name'] }}</a>/{!! $page->category->displayName !!}</div>
        <div class="col-md">{!! pretty_date($page->version->created_at) !!} by {!! $page->version->user->displayName !!}</div>
    </div>
    @endforeach
</div>

{!! $pages->render() !!}

<div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }} found.</div>

@endsection

@section('scripts')
@parent

<script>
    $(document).ready(function() {
        $('#tagList').selectize({
            maxItems: 10
        });
    });
</script>
@endsection
