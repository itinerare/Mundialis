@extends('pages.layout')

@section('pages-title') Special - Create Wanted Page @endsection

@section('pages-content')
{!! breadcrumbs(['Special' => 'special', 'Wanted Pages' => 'special/wanted-pages', 'Create Wanted Page' => 'special/create-wanted']) !!}

<h1>Create Page: {{ str_replace('_', ' ', $title) }}</h1>

<p>Select a category to place the new page into. This will impact the template used for the page, among other things.</p>

{!! Form::open(['url' => 'special/create-wanted']) !!}
    <div class="form-group">
        {!! Form::label('Category') !!}
        {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control select-category', 'placeholder' => 'Select a Category']) !!}
    </div>

    {!! Form::hidden('title', $title) !!}

    <div class="text-right">
        {!! Form::submit('Go to Create Page', ['class' => 'btn btn-primary']) !!}
    </div>
{!! Form::close() !!}

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {
    $(".select-category").selectize({
        sortField: "text",
    });
});
</script>

@endsection
