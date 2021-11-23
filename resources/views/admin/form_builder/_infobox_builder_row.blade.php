<div class="card mb-2 infobox-list-entry sort-item">
    <div class="card-body">
        <a class="float-left fas fa-arrows-alt-v handle mr-3" href="#"></a>
        <a href="#" class="float-right remove-infobox btn btn-danger mb-2">×</a>
        <div class="row">
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Key') !!}
                    {!! Form::text('infobox_key[]', null, ['class' => 'form-control', 'placeholder' => 'Internal key. Can\'t be duplicated in a template']) !!}
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Type') !!}
                    {!! Form::select('infobox_type[]',  ['text' => 'Text', 'number' => 'Number', 'checkbox' => 'Checkbox/Toggle', 'choice' => 'Choose One', 'multiple' => 'Choose Multiple'], null, ['class' => 'form-control form-field-type', 'placeholder' => 'Select a Type']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Label') !!}
                    {!! Form::text('infobox_label[]', null, ['class' => 'form-control', 'placeholder' => 'Label shown on the editing form and page']) !!}
                </div>
            </div>
            <div class="chooseOptions col-md-12">
                <div class="choiceOptions hide">
                    <div class="form-group">
                        {!! Form::label('Field Options') !!}
                        {!! Form::text('infobox_choices[]', null, ['class' => 'form-control', 'placeholder' => 'Enter options, separated by commas']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Rules (Optional)') !!} (See rules <a href="https://laravel.com/docs/8.x/validation#available-validation-rules">here</a>)
                    {!! Form::text('infobox_rules[]', null, ['class' => 'form-control', 'placeholder' => 'Any custom validation rules']) !!}
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Field Value (Optional)') !!}
                    {!! Form::text('infobox_value[]', null, ['class' => 'form-control', 'placeholder' => 'Default value for the field']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Field Help (Optional)') !!}
                    {!! Form::text('infobox_help[]', null, ['class' => 'form-control', 'placeholder' => 'Help tooltip text displayed when editing']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
