@if($image)
    {!! Form::open(['url' => 'pages/'.$page->id.'/gallery/delete/'.$image->id]) !!}

    <p>You are about to delete image <strong>#{{ $image->id }}</strong>. This is not reversible.</p>
    <p>Are you sure you want to delete <strong>#{{ $image->id }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Image', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid image selected.
@endif
