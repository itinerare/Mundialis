@extends('pages.layout')

@section('title')
    Language: {{ $entry->id ? 'Edit' : 'Create' }} Entry
@endsection

@section('pages-content')
    {!! breadcrumbs(
        ['Language' => 'language'] +
            ($entry->id && $entry->category
                ? [$entry->category->name => 'language/lexicon/' . $entry->category_id]
                : []) + [
                $entry->id ? 'Edit' : 'Create' . ' Lexicon Entry' => $entry->id
                    ? 'language/lexicon/edit/' . $entry->id
                    : 'language/lexicon/create',
            ],
    ) !!}

    <h1>{{ $entry->id ? 'Edit' : 'Create' }} Lexicon Entry
        @if ($entry->id)
            <a href="#" class="btn btn-danger float-right delete-entry-button">Delete Entry</a>
        @endif
    </h1>

    {!! Form::open(['action' => $entry->id ? 'language/lexicon/edit/' . $entry->id : 'language/lexicon/create']) !!}

    <div class="row">
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('word', 'Word') !!}
                {!! Form::text('word', $entry->word, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('category_id', 'Category (Optional)') !!} {!! add_help(
                    'While selecting a category is optional, doing so allows access to advanced settings, such as conjugation/declension of words, as set for the category.',
                ) !!}
                {!! Form::select(
                    'category_id',
                    $categoryOptions,
                    $entry->id ? $entry->category_id : Request::get('category_id'),
                    ['class' => 'form-control', 'placeholder' => 'Select a Category'],
                ) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('class', 'Part of Speech') !!}
                {!! Form::select('class', $classOptions, $entry->class, [
                    'class' => 'form-control',
                    'placeholder' => 'Select a Part of Speech',
                ]) !!}
            </div>
        </div>
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('meaning', 'Meaning') !!} {!! add_help('A concise meaning or translation of the word.') !!}
                {!! Form::text('meaning', $entry->meaning, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('pronunciation', 'Pronunciation (Optional)') !!}
                {!! Form::text('pronunciation', $entry->pronunciation, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <p>When editing the long-form definition, wiki link syntax can be used to create links to other pages on this site, and
        in fact is encouraged; not only is it convenient, links created this way are tracked by the site for various
        purposes, not the least of which is tracking wanted pages (pages which have been linked to using this system but
        which do not yet exist). Links can be created like so, using the title with disambiguation for affected pages (e.g.
        <span class="alert-secondary">Link (Place)</span>):
    </p>

    <ul>
        <li>[[Page Title Here]]</li>
        <li>[[Page Title Here|Label/Text Used for Link Here]]</li>
    </ul>

    <div class="form-group">
        {!! Form::label('definition', 'Definition (Optional)') !!} {!! add_help('If desired, you can provide a longer-form definition for the word here.') !!}
        {!! Form::textarea('definition', $entry->definition, ['class' => 'form-control wysiwyg']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('parent_id[]', 'Etymology') !!} {!! add_help('Either select an on-site lexicon entry, or enter an off-site parent word.') !!}
        <div id="etymologyList">
            @if (!$entry->id || !$entry->etymologies->count())
                <div class="mb-2 d-flex">
                    {!! Form::select('parent_id[]', $entryOptions, null, [
                        'class' => 'form-control mr-2 selectize',
                        'placeholder' => 'Select an Entry',
                    ]) !!}
                    {!! Form::text('parent[]', null, ['class' => 'form-control mr-2', 'placeholder' => 'Enter Parent Word']) !!}
                    <a href="#" class="add-etymology btn btn-link" data-toggle="tooltip"
                        title="Add another parent">+</a>
                </div>
            @else
                @foreach ($entry->etymologies as $etymology)
                    <div class="mb-2 d-flex">
                        {!! Form::select('parent_id[]', $entryOptions, $etymology->parent_id, [
                            'class' => 'form-control mr-2 selectize',
                            'placeholder' => 'Select an Entry',
                        ]) !!}
                        {!! Form::text('parent[]', $etymology->parent, [
                            'class' => 'form-control mr-2',
                            'placeholder' => 'Enter Parent Word',
                        ]) !!}
                        <a href="#" class="add-etymology btn btn-link" data-toggle="tooltip"
                            title="Add another parent">+</a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="form-group">
        {!! Form::checkbox('is_visible', 1, $entry->id ? $entry->is_visible : 1, [
            'class' => 'form-check-input',
            'data-toggle' => 'toggle',
        ]) !!}
        {!! Form::label('is_visible', 'Is Visible', ['class' => 'form-check-label ml-3']) !!} {!! add_help(
            'If this is turned off, visitors and regular users will not be able to see this entry. Users with editor permissions will still be able to see it.',
        ) !!}
    </div>

    @if ($entry->id && $entry->category)
        <h2>Conjugation/Declension Settings</h2>

        <p>Here you can set conjugated/declined forms for this word, if configured for this category. If automatic
            conjugation/declension rules are configured, you can also choose to automatically create these forms using those
            settings.</p>

        @if ($entry->category->classCombinations($entry->lexicalClass->id))
            <div class="row mb-2">
                @foreach ($entry->category->classCombinations($entry->lexicalClass->id) as $key => $combination)
                    <div
                        class="col-md-{{ count($entry->category->data[$entry->lexicalClass->id]['properties']) <= 2 && (count(collect($entry->category->data[$entry->lexicalClass->id]['properties'])->first()['dimensions']) == 2 || count(collect($entry->category->data[$entry->lexicalClass->id]['properties'])->last()['dimensions']) == 2) && count($entry->category->classCombinations($entry->lexicalClass->id)) < 20 ? 6 : (count($entry->category->classCombinations($entry->lexicalClass->id)) % 3 == 0 && count($entry->category->classCombinations($entry->lexicalClass->id)) < 30 ? 4 : (count($entry->category->classCombinations($entry->lexicalClass->id)) % 4 == 0 ? 3 : (count($entry->category->classCombinations($entry->lexicalClass->id)) < 20 ? 6 : 2))) }} mb-2">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6>{{ $combination }}</h6>
                                {!! Form::text(
                                    'conjdecl[' . $combination . ']',
                                    isset($entry->data[$combination]) ? $entry->data[$combination] : null,
                                    ['class' => 'form-control dimension-regex'],
                                ) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if (isset($entry->category->data[$entry->lexicalClass->id]['conjugation']))
                <div class="form-group">
                    {!! Form::checkbox('autoconj', 1, 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
                    {!! Form::label('autoconj', 'Auto-Generate', ['class' => 'form-check-label ml-3']) !!} {!! add_help(
                        'If this is turned on, conjugated/declined forms of this word will be generated using the settings for this entry\'s category. <strong>This will override any existing or entered forms for this word!</strong>',
                    ) !!}
                </div>
            @endif
        @else
            <p>No conjugation/declension settings configured for this category.</p>
        @endif
    @endif

    <div class="text-right">
        {!! Form::submit($entry->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

    <div class="etymology-row hide mb-2">
        {!! Form::select('parent_id[]', $entryOptions, null, [
            'class' => 'form-control mr-2 etymology-select',
            'placeholder' => 'Select an Entry',
        ]) !!}
        {!! Form::text('parent[]', null, ['class' => 'form-control mr-2', 'placeholder' => 'Enter Parent Word']) !!}
        <a href="#" class="add-etymology btn btn-link" data-toggle="tooltip" title="Add another parent">+</a>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.delete-entry-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('language/lexicon/delete') }}/{{ $entry->id }}", 'Delete Entry');
            });

            $(".selectize").selectize();

            $('.add-etymology').on('click', function(e) {
                e.preventDefault();
                addEtymologyRow($(this));
            });

            function addEtymologyRow($trigger) {
                var $clone = $('.etymology-row').clone();
                $('#etymologyList').append($clone);
                $clone.removeClass('hide etymology-row');
                $clone.addClass('d-flex');
                $clone.find('.add-etymology').on('click', function(e) {
                    e.preventDefault();
                    addEtymologyRow($(this));
                })
                $trigger.css({
                    visibility: 'hidden'
                });
                $clone.find('.etymology-select').selectize();
            }
        });
    </script>
@endsection
