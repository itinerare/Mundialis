<div class="form-group">
    {!! Form::label('parent_id', 'Parent Faction (Optional)') !!} {!! add_help(
        'The faction that the faction being edited exists within. For instance, departments exist within organizations, etc.',
    ) !!}
    {!! Form::select('parent_id', $factionOptions, $page->parent_id, [
        'class' => 'form-control',
        'placeholder' => 'Select a Faction',
    ]) !!}
</div>
