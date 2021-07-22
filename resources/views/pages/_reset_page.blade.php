@if($page)
    {!! Form::open(['url' => 'pages/'.$page->id.'/history/'.$version->id.'/reset']) !!}

    <p>You are about to reset the page <strong>{{ $page->title }}</strong> to version #{{ $version->id }} ({!! format_date($version->created_at) !!}). Are you sure you want to reset <strong>{{ $page->title }}</strong> to this version?</p>

    <div class="text-right">
        {!! Form::submit('Reset Page', ['class' => 'btn btn-warning']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid page selected.
@endif
