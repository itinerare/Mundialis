@if ($chronology)
    {!! Form::open(['url' => 'admin/data/time/chronology/delete/' . $chronology->id]) !!}

    <p>You are about to delete the chronology <strong>{{ $chronology->name }}</strong>. This is not reversible. If
        events or sub-chronologies in this chronology exist, you will not be able to delete this chronology.</p>
    <p>Are you sure you want to delete <strong>{{ $chronology->name }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Chronology', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid chronology selected.
@endif
