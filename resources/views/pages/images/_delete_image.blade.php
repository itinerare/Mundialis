@if ($image)
    {!! Form::open(['action' => '/pages/' . $page->id . '/gallery/delete/' . $image->id]) !!}

    <p>You are about to delete image <strong>#{{ $image->id }}</strong>. Only admins are able to restore deleted
        images. Please provide a reason why you are deleting the image.</p>

    <div class="form-group">
        {!! Form::label('reason', 'Reason (Optional)') !!} {!! add_help(
            'A short summary of why you are deleting the image. Optional, but recommended for recordkeeping and communication purposes.',
        ) !!}
        {!! Form::text('reason', null, ['class' => 'form-control']) !!}
    </div>

    <p>Are you sure you want to delete <strong>#{{ $image->id }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Image', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid image selected.
@endif
