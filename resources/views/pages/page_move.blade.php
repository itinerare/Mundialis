@extends('pages.layout')

@section('pages-title') {{ $page->title }} - Move Page @endsection

@section('meta-img')
    {{ $page->image ? $page->image->thumbnailUrl : asset('images/logo.png') }}
@endsection

@section('meta-desc')
    {{ $page->summary ? $page->summary : Config::get('mundialis.settings.site_desc') }}
@endsection

@section('pages-content')
{!! breadcrumbs([$page->category->subject['name'] => $page->category->subject['key'], $page->category->name => $page->category->subject['key'].'/categories/'.$page->category->id, $page->title => $page->url, 'Move Page' => 'pages/'.$page->id.'/move']) !!}

@include('pages._page_header', ['section' => 'Move Page'])

<p>Select a category to move this page to. Note that if the selected category does not have the same template information, or fields, that information will be "removed" from the page. However, it will still be preserved in the page's revision history. Nonetheless, for this reason, it is recommended to only move pages to categories within the same subject, or to categories that have the same or similar fields.</p>

{!! Form::open(['url' => 'pages/'.$page->id.'/move']) !!}
    <div class="form-group">
        {!! Form::label('Category') !!}
        {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control select-category', 'placeholder' => 'Select a Category']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Reason (Optional)') !!} {!! add_help('A short summary of why you are moving the page. Optional, but recommended for recordkeeping and communication purposes.') !!}
        {!! Form::text('reason', null, ['class' => 'form-control']) !!}
    </div>

    <div class="text-right">
        {!! Form::submit('Move Page', ['class' => 'btn btn-primary']) !!}
    </div>
{!! Form::close() !!}

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {
    $(".select-category").selectize({
        sortField: "text",
        lockOptgroupOrder: true
    });
});
</script>

@endsection
