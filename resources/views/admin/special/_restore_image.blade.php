@if ($image)
    {!! Form::open(['action' => '/admin/special/deleted-images/' . $image->id . '/restore']) !!}

    <p>You are about to restore image <strong>#{{ $image->id }}</strong>. Please provide a reason why you are
        restoring the image.</p>

    <div class="form-group">
        {!! Form::label('reason', 'Reason (Optional)') !!} {!! add_help(
            'A short summary of why you are restoring the image. Optional, but recommended for recordkeeping and communication purposes.',
        ) !!}
        {!! Form::text('reason', null, ['class' => 'form-control']) !!}
    </div>

    <p>Are you sure you want to restore image <strong>#{{ $image->id }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Restore Image', ['class' => 'btn btn-success']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid image selected.
@endif
