@extends('admin.layout')

@section('admin-title')
    {{ $subject['name'] }} - Template
@endsection

@section('admin-content')
    {!! breadcrumbs([
        'Admin Panel' => 'admin',
        $subject['name'] => 'admin/data/' . $subject['key'],
        'Template' => 'admin/data/' . $subject['key'] . '/edit',
    ]) !!}

    <h1>
        Subject Template ({{ $subject['name'] }})
        <div class="float-right mb-3">
            <a class="btn btn-primary" href="{{ url('admin/data/' . $subject['key']) }}">Back to Index</a>
        </div>
    </h1>

    <p>This is the overall template that will be used for this subject's pages. Categories' templates can be further
        customized, but it's recommended to make smart use of this to minimize as much redundancy as possible.</p>

    {!! Form::open(['url' => 'admin/data/' . $subject['key'] . '/edit']) !!}

    @include('admin.form_builder._template_builder_content', ['template' => $template])

    @if ($template->id)
        <div class="form-group">
            {!! Form::checkbox('cascade_template', 1, 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('cascade_template', 'Cascade Template Changes (Optional)', ['class' => 'form-check-label ml-3']) !!} {!! add_help(
                'If this is turned on, any changes made to this template will cascade to categories in this subject that have customized templates. <strong>This includes removing elements!</strong> Changes can also be cascaded per-category, including recursively (optionally), for more precise cascading.',
            ) !!}
        </div>
    @endif

    <div class="text-right">
        {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

    @include('admin.form_builder._template_builder_rows')
@endsection

@section('scripts')
    @parent

    @include('admin.form_builder._template_builder_js')
@endsection
