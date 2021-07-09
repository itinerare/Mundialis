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

<p>These are the divisions of time that will be used for your site and when creating and editing events. Feel free to be as narrow or as broad in focus as suits your project. Setting these is semi-optional; if you do not specify any divisions of time, you will be able to specify only the year for any events. Once created, divisions can be sorted. If divisions are set, they will be displayed along with other basic information on your project's timeframe and events.</p>

{!! Form::open(['url' => 'admin/data/time/divisions']) !!}

<table class="table table-sm division-table">
    <thead>
        <tr>
            <th width="40%">Name {!! add_help('The division\'s name.') !!}</th>
            <th width="33%">Abbreviation (Optional) {!! add_help('e.g. \'min\' for minute.') !!}</th>
            <th>Unit (Optional) {!! add_help('The amount of the division that are in the next up, e.g. 24 for hours.') !!}</th>
        </tr>
    </thead>
    <tbody id="sortable" class="sortable division-list">
        @if(count($divisions))
            @foreach($divisions as $division)
                <tr class="sort-item" data-id="{{ $division->id }}">
                    <td class="d-flex">
                        <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                        {!! Form::text('name[]', $division->name, ['class' => 'form-control']) !!}
                    </td>
                    <td>
                        {!! Form::text('abbreviation[]', $division->abbreviation, ['class' => 'form-control']) !!}
                    </td>
                    <td>
                        {!! Form::number('unit[]', $division->unit, ['class' => 'form-control']) !!}
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

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
    <table>
        <tr class="division-row sort-item">
            <td>
                {!! Form::text('name[]', null, ['class' => 'form-control']) !!}
            </td>
            <td>
                {!! Form::text('abbreviation[]', null, ['class' => 'form-control']) !!}
            </td>
            <td>
                {!! Form::number('unit[]', null, ['class' => 'form-control']) !!}
            </td>
        </tr>
    </table>
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
