<h6>Date (Optional)</h6>
<div class="row mb-2">
    @foreach((new App\Models\Subject\TimeDivision)->dateFields() as $key=>$field)
        <div class="col-md">
            {!! Form::label($field['label']) !!}
            {!! Form::number($key, isset($page->data['date'][$key]) ? $page->data['date'][$key] : null, ['class' => 'form-control']) !!}
        </div>
    @endforeach
</div>

<div class="form-group">
    {!! Form::label('Chronology (Optional)') !!} {!! add_help('The broad period of time that the event takes place in.') !!}
    {!! Form::select('parent_id', $chronologyOptions, $page->parent_id, ['class' => 'form-control', 'placeholder' => 'Select a Chronology']) !!}
</div>
