@extends('admin.layout')

@section('admin-title') {{ $page->title }} @endsection

@section('admin-content')
{!! breadcrumbs(['Admin' => 'admin', 'Special/ Deleted Pages' => 'admin/special/deleted-pages', $page->title => 'admin/special/deleted-pages/'.$page->id]) !!}

<div class="alert alert-danger">
    This page was deleted at {!! format_date($page->version->created_at) !!} by {!! $page->version->user->displayName !!}. As long as its parent category is not deleted, it can be restored at any time.
</div>

<a href="#" class="btn btn-info float-right restore-page-button">Restore {{ $page->category->subject['term'] }}</a>
@include('pages._page_header', ['section' => '(Deleted)'])

@include('pages._page_content')

@endsection

@section('scripts')
@parent
    @include('pages.images._info_popup_js', ['gallery' => false])

    <script>
    $( document ).ready(function() {
        $('.restore-page-button').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('admin/special/deleted-pages') }}/{{ $page->id }}/restore", 'Restore Page');
        });
    });

    </script>
@endsection
