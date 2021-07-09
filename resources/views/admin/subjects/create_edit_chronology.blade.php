@extends('admin.layout')

@section('admin-title') Time - Chronology @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Time' => 'admin/data/time', 'Chronology' => 'admin/data/time/chronology', ($chronology->id ? 'Edit' : 'Create').' Chronology' => $chronology->id ? 'admin/data/time/chronology/edit/'.$chronology->id : 'admin/data/time/chronology/create']) !!}

<h1>{{ $chronology->id ? 'Edit' : 'Create' }} Chronology
    @if($chronology->id)
        <a href="#" class="btn btn-danger float-right delete-chronology-button">Delete Chronology</a>
    @endif
</h1>

{!! Form::open(['url' => $chronology->id ? 'admin/data/categories/edit/'.$chronology->id : 'admin/data/time/chronology/create']) !!}

<h2>Basic Information</h2>

<div class="row">
    <div class="col-md">
        <div class="form-group">
            {!! Form::label('Name') !!}
            {!! Form::text('name', $chronology->name, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md">
        <div class="form-group">
            {!! Form::label('Abbreviation (Optional)') !!}
            {!! Form::text('abbreviation', $chronology->abbreviation, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('Parent Chronology (Optional)') !!}
    {!! Form::select('parent_id', $chronologyOptions, $chronology->parent_id, ['class' => 'form-control', 'placeholder' => 'Select a Chronology']) !!}
</div>

<div class="form-group">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $chronology->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="text-right">
    {!! Form::submit($chronology->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}


@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {
    $('.delete-chronology-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/data/time/chronology/delete') }}/{{ $chronology->id }}", 'Delete Chronology');
    });
});

</script>
@endsection
