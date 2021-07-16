@if($page)
    {!! Form::open(['url' => 'pages/delete/'.$page->id]) !!}

    <p>You are about to delete the page <strong>{{ $page->title }}</strong>. This is not reversible.</p>
    <p>Are you sure you want to delete <strong>{{ $page->title }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Page', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid page selected.
@endif
