@if ($page)
    {!! Form::open(['url' => 'pages/' . $page->id . '/delete']) !!}

    <p>You are about to delete the page <strong>{{ $page->title }}</strong>. While this is not permanent, keep in mind
        that only admins are able to restore deleted pages. Please provide a reason why you are deleting the page.</p>

    <div class="form-group">
        {!! Form::label('Reason (Optional)') !!} {!! add_help(
            'A short summary of why you are deleting the page. Optional, but recommended for recordkeeping and communication purposes.',
        ) !!}
        {!! Form::text('reason', null, ['class' => 'form-control']) !!}
    </div>

    <p>Are you sure you want to delete <strong>{{ $page->title }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Page', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid page selected.
@endif
