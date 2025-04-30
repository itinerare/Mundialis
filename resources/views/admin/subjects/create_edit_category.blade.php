@extends('admin.layout')

@section('admin-title')
    Categories
@endsection

@section('admin-content')
    {!! breadcrumbs([
        'Admin Panel' => 'admin',
        $subject['name'] => 'admin/data/' . $subject['key'],
        ($category->id ? 'Edit' : 'Create') . ' Category' => $category->id
            ? 'admin/data/categories/edit/' . $category->id
            : 'admin/data/' . $subject['key'] . '/create',
    ]) !!}

    <h1>{{ $category->id ? 'Edit' : 'Create' }} Category
        @if ($category->id)
            <a href="#" class="btn btn-danger float-right delete-category-button">Delete Category</a>
        @endif
    </h1>

    {!! Form::open([
        'url' => $category->id
            ? 'admin/data/categories/edit/' . $category->id
            : 'admin/data/' . $subject['key'] . '/create',
        'files' => true,
    ]) !!}

    <h2>Basic Information</h2>

    <div class="row">
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', $category->name, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('parent_id', 'Parent Category (Optional)') !!}
                {!! Form::select('parent_id', $categoryOptions, $category->parent_id, [
                    'class' => 'form-control',
                    'placeholder' => 'Select a Category',
                ]) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('summary', 'Summary (Optional)') !!} {!! add_help('A short summary of the category\'s contents. This will be displayed on the category index.') !!}
        {!! Form::text('summary', $category->summary, ['class' => 'form-control']) !!}
    </div>

    <div class="row">
        @if ($category->has_image)
            <div class="col-md-4 text-center">
                Current image:<br />
                <img src="{{ $category->imageUrl }}" class="mw-100 img-thumbnail mb-2" />
            </div>
        @endif
        <div class="col-md align-self-center">
            <div class="form-group">
                {!! Form::label('image', 'Index Image (Optional)') !!} {!! add_help('This image is only used in the category index.') !!}
                <div>{!! Form::file('image') !!}</div>
                <div class="text-muted">Recommended size: 300px x 300px</div>
                @if ($category->has_image)
                    <div class="form-check">
                        {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
                        {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('description', 'Description (Optional)') !!}
        {!! Form::textarea('description', $category->description, ['class' => 'form-control wysiwyg']) !!}
    </div>

    <div class="form-group">
        {!! Form::checkbox('populate_template', 1, 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
        {!! Form::label('populate_template', 'Populate Template (Optional)', ['class' => 'form-check-label ml-3']) !!} {!! add_help(
            'If this is turned on, this category\'s template information will be populated with any information from its\' parent\'s (or subject\'s) template. <strong>This will overwrite any current template information!</strong>',
        ) !!}
    </div>

    @if ($category->id)
        <h2>Template</h2>

        @include('admin.form_builder._template_builder_content', ['template' => $category])

        <div class="row">
            <div class="col-md">
                <div class="form-group">
                    {!! Form::checkbox('cascade_template', 1, 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
                    {!! Form::label('cascade_template', 'Cascade Template Changes', ['class' => 'form-check-label ml-3']) !!} {!! add_help(
                        'If this is turned on, any changes made to this category\'s template will cascade to its sub-categories that have customized templates. <strong>This includes removing elements!</strong>',
                    ) !!}
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::checkbox('cascade_recursively', 1, 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
                    {!! Form::label('cascade_recursively', 'Cascade Changes Recursively', ['class' => 'form-check-label ml-3']) !!} {!! add_help(
                        'If this and cascading changes are turned on, changes will cascade recursively. <strong>This includes removing elements!</strong>',
                    ) !!}
                </div>
            </div>
        </div>
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
        $(document).ready(function() {
            $('.delete-category-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/categories/delete') }}/{{ $category->id }}",
                    'Delete Category');
            });
        });
    </script>
@endsection
