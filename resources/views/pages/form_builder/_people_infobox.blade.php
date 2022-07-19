<div class="form-group">
    {!! Form::label('Name (Optional)') !!}
    {!! Form::text('people_name', isset($page->data['people_name']) ? $page->data['people_name'] : null, [
        'class' => 'form-control',
    ]) !!}
</div>

@foreach (['birth', 'death'] as $segment)
    <div class="mb-2">
        <div class="row">
            <div class="col-md-4">
                <h6>{{ ucfirst($segment) }} (Optional)</h6>
                <div class="row mb-2">
                    <div class="col-md">
                        {!! Form::label('Place') !!}
                        {!! Form::select(
                            $segment . '_place_id',
                            $placeOptions,
                            isset($page->data[$segment]['place']) ? $page->data[$segment]['place'] : null,
                            ['class' => 'form-control', 'placeholder' => 'Select a Place'],
                        ) !!}
                    </div>
                </div>
            </div>
            <div class="col-md">
                <h6>Date (Optional)</h6>
                <div class="row mb-2">
                    @foreach ((new App\Models\Subject\TimeDivision())->dateFields() as $key => $field)
                        <div class="col-md">
                            {!! Form::label($field['label']) !!}
                            {!! Form::number(
                                $segment . '_' . $key,
                                isset($page->data[$segment]['date'][$key])
                                    ? $page->data[$segment]['date'][$key]
                                    : (isset($page->data[$segment]['date'][str_replace(' ', '_', strtolower($field['label']))])
                                        ? $page->data[$segment]['date'][str_replace(' ', '_', strtolower($field['label']))]
                                        : null),
                                ['class' => 'form-control'],
                            ) !!}
                        </div>
                    @endforeach
                    <div class="col-md-4">
                        {!! Form::label('Chronology') !!} {!! add_help('The broad period of time that the event takes place in.') !!}
                        {!! Form::select(
                            $segment . '_chronology_id',
                            $chronologyOptions,
                            isset($page->data[$segment]['chronology']) ? $page->data[$segment]['chronology'] : null,
                            ['class' => 'form-control', 'placeholder' => 'Select a Chronology'],
                        ) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
