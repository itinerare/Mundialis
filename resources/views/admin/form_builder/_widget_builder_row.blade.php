<div class="card mb-2">
    <div class="card-body">
        <a href="#" class="float-right remove-widget btn btn-danger mb-2">×</a>
        <div class="row">
            <div class="col-md">
                <div class="form-group">
                    {!! Form::label('Widget') !!}
                    @php $configWidgets = Config::get('mundialis.widgets'); foreach($configWidgets as $widget=>$values) $widgets[$widget] = ucfirst($widget).(isset($values['description']) ? ': '.$values['description'] : ''); @endphp
                    {!! Form::select('widget_key[]', $widgets, null, ['class' => 'form-control', 'placeholder' => 'Select a Widget']) !!}
                </div>
            </div>
            {!! Form::hidden('widget_section[]', null, ['class' => 'form-control widget-section']) !!}
        </div>
    </div>
</div>
