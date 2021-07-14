@extends('admin.layout')

@section('admin-title') Time - Chronology @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Time' => 'admin/data/time', 'Chronology' => 'admin/data/time/chronology']) !!}

<h1>
    Chronology
    <div class="float-right mb-3">
        <a class="btn btn-primary" href="{{ url('admin/data/time') }}">Back to Index</a>
    </div>
</h1>

<p>This is a list of sequences of time that make up the overall chronology used by pages on this site. These serve as a sort of secondary category system for events, allowing them to be placed within larger spans of time that are themselves ordered. This assists with functions such as building timelines.</p>

<p>Chronologies are optional; if they are not used, all events will be ordered as if they were all in one. If they are used but some events do not have one set, these events will be considered most recent. It's recommended to use these in combination with <a href="{{ url('admin/data/time/divisions') }}">divisions</a>-- the basic units of time for your project that can be set per-event-- and only create chronologies for your project's broadest spans of time.</p>

<div class="text-right mb-3">
    <a class="btn btn-primary" href="{{ url('admin/data/time/chronology/create') }}"><i class="fas fa-plus"></i> Create New Chronology</a>
</div>

@if(!count($chronologies))
    <p>No chronologies found.</p>
@else
    <table class="table table-sm chronology-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Abbreviation</th>
                <th>Parent Chronology</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="sortable" class="sortable">
            @foreach($chronologies as $chronology)
                <tr class="sort-item" data-id="{{ $chronology->id }}">
                    <td>
                        <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                        {!! $chronology->name !!}
                    </td>
                    <td>
                        {!! $chronology->abbreviation ? $chronology->abbreviation : '-' !!}
                    </td>
                    <td>
                        {!! $chronology->parent ? $chronology->parent->name : '-' !!}
                    </td>
                    <td class="text-right">
                        <a href="{{ url('admin/data/time/chronology/edit/'.$chronology->id) }}" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    <div class="mb-4">
        {!! Form::open(['url' => 'admin/data/time/chronology/sort']) !!}
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
