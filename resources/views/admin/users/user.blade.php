@extends('admin.layout')

@section('admin-title') User Index @stop

@section('admin-content')
    {!! breadcrumbs([
        'Admin Panel' => 'admin',
        'User Index' => 'admin/users',
        $user->name => 'admin/users/' . $user->name . '/edit',
    ]) !!}

    <h1>User: {!! $user->displayName !!}</h1>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{ $user->adminUrl }}">Account</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/users/' . $user->name . '/updates') }}">Account Updates</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/users/' . $user->name . '/ban') }}">Ban</a>
        </li>
    </ul>

    <h3>Basic Info</h3>
    {!! Form::open(['url' => 'admin/users/' . $user->name . '/basic']) !!}
    <div class="form-group row">
        <label class="col-md-2 col-form-label">Username</label>
        <div class="col-md-10">
            {!! Form::text('name', $user->name, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 col-form-label">Rank
            @if ($user->isAdmin)
                {!! add_help('The rank of the admin user cannot be edited.') !!}
            @endif
        </label>
        <div class="col-md-10">
            @if (!$user->isAdmin)
                {!! Form::select('rank_id', $ranks, $user->rank_id, ['class' => 'form-control']) !!}
            @else
                {!! Form::text('rank_id', $ranks[$user->rank_id], ['class' => 'form-control', 'disabled']) !!}
            @endif
        </div>
    </div>
    <div class="text-right">
        {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}

    <h3>Account</h3>

    {!! Form::open(['url' => 'admin/users/' . $user->name . '/account']) !!}
    <div class="form-group row">
        <label class="col-md-2 col-form-label">Email Address</label>
        <div class="col-md-10">
            {!! Form::text('email', $user->email, ['class' => 'form-control', 'disabled']) !!}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-2 col-form-label">Join Date</label>
        <div class="col-md-10">
            {!! Form::text('created_at', format_date($user->created_at, false), ['class' => 'form-control', 'disabled']) !!}
        </div>
    </div>
    <div class="text-right">
        {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}

@endsection
