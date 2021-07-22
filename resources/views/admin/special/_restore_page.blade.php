@if($page)
    {!! Form::open(['url' => 'admin/special/deleted-pages/'.$page->id.'/restore']) !!}

    <p>You are about to restore the page <strong>{{ $page->title }}</strong>. Are you sure you want to restore <strong>{{ $page->title }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Restore Page', ['class' => 'btn btn-success']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid page selected.
@endif
