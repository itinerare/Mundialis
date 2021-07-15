@extends('admin.layout')

@section('admin-title') Language - Lexicon Categories @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Language' => 'admin/data/language', 'Lexicon Categories' => 'admin/data/time/lexicon-categories']) !!}

<h1>
    Lexicon Categories
    <div class="float-right mb-3">
        <a class="btn btn-primary" href="{{ url('admin/data/language') }}">Back to Index</a>
    </div>
</h1>

<p>This is a list of categories that entries in the project's lexicon can be sorted into. Note that these are different from parts of speech (noun, verb, etc.) which are accounted for by <a href="{{ url('admin/data/language/lexicon-settings') }}">lexicon settings</a>. Like page categories, these can be nested, and can represent different things depending on the scope of your project-- for instance, you could use them simply to categorize different sets of terminology used in your project, or to handle whole languages and groups thereof. Lexicon categories are semi-optional-- you do not need to create any to add words to the lexicon, but advanced features such as creating cases and auto-declension/conjucation are only available via categories.</p>

<div class="text-right mb-3">
    <a class="btn btn-primary" href="{{ url('admin/data/language/lexicon-categories/create') }}"><i class="fas fa-plus"></i> Create New Category</a>
</div>

@if(!count($categories))
    <p>No categories found.</p>
@else
    <table class="table table-sm category-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Parent Category</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="sortable" class="sortable">
            @foreach($categories as $category)
                <tr class="sort-item" data-id="{{ $category->id }}">
                    <td>
                        <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                        {!! $category->name !!}
                    </td>
                    <td>
                        {!! $category->parent ? $category->parent->name : '-' !!}
                    </td>
                    <td class="text-right">
                        <a href="{{ url('admin/data/categories/edit/'.$category->id) }}" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    <div class="mb-4">
        {!! Form::open(['url' => 'admin/data/language/lexicon-categories/sort']) !!}
        {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
        {!! Form::submit('Save Order', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@endif

@endsection

@section('scripts')
@parent
<script>

$( document ).ready(function() {
    $('.handle').on('click', function(e) {
        e.preventDefault();
    });
    $( "#sortable" ).sortable({
        items: '.sort-item',
        handle: ".handle",
        placeholder: "sortable-placeholder",
        stop: function( event, ui ) {
            $('#sortableOrder').val($(this).sortable("toArray", {attribute:"data-id"}));
        },
        create: function() {
            $('#sortableOrder').val($(this).sortable("toArray", {attribute:"data-id"}));
        }
    });
    $( "#sortable" ).disableSelection();
});
</script>
@endsection
