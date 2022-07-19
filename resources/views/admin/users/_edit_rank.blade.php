@if ($rank)
    {!! Form::open(['url' => 'admin/ranks/edit/' . $rank->id]) !!}

    <div class="form-group">
        {!! Form::label('Rank Name') !!}
        {!! Form::text('name', $rank->name, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Description (optional)') !!}
        {!! Form::textarea('description', $rank->description, ['class' => 'form-control']) !!}
    </div>

    <div class="text-right">
        {!! Form::submit($rank->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid rank selected.
@endif
