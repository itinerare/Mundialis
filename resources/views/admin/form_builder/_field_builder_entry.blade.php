<div class="card mb-2 field-list-entry">
    <div class="card-body">
        <a href="#" class="float-right remove-field btn btn-danger mb-2">×</a>
        <div class="row">
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Key') !!} {!! add_help('Internal key. Can\'t be duplicated within one form, or duplicate instances will overwrite each other. <strong>Changing this will break any existing commissions\' form responses if they use this form.') !!}
                    {!! Form::text('field_key[]', $key, ['class' => 'form-control', 'placeholder' => 'Internal key. Can\'t be duplicated']) !!}
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Type') !!}
                    {!! Form::select('field_type[]', $fieldTypes, $field['type'], ['class' => 'form-control form-field-type', 'placeholder' => 'Select a Type']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Field Label') !!}
                    {!! Form::text('field_label[]', $field['label'], ['class' => 'form-control', 'placeholder' => 'Label shown on the commission form']) !!}
                </div>
            </div>
            <div class="chooseOptions col-md-12">
                <div class="choiceOptions {{ $field['type'] == 'choice' || $field['type'] == 'multiple' ? '' : 'hide' }}">
                    <div class="form-group">
                        {!! Form::label('Field Options') !!} {!! add_help('Enter options, separated by commas. <strong>Changing this will break any existing commissions\' form responses if they use this form.') !!}
                        {!! Form::text('field_choices[]', isset($field['choices']) ? implode(',', $field['choices']) : null, ['class' => 'form-control', 'placeholder' => 'Enter options, separated by commas']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Rules (Optional)') !!} (See rules <a href="https://laravel.com/docs/8.x/validation#available-validation-rules">here</a>)
                    {!! Form::text('field_rules[]', isset($field['rules']) ? $field['rules'] : null, ['class' => 'form-control', 'placeholder' => 'Any custom validation rules']) !!}
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Value (Optional)') !!}
                    {!! Form::text('field_value[]', isset($field['value']) ? $field['value'] : null, ['class' => 'form-control', 'placeholder' => 'Default value for the field']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Field Help (Optional)') !!}
                    {!! Form::text('field_help[]', isset($field['help']) ? $field['help'] : null, ['class' => 'form-control', 'placeholder' => 'Help tooltip text displayed when editing']) !!}
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Is Subsection') !!}
                    {!! Form::select('field_is_subsection[]', [0 => 'No (No header shown)', 1 => 'Yes (Header shown)', 2 => 'Yes (sub-subsection) (Shows a minor header)'], $field['is_subsection'], ['class' => 'form-control form-field-type']) !!}
                </div>
            </div>
            {!! Form::hidden('field_section[]', $field['section'], ['class' => 'form-control field-section']) !!}
        </div>
    </div>
</div>
