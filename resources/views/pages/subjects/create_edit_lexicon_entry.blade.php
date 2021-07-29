@extends('pages.layout')

@section('pages-title') {{ $entry->id ? 'Edit' : 'Create' }} Entry @endsection

@section('pages-content')
{!! breadcrumbs(['Language' => 'language', $entry->id ? 'Edit' : 'Create'.' Lexicon Entry' => $entry->id ? 'language/lexicon/edit/'.$entry->id : 'language/lexicon/create' ]) !!}

<h1>{{ $entry->id ? 'Edit' : 'Create' }} Lexicon Entry
    @if($entry->id)
        <a href="#" class="btn btn-danger float-right delete-entry-button">Delete Entry</a>
    @endif
</h1>

{!! Form::open(['url' => $entry->id ? 'language/lexicon/edit/'.$entry->id : 'language/lexicon/create']) !!}

<div class="row">
    <div class="col-md">
        <div class="form-group">
            {!! Form::label('Word') !!}
            {!! Form::text('word', $entry->word, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md">
        <div class="form-group">
            {!! Form::label('Category (Optional)') !!} {!! add_help('While selecting a category is optional, doing so allows access to advanced settings, such as conjugation/declension of words, as set for the category.') !!}
            {!! Form::select('category_id', $categoryOptions, $entry->id ? $entry->category_id : Request::get('category_id'), ['class' => 'form-control', 'placeholder' => 'Select a Category']) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Part of Speech') !!}
            {!! Form::select('class', $classOptions, $entry->class, ['class' => 'form-control', 'placeholder' => 'Select a Part of Speech']) !!}
        </div>
    </div>
    <div class="col-md">
        <div class="form-group">
            {!! Form::label('Meaning') !!} {!! add_help('A concise meaning or translation of the word.') !!}
            {!! Form::text('meaning', $entry->meaning, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md">
        <div class="form-group">
            {!! Form::label('Pronunciation (Optional)') !!}
            {!! Form::text('pronunciation', $entry->pronunciation, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('Definition (Optional)') !!} {!! add_help('If desired, you can provide a longer-form definition for the word here.') !!}
    {!! Form::textarea('definition', $entry->definition, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="form-group">
    {!! Form::checkbox('is_visible', 1, $entry->id ? $entry->is_visible : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
    {!! Form::label('is_visible', 'Is Visible', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If this is turned off, visitors and regular users will not be able to see this entry. Users with editor permissions will still be able to see it.') !!}
</div>

<div class="text-right">
    {!! Form::submit($entry->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

@endsection

@section('scripts')
<script>
    $( document ).ready(function() {
        $('.delete-entry-button').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('language/lexicon/delete') }}/{{ $entry->id }}", 'Delete Entry');
        });
    });
</script>

@endsection
