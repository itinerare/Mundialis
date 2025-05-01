@if ($user->is_banned)
    <p>This will unban the user, allowing them to use the site features again. Are you sure you want to do this?</p>
    {!! Form::open(['action' => '/admin/users/' . $user->name . '/unban']) !!}
    <div class="text-right">
        {!! Form::submit('Unban', ['class' => 'btn btn-danger']) !!}
    </div>
    {!! Form::close() !!}
@else
    <p>This user is not banned.</p>
@endif
