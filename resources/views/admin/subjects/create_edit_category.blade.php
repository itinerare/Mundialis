@extends('admin.layout')

@section('admin-title') Categories @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', $subject['name'] => 'admin/data/'.$subject['key'], ($category->id ? 'Edit' : 'Create').' Category' => $category->id ? 'admin/data/categories/edit/'.$category->id : 'admin/data/'.$subject['key'].'/create']) !!}

<h1>{{ $category->id ? 'Edit' : 'Create' }} Category
    @if($category->id)
        <a href="#" class="btn btn-danger float-right delete-category-button">Delete Category</a>
    @endif
</h1>

{!! Form::open(['url' => $category->id ? 'admin/data/categories/edit/'.$category->id : 'admin/data/'.$subject['key'].'/create']) !!}

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

<div class="form-group">
    {!! Form::checkbox('populate_template', 1, 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
    {!! Form::label('populate_template', 'Populate Template (Optional)', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If this is turned on, this category\'s template information will be populated with any information from its\' subject\'s template. <strong>This will overwrite any current template information!</strong>') !!}
</div>

@if($category->id)
    <h2>Template</h2>

    @include('admin.form_builder._template_builder_content', ['template' => $category])
@endif

<div class="text-right">
    {!! Form::submit($category->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

@include('admin.form_builder._template_builder_rows')

@endsection

@section('scripts')
@parent
@include('admin.form_builder._template_builder_js')
<script>
$( document ).ready(function() {
    $('.delete-category-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/data/categories/delete') }}/{{ $category->id }}", 'Delete Category');
    });
});

</script>
@endsection