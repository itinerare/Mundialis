<div class="section-list-entry">
    <div>
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
                    {!! Form::text('section_name[]', $name, ['class' => 'form-control', 'placeholder' => 'Section name/header']) !!}
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="text-right mb-3">
            <a href="#" class="btn btn-outline-info add-field" value="{{ $key }}">Add Field</a>
        </div>
        <div class="field-list">
            @if(isset($template->data['fields']))
                @foreach($template->data['fields'] as $key=>$field)
                    @include('admin.form_builder._field_builder_entry', ['key' => $key, 'field' => $field])
                @endforeach
            @endif
        </div>
    </div>
    <hr/>
</div>
