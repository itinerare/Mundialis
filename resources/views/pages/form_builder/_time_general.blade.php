<h6>Dates (Optional)  {!! add_help('If the event transpires over a single date, leave one or the other blank.') !!}</h6>
@foreach(['start', 'end'] as $segment)
    <div class="row mb-2">
        @foreach((new App\Models\Subject\TimeDivision)->dateFields() as $key=>$field)
            <div class="col-md">
                {!! Form::label(ucfirst($segment).' '.$field['label']) !!}
                {!! Form::number('date_'.$segment.'_'.$key, isset($page->data['date'][$segment][$key]) ? $page->data['date'][$segment][$key] : null, ['class' => 'form-control']) !!}
            </div>
        @endforeach
    </div>
@endforeach

<div class="form-group">
    {!! Form::label('Chronology (Optional)') !!} {!! add_help('The broad period of time that the event takes place in.') !!}
    {!! Form::select('parent_id', $chronologyOptions, $page->parent_id, ['class' => 'form-control', 'placeholder' => 'Select a Chronology']) !!}
</div>
