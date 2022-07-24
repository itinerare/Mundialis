@if ($relationship)
    {!! Form::open(['url' => 'pages/' . $page->id . '/relationships/delete/' . $relationship->id]) !!}

    <p>You are about to delete the relationship between {!! $relationship->pageOne->displayName !!} and {!! $relationship->pageTwo->displayName !!}. This is not
        reversible.</p>

    <p>Are you sure you want to delete this relationship?</p>

    <div class="text-right">
        {!! Form::submit('Delete Relationship', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid relationship selected.
@endif
