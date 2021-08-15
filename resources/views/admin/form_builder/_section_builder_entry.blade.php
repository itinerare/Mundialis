<div class="sort-item section-list-entry" data-id="{{ $key }}">
    <a class="float-left fas fa-arrows-alt-v handle mr-3" href="#"></a>
    <a href="#" class="float-right remove-section btn btn-danger mb-2">×</a>
    <div class="row">
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('Section Key') !!}
                {!! Form::text('section_key[]', $key, ['class' => 'form-control', 'placeholder' => 'Internal key. Can\'t be duplicated in a template']) !!}
            </div>
        </div>
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('Section Name') !!}
                {!! Form::text('section_name[]', $section['name'], ['class' => 'form-control', 'placeholder' => 'Section name/header']) !!}
            </div>
        </div>
    </div>
    <div>
        <div class="field-list">
            @if(isset($template->data['fields'][$key]))
                @foreach($template->data['fields'][$key] as $fieldKey=>$field)
                    @include('admin.form_builder._field_builder_entry', ['key' => $fieldKey, 'field' => $field, 'section' => $key])
                @endforeach
            @endif
        </div>
        <div class="text-right mb-3">
            <a href="#" class="btn btn-outline-info add-field" value="{{ $key }}">Add Field</a>
        </div>
    </div>
    <hr/>
</div>
