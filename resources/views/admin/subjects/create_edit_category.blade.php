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

<h2>Template</h2>

@include('admin.form_builder._template_builder_content', ['template' => $category])

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
