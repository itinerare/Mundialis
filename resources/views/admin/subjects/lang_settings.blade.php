@extends('admin.layout')

@section('admin-title') Language - Lexicon Settings @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Language' => 'admin/data/language', 'Lexicon Settings' => 'admin/data/language/lexicon-settings']) !!}

<h1>
    Lexicon Settings
    <div class="float-right mb-3">
        <a class="btn btn-primary" href="{{ url('admin/data/language') }}">Back to Index</a>
    </div>
</h1>

<p>These are the lexical classes, or parts of speech, that will be used/available when adding words to your site's lexicon. By default, this is populated with commonly used English lexical classes during set-up and may not need to be modified. Once created, parts of speech can be sorted as desired. Please note that <strong>deleting parts of speech from this list will cause any <a href="{{ url('admin/data/language/lexicon-categories') }}">lexicon category settings</a> that depend on them to be deleted</strong>, even if they are re-added.</p>

{!! Form::open(['url' => 'admin/data/language/lexicon-settings']) !!}

<table class="table table-sm part-table">
    <thead>
        <tr>
            <th width="50%">Name {!! add_help('The part of speech\'s name.') !!}</th>
            <th>Abbreviation (Optional)</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="sortable" class="sortable part-list">
        @if(count($parts))
            @foreach($parts as $part)
                <tr class="sort-item" data-id="{{ $part->id }}">
                    <td class="d-flex">
                        <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                        {!! Form::hidden('id[]', $part->id, ['class' => 'form-control']) !!}
                        {!! Form::text('name[]', $part->name, ['class' => 'form-control']) !!}
                    </td>
                    <td>
                        {!! Form::text('abbreviation[]', $part->abbreviation, ['class' => 'form-control']) !!}
                    </td>
                    <td>
                        <a href="#" class="float-right remove-part btn btn-danger mb-2">×</a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="text-right">
    <a href="#" class="btn btn-outline-primary btn-sm add-part">Add Part of Speech</a>
</div>

<div class="mb-4">
    {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
</div>

<div class="text-right">
    {!! Form::submit('Edit Parts of Speech', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

<div class="hide mb-2">
    <table>
        <tr class="part-row sort-item">
            <td>
                {!! Form::text('name[]', null, ['class' => 'form-control']) !!}
            </td>
            <td>
                {!! Form::text('abbreviation[]', null, ['class' => 'form-control']) !!}
            </td>
            <td>
                <a href="#" class="float-right remove-part btn btn-danger mb-2">×</a>
            </td>
        </tr>
    </table>
</div>

@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('#partList .part-list-entry').each(function(index) {
            attachFieldTypeListener($(this));
        });

        $('.add-part').on('click', function(e) {
            e.preventDefault();
            addPartRow();
        });
        $('.remove-part').on('click', function(e) {
            e.preventDefault();
            removePartRow($(this));
        })
        function addPartRow() {
            var $clone = $('.part-row').clone();
            $('.part-list').append($clone);
            $clone.removeClass('part-row');
            $clone.find('.remove-part').on('click', function(e) {
                e.preventDefault();
                removePartRow($(this));
            });
        }
        function removePartRow($trigger) {
            $trigger.parent().parent().remove();
        }

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
