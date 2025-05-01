<div class="card mb-2 infobox-list-entry sort-item" data-id="{{ $key }}">
    <div class="card-body">
        <a class="float-left fas fa-arrows-alt-v handle mr-3" href="#"></a>
        <a href="#" class="float-right remove-infobox btn btn-danger mb-2">×</a>
        <div class="row">
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('infobox_key[]', 'Field Key') !!} {!! add_help(
                        'Internal key. Can\'t be duplicated within one form, or duplicate instances will overwrite each other. <strong>Changing this will break any existing pages if they use this template.</strong>',
                    ) !!}
                    {!! Form::text('infobox_key[]', $key, [
                        'class' => 'form-control',
                        'placeholder' => 'Internal key. Can\'t be duplicated',
                    ]) !!}
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('infobox_type[]', 'Field Type') !!}
                    {!! Form::select(
                        'infobox_type[]',
                        [
                            'text' => 'Text',
                            'number' => 'Number',
                            'checkbox' => 'Checkbox/Toggle',
                            'choice' => 'Choose One',
                            'multiple' => 'Choose Multiple',
                        ],
                        $field['type'],
                        ['class' => 'form-control form-field-type', 'placeholder' => 'Select a Type'],
                    ) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('infobox_label[]', 'Field Label') !!}
                    {!! Form::text('infobox_label[]', $field['label'], [
                        'class' => 'form-control',
                        'placeholder' => 'Label shown on the commission form',
                    ]) !!}
                </div>
            </div>
            <div class="chooseOptions col-md-6">
                <div
                    class="choiceOptions {{ $field['type'] == 'choice' || $field['type'] == 'multiple' ? 'show' : 'hide' }}">
                    <div class="form-group">
                        {!! Form::label('infobox_choices[]', 'Field Options') !!} {!! add_help(
                            'Enter options, separated by commas. <strong>Changing this will break and/or change any existing pages if they use this template.</strong>',
                        ) !!}
                        {!! Form::text('infobox_choices[]', isset($field['choices']) ? implode(',', $field['choices']) : null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter options, separated by commas',
                        ]) !!}
                    </div>
                </div>
                <div
                    class="valueOptions {{ $field['type'] == 'choice' || $field['type'] == 'multiple' ? 'hide' : 'show' }}">
                    <div class="form-group">
                        {!! Form::label('infobox_value[]', 'Field Value (Optional)') !!}
                        {!! Form::text('infobox_value[]', isset($field['value']) ? $field['value'] : null, [
                            'class' => 'form-control',
                            'placeholder' => 'Default value for the field',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('infobox_rules[]', 'Field Rules (Optional)') !!} (See rules <a
                        href="https://laravel.com/docs/12.x/validation#available-validation-rules">here</a>)
                    {!! Form::text('infobox_rules[]', isset($field['rules']) ? $field['rules'] : null, [
                        'class' => 'form-control',
                        'placeholder' => 'Any custom validation rules',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('infobox_help[]', 'Field Help (Optional)') !!}
                    {!! Form::text('infobox_help[]', isset($field['help']) ? $field['help'] : null, [
                        'class' => 'form-control',
                        'placeholder' => 'Help tooltip text displayed when editing',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</div>
