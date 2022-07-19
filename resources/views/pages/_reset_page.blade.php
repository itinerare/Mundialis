@if ($page)
    {!! Form::open(['url' => 'pages/' . $page->id . '/history/' . $version->id . '/reset']) !!}

    <p>You are about to reset the page <strong>{{ $page->title }}</strong> to version #{{ $version->id }}
        ({!! format_date($version->created_at) !!}). Please provide a reason why you are resetting the page to this version.</p>

    <div class="form-group">
        {!! Form::label('Reason (Optional)') !!} {!! add_help(
            'A short summary of why you are deleting the page. Optional, but recommended for recordkeeping and communication purposes.',
        ) !!}
        {!! Form::text('reason', null, ['class' => 'form-control']) !!}
    </div>

    <p>Are you sure you want to reset <strong>{{ $page->title }}</strong> to this version?</p>

    <div class="text-right">
        {!! Form::submit('Reset Page', ['class' => 'btn btn-warning']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid page selected.
@endif
