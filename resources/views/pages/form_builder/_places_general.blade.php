<div class="form-group">
    {!! Form::label('Parent Place (Optional)') !!} {!! add_help(
        'The location that the location being edited exists within. For instance, countries exist within continents, neighborhoods exist within cities, etc.',
    ) !!}
    {!! Form::select('parent_id', $placeOptions, $page->parent_id, [
        'class' => 'form-control',
        'placeholder' => 'Select a Place',
    ]) !!}
</div>
