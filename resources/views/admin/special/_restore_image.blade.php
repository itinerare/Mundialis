@if($image)
    {!! Form::open(['url' => 'admin/special/deleted-images/'.$image->id.'/restore']) !!}

    <p>You are about to restore image <strong>#{{ $image->id }}</strong>. Are you sure you want to restore image <strong>#{{ $image->id }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Restore Image', ['class' => 'btn btn-success']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid image selected.
@endif
