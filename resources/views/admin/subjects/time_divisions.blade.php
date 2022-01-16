@extends('admin.layout')

@section('admin-title') Time - Divisions @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Time' => 'admin/data/time', 'Divisions' => 'admin/data/time/divisions']) !!}

<h1>
    Divisions of Time
    <div class="float-right mb-3">
        <a class="btn btn-primary" href="{{ url('admin/data/time') }}">Back to Index</a>
    </div>
</h1>

<p>These are the divisions of time that will be used for your site and when creating and editing events. Feel free to be as narrow or as broad in focus as suits your project. Setting these is semi-optional; if you do not specify any divisions of time, you will be able to specify only the year for any events. Once created, divisions can be sorted; they should be sorted from largest to smallest. If divisions are set, they will be displayed along with other basic information on your project's timeframe and events. It's recommended to have the largest division of time correspond to a year at most.</p>

<p>Divisions of time have the following properties, in order:</p>
<ul>
    <li>Name</li>
    <li>Abbreviation (Optional), e.g. "min" for minute</li>
    <li>Unit (Optional), the amount of the division that are in the next largest, e.g. 24 for hours</li>
    <li>Use for Dates, which controls whether or not the division is used when entering dates</li>
</ul>

{!! Form::open(['url' => 'admin/data/time/divisions']) !!}

<div id="sortable" class="sortable division-list">
    @if(count($divisions))
        @foreach($divisions as $division)
            <div class="input-group sort-item mb-3" data-id="{{ $division->id }}">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <a class="fas fa-arrows-alt-v handle" href="#"></a>
                    </span>
                </div>
                {!! Form::hidden('id[]', $division->id, ['class' => 'form-control']) !!}
                {!! Form::text('name[]', $division->name, ['class' => 'form-control', 'aria-label' => 'Division Name', 'placeholder' => 'Name']) !!}
                {!! Form::text('abbreviation[]', $division->abbreviation, ['class' => 'form-control', 'aria-label' => 'Division Abbreviation', 'placeholder' => 'Abbreviation']) !!}
                {!! Form::number('unit[]', $division->unit, ['class' => 'form-control', 'aria-label' => 'Division Unit', 'placeholder' => 'Unit']) !!}
                <div class="input-group-append">
                    <div class="input-group-text">
                        {!! Form::checkbox('use_for_dates['.$division->id.']', 1, $division->use_for_dates, ['aria-label' => 'Use for Dates', 'data-toggle' => 'tooltip', 'title' => 'Use for Dates']) !!}
                    </div>
                    <button href="#" class="btn remove-division btn btn-danger" type="button" id="button-addon2">x</button>
                </div>
            </div>
        @endforeach
    @endif
</div>

<div class="text-right">
    <a href="#" class="btn btn-outline-primary btn-sm add-division">Add Division</a>
</div>

<div class="mb-4">
    {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
</div>

<div class="text-right">
    {!! Form::submit('Edit Divisions', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

<div class="hide mb-2">
    <div class="division-row input-group sort-item mb-3">
        {!! Form::text('name[]', null, ['class' => 'form-control', 'aria-label' => 'Division Name', 'placeholder' => 'Name']) !!}
        {!! Form::text('abbreviation[]', null, ['class' => 'form-control', 'aria-label' => 'Abbreviation', 'placeholder' => 'Abbreviation']) !!}
        {!! Form::number('unit[]', null, ['class' => 'form-control', 'aria-label' => 'Division Unit', 'placeholder' => 'Unit']) !!}
        <div class="input-group-append">
            <button href="#" class="btn remove-division btn btn-danger" type="button" id="button-addon2">x</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('#divisionList .division-list-entry').each(function(index) {
            attachFieldTypeListener($(this));
        });

        $('.add-division').on('click', function(e) {
            e.preventDefault();
            addDivisionRow();
        });
        $('.remove-division').on('click', function(e) {
            e.preventDefault();
            removeDivisionRow($(this));
        })
        function addDivisionRow() {
            var $clone = $('.division-row').clone();
            $('.division-list').append($clone);
            $clone.removeClass('division-row');
            $clone.find('.remove-division').on('click', function(e) {
                e.preventDefault();
                removeDivisionRow($(this));
            });
        }
        function removeDivisionRow($trigger) {
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
