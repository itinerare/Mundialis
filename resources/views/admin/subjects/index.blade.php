@extends('admin.layout')

@section('admin-title') {{ $subjectName }} @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', $subjectName => 'admin/data/'.$subject]) !!}

<h1>{{ $subjectName }}</h1>

<p>This is a list of categories that will be used to sort pages.</p>

<div class="text-right mb-3">
    <a class="btn btn-primary" href="{{ url('admin/data/'.$subject.'/edit') }}"><i class="fas fa-edit"></i> Edit Template</a>
    <a class="btn btn-primary" href="{{ url('admin/data/'.$subject.'/create') }}"><i class="fas fa-plus"></i> Create New Category</a>
</div>

@if(!count($categories))
    <p>No categories found.</p>
@else
    <table class="table table-sm project-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Visibility</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="sortable" class="sortable">
            @foreach($categories as $category)
                <tr class="sort-item" data-id="{{ $project->id }}">
                    <td>
                        <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                        {!! $category->name !!}
                    </td>
                    <td>
                        {!! $category->is_visible ? '<i class="text-success fas fa-check"></i>' : '-' !!}
                    </td>
                    <td class="text-right">
                        <a href="{{ url('admin/data/'.$subject.'/edit/'.$category->id) }}" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    <div class="mb-4">
        {!! Form::open(['url' => 'admin/data/'.$subject.'/sort']) !!}
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
