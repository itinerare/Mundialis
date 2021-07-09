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
                    {!! Form::text('section_name[]', $section['name'], ['class' => 'form-control', 'placeholder' => 'Section name/header']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Focus Subject (Optional)') !!}
                    @php $configSubjects = Config::get('mundialis.subjects'); foreach($configSubjects as $subject=>$values) $subjects[$subject] = $values['name']; @endphp
                    {!! Form::select('section_subject[]', $subjects, isset($section['subject']) ? $section['subject'] : null, ['class' => 'form-control form-field-type', 'placeholder' => 'Select a subject; this allows relating the subject\'s page(s) when editing a page']) !!}
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="text-right mb-3">
            <a href="#" class="btn btn-outline-info add-widget mr-1" value="{{ $key }}">Add Widget</a>
            <a href="#" class="btn btn-outline-info add-field" value="{{ $key }}">Add Field</a>
        </div>
        <div class="widget-list">
            @if(isset($template->data['widgets'][$key]))
                @foreach($template->data['widgets'][$key] as $widget)
                    @include('admin.form_builder._widget_builder_entry', ['widget' => $widget, 'section' => $key])
                @endforeach
            @endif
        </div>
        <div class="field-list">
            @if(isset($template->data['fields'][$key]))
                @foreach($template->data['fields'][$key] as $fieldKey=>$field)
                    @include('admin.form_builder._field_builder_entry', ['key' => $fieldKey, 'field' => $field, 'section' => $key])
                @endforeach
            @endif
        </div>
    </div>
    <hr/>
</div>
