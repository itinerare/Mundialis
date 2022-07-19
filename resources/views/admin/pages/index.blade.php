@extends('admin.layout')

@section('admin-title')
    Text Pages
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Text Pages' => 'admin/pages']) !!}

    <h1>Text Pages</h1>

    <p>This is a list of text pages used at various places around the site for basic information. Site pages cannot be
        created, only edited.</p>

    @if (!count($pages))
        <p>No pages found.</p>
    @else
        {!! $pages->render() !!}
        <div class="row ml-md-2">
            <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
                <div class="col-12 col-md-5 font-weight-bold">Title</div>
                <div class="col-3 col-md-3 font-weight-bold">Key</div>
                <div class="col-6 col-md-3 font-weight-bold">Last Edited</div>
            </div>
            @foreach ($pages as $page)
                <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                    <div class="col-12 col-md-5">{{ $page->title }}</div>
                    <div class="col-3 col-md-3">{{ $page->key }}</div>
                    <div class="col-6 col-md-3">{!! pretty_date($page->updated_at) !!}</div>
                    <div class="col-3 col-md-1 text-right"><a href="{{ url('admin/pages/edit/' . $page->id) }}"
                            class="btn btn-primary py-0 px-2">Edit</a></div>
                </div>
            @endforeach
        </div>
        {!! $pages->render() !!}

        <div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }}
            found.</div>
    @endif

@endsection
