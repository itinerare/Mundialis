@extends('admin.layout')

@section('admin-title') Language - Lexicon Categories @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Language' => 'admin/data/language', 'Lexicon Categories' => 'admin/data/language/lexicon-categories', ($category->id ? 'Edit' : 'Create').' Lexicon Category' => $category->id ? 'admin/data/language/lexicon-categories/edit/'.$category->id : 'admin/data/language/lexicon-categories/create']) !!}

<h1>{{ $category->id ? 'Edit' : 'Create' }} Lexicon Category
    @if($category->id)
        <a href="#" class="btn btn-danger float-right delete-category-button">Delete Category</a>
    @endif
</h1>

{!! Form::open(['url' => $category->id ? 'admin/data/language/lexicon-categories/edit/'.$category->id : 'admin/data/language/lexicon-categories/create']) !!}

<h2>Basic Information</h2>

<div class="row">
    <div class="col-md">
        <div class="form-group">
            {!! Form::label('Name') !!}
            {!! Form::text('name', $category->name, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md">
        <div class="form-group">
            {!! Form::label('Parent Category (Optional)') !!}
            {!! Form::select('parent_id', $categoryOptions, $category->parent_id, ['class' => 'form-control', 'placeholder' => 'Select a Category']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $category->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<h2>Declension/Conjugation Settings</h2>

<p>Here you can specify different declension/conjugation settings for the different parts of speech set in <a href="{{ url('admin/data/language/lexicon-settings') }}">lexicon settings</a>. These settings are twofold; first, you can specify different properties, such as case or number, and the dimensions thereof. Second, once properties have been added, you can specify automatic conjugation/declension rules for each combination of these dimensions and/or for each non-dimensional property that can be applied to words within this category to automatically generate each form of the word. <strong>These settings are entirely optional</strong>.</p>

@foreach($classes as $class)
    <div class="mb-3">
        <h4>
            {{ $class->name }}
            <a class="small collapse-toggle collapsed" href="#{{ strtolower($class->name) }}" data-toggle="collapse">Show</a></h3>
        </h4>
        <div class="collapse" id="{{ strtolower($class->name) }}">
            <h5>Properties</h5>
            <div class="property-list sortable">
                @if(isset($category->data[$class->id]['properties']))
                    @foreach($category->data[$class->id]['properties'] as $key=>$values)
                        <div class="sort-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('Property Name') !!}
                                        <div class="d-flex">
                                            <a class="fas fa-arrows-alt-v handle mr-2" href="#"></a>
                                            {!! Form::text('property_name[]', $values['name'], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('Dimensions') !!} {!! add_help('Enter any number of dimensions, or leave blank to mark as non-dimensional.') !!}
                                        <div class="d-flex">
                                            {!! Form::text('property_dimensions[]', isset($values['dimensions']) ? implode(',', $values['dimensions']) : null, ['class' => 'form-control dimension-list', 'multiple']) !!}
                                            <a href="#" class="remove-property btn btn-danger ml-2 mb-2">×</a>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::hidden('property_class[]', $class->id, ['class' => 'form-control property-class']) !!}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-right mb-3">
                <a href="#" class="btn btn-sm btn-outline-info add-property" value="{{ $class->id }}">Add Property</a>
            </div>

            @if(isset($category->data[$class->id]['properties']))
                <h5>Auto-Conjugation/Declension Rules</h5>
                <p>Each combination of the above dimensions and properties specified above is listed here. To set rules for autogeneration of their conjugated/declined forms, enter in one or more criteria (regex pattern), a regex pattern for what part of the word to replace, and what to replace it with. If multiple criteria are entered, multiple replacements should be entered. Regex patterns may either be entered for each criteria/replacement pair, or only one pattern may be entered for all pairs. As commas may be used within regex, semicolons are used as the delimiter for these fields instead.</p>
                <div class="row mb-2">
                    @foreach($category->classCombinations($class->id) as $key=>$combination)
                        <div class="col-md-{{ (count($category->data[$class->id]['properties']) <= 2 && (count(collect($category->data[$class->id]['properties'])->first()['dimensions']) == 2 || count(collect($category->data[$class->id]['properties'])->last()['dimensions']) == 2)) && count($category->classCombinations($class->id)) < 20 ? 6 : (count($category->classCombinations($class->id))%3 == 0 && count($category->classCombinations($class->id)) < 30 ? 4 : (count($category->classCombinations($class->id))%4 == 0 ? 3 : (count($category->classCombinations($class->id)) < 20 ? 6 : 2))) }} mb-2">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6>{{ $combination }}</h6>
                                    <div class="row">
                                        <div class="col-md">
                                            {!! Form::label('Criteria') !!} {!! add_help('Enter one or more regex patterns. Only words matching these criteria will have transformations applied to them.') !!}
                                            {!! Form::text('declension_criteria['.$class->id.']['.$key.']', isset($category->data[$class->id]['conjugation'][$key]) ? implode(';', $category->data[$class->id]['conjugation'][$key]['criteria']) : null, ['class' => 'form-control dimension-regex']) !!}
                                        </div>
                                        <div class="w-100 mb-1"></div>
                                        <div class="col-md">
                                            {!! Form::label('Regex') !!} {!! add_help('Enter one or more regex patterns to replace.') !!}
                                            {!! Form::text('declension_regex['.$class->id.']['.$key.']', isset($category->data[$class->id]['conjugation'][$key]) ? implode(';', $category->data[$class->id]['conjugation'][$key]['regex']) : null, ['class' => 'form-control dimension-regex']) !!}
                                        </div>
                                        <div class="col-md">
                                            {!! Form::label('Replacement') !!} {!! add_help('Enter one or more series of characters to replace the pattern(s) with.') !!}
                                            {!! Form::text('declension_replacement['.$class->id.']['.$key.']', isset($category->data[$class->id]['conjugation'][$key]) ? implode(';', $category->data[$class->id]['conjugation'][$key]['replacement']) : null, ['class' => 'form-control dimension-regex']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <hr/>
        </div>
    </div>
@endforeach

<div class="text-right">
    {!! Form::submit($category->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

<div class="hide mb-2 property-row">
    <div class="sort-item">
        <div class="row">
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Property Name') !!}
                    <div class="d-flex">
                        <a class="fas fa-arrows-alt-v handle mr-2" href="#"></a>
                        {!! Form::text('property_name[]', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Dimensions') !!}
                    <div class="d-flex">
                        {!! Form::text('property_dimensions[]', null, ['class' => 'form-control dimension-list', 'multiple', 'placeholder' => 'Enter any number of dimensions here, or leave blank to mark as non-dimensional']) !!}
                        <a href="#" class="remove-property btn btn-danger ml-2 mb-2">×</a>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::hidden('property_class[]', null, ['class' => 'form-control property-class']) !!}
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {
    $('.delete-category-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/data/language/lexicon-categories/delete') }}/{{ $category->id }}", 'Delete Category');
    });

    $('.handle').on('click', function(e) {
        e.preventDefault();
    });
    $( ".sortable" ).sortable({
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
    $( ".sortable" ).disableSelection();

    $('.add-property').on('click', function(e) {
        e.preventDefault();
        addPropertyRow($(this));
    });
    $('.remove-property').on('click', function(e) {
        e.preventDefault();
        removePropertyRow($(this));
    });

    function addPropertyRow(node) {
        var $clone = $('.property-row').clone();
        node.parent().parent().find('.property-list').append($clone);
        $clone.removeClass('hide property-row');
        $clone.find('.remove-property').on('click', function(e) {
            e.preventDefault();
            removePropertyRow($(this));
        });
        console.log(node.attr("value"));
        $clone.find('.property-class').attr('value', node.attr("value"));
        $clone.find('.dimension-list').selectize({
            delimiter: ",",
            persist: false,
            create: function (input) {
                return {
                    value: input,
                    text: input,
                };
            },
        });
    }
    function removePropertyRow($trigger) {
        $trigger.parent().parent().parent().parent().remove();
    }

    $('.property-list .dimension-list').selectize({
        delimiter: ",",
        persist: false,
        create: function (input) {
            return {
                value: input,
                text: input,
            };
        },
    });

    $('.dimension-regex').selectize({
        delimiter: ";",
        persist: false,
        create: function (input) {
            return {
                value: input,
                text: input,
            };
        },
    });

});

</script>
@endsection
