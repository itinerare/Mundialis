<div class="card mb-2">
    <div class="card-body">
        <a href="#" class="float-right remove-field btn btn-danger mb-2">×</a>
        <div class="row">
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Key') !!}
                    {!! Form::text('field_key[]', null, ['class' => 'form-control', 'placeholder' => 'Internal key. Can\'t be duplicated in a template']) !!}
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Type') !!}
                    {!! Form::select('field_type[]', ['text' => 'Text', 'textarea' => 'Textbox', 'number' => 'Number', 'checkbox' => 'Checkbox/Toggle', 'choice' => 'Choose One', 'multiple' => 'Choose Multiple'], null, ['class' => 'form-control form-field-type', 'placeholder' => 'Select a Type']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Label') !!}
                    {!! Form::text('field_label[]', null, ['class' => 'form-control', 'placeholder' => 'Label shown on the editing form and as header if enabled']) !!}
                </div>
            </div>
            <div class="chooseOptions col-md-6">
                <div class="choiceOptions hide">
                    <div class="form-group">
                        {!! Form::label('Field Options') !!}
                        {!! Form::text('field_choices[]', null, ['class' => 'form-control', 'placeholder' => 'Enter options, separated by commas']) !!}
                    </div>
                </div>
                <div class="valueOptions show">
                    <div class="form-group">
                        {!! Form::label('Field Value (Optional)') !!}
                        {!! Form::text('field_value[]', null, ['class' => 'form-control', 'placeholder' => 'Default value for the field']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Rules (Optional)') !!} (See rules <a href="https://laravel.com/docs/8.x/validation#available-validation-rules">here</a>)
                    {!! Form::text('field_rules[]', null, ['class' => 'form-control', 'placeholder' => 'Any custom validation rules']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Field Help (Optional)') !!}
                    {!! Form::text('field_help[]', null, ['class' => 'form-control', 'placeholder' => 'Help tooltip text displayed when editing']) !!}
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Is Subsection') !!}
                    {!! Form::select('field_is_subsection[]', [0 => 'No (No header shown)', 1 => 'Yes (Header shown)', 2 => 'Yes (sub-subsection) (Shows a minor header)'], null, ['class' => 'form-control form-field-type']) !!}
                </div>
            </div>
            {!! Form::hidden('field_section[]', null, ['class' => 'form-control field-section']) !!}
        </div>
    </div>
</div>
