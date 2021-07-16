<div class="form-group">
    {!! Form::label('Parent Place (Optional)') !!}
    {!! Form::select('parent_place_id', $placeOptions, $page->parentPlace ? $page->parentPlace->id : null, ['class' => 'form-control', 'placeholder' => 'Select a Place']) !!}
</div>
