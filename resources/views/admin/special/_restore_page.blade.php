@if ($page)
    {!! Form::open(['url' => 'admin/special/deleted-pages/' . $page->id . '/restore']) !!}

    <p>You are about to restore the page <strong>{{ $page->title }}</strong>. Please provide a reason why you are
        restoring the page.</p>

    <div class="form-group">
        {!! Form::label('Reason (Optional)') !!} {!! add_help(
            'A short summary of why you are restoring the page. Optional, but recommended for recordkeeping and communication purposes.',
        ) !!}
        {!! Form::text('reason', null, ['class' => 'form-control']) !!}
    </div>

    <p>Are you sure you want to restore <strong>{{ $page->title }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Restore Page', ['class' => 'btn btn-success']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid page selected.
@endif
