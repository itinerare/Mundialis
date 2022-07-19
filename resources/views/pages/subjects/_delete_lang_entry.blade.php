@if ($entry)
    {!! Form::open(['url' => 'language/lexicon/delete/' . $entry->id]) !!}

    <p>You are about to delete the lexicon entry <strong>{{ $entry->word }}</strong>. This is not reversible.</p>

    <p>Are you sure you want to delete the entry for <strong>{{ $entry->word }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Entry', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid entry selected.
@endif
