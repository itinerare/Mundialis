@extends('pages.layout')

@section('pages-title') {{ $page->id ? 'Edit' : 'Create' }} Page @endsection

@section('pages-content')
{!! breadcrumbs(['Pages' => 'pages', $category->subject['name'] => 'pages/'.$category->subject['key'], ($page->id ? 'Edit' : 'Create').' Page' => $page->id ? 'pages/edit/'.$page->id : 'pages/create/'.$category->id]) !!}

<h1>{{ $page->id ? 'Edit' : 'Create' }} Page
    @if($page->id)
        <a href="#" class="btn btn-danger float-right delete-page-button">Delete Page</a>
    @endif
</h1>

{!! Form::open(['url' => $page->id ? 'pages/edit/'.$page->id : 'pages/create']) !!}

<h2>Basic Information</h2>

<div class="form-group">
    {!! Form::label('Title') !!}
    {!! Form::text('title', $page->title, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Summary (Optional)') !!} {!! add_help('A short summary of the page\'s contents. This will be displayed on the page index.') !!}
    {!! Form::text('summary', $page->summary, ['class' => 'form-control']) !!}
</div>

@if(!$page->id)
    {!! Form::hidden('category_id', $category->id, ['class' => 'form-control']) !!}
@endif

@if(isset($category->template)) @include('pages.form_builder._form_builder', ['category' => $page->id ? $page->category : $category, 'page' => $page]) @endif

<div class="form-group">
    {!! Form::checkbox('is_visible', 1, $page->id ? $page->is_visible : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
    {!! Form::label('is_visible', 'Is Visible', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If this is turned off, visitors and regular users will not be able to see this page. Users with editor permissions will still be able to see it.') !!}
</div>

<div class="text-right">
    {!! Form::submit($page->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {
    $('.delete-page-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('pages/delete') }}/{{ $page->id }}", 'Delete Page');
    });
});

</script>
@endsection
