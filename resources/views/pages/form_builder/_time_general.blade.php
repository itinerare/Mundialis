<p>Events with a start date and/or chronology will be displayed in the <a href="{{ url('time/timeline') }}">timeline</a>, organized by chronology if set, and then by start date if set. Conversely, to "hide" a page in this subject from the timeline, do not set either a start date or chronology.</p>

<h6>Dates (Optional)  {!! add_help('If the event transpires over a single date, leave one or the other blank.') !!}</h6>
@foreach(['start', 'end'] as $segment)
    <div class="row mb-2">
        @foreach((new App\Models\Subject\TimeDivision)->dateFields() as $key=>$field)
            <div class="col-md">
                {!! Form::label(ucfirst($segment).' '.$field['label']) !!}
                {!! Form::number('date_'.$segment.'_'.$key, isset($page->data['date'][$segment][$key]) ? $page->data['date'][$segment][$key] : (isset($page->data['date'][$segment][str_replace(' ', '_', strtolower($field['label']))]) ? $page->data['date'][$segment][str_replace(' ', '_', strtolower($field['label']))] : null), ['class' => 'form-control']) !!}
            </div>
        @endforeach
    </div>
@endforeach

<div class="form-group">
    {!! Form::label('Chronology (Optional)') !!} {!! add_help('The broad period of time that the event takes place in.') !!}
    {!! Form::select('parent_id', $chronologyOptions, $page->parent_id, ['class' => 'form-control', 'placeholder' => 'Select a Chronology']) !!}
</div>
