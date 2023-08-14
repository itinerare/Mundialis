@extends('account.layout')

@section('account-title')
    Settings
@endsection

@section('account-content')
    {!! breadcrumbs(['My Account' => Auth::user()->url, 'Settings' => 'account/settings']) !!}

    <h1>Settings</h1>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                @if (Auth::user()->avatar != 'default.jpg')
                    <div class="col-md-2 align-self-center">
                        <img class="img-thumbnail mw-100" src="{{ asset('images/avatars/' . Auth::user()->avatar) }}" />
                    </div>
                @endif
                <div class="col-md">
                    <h3>Avatar</h3>
                    <div class="text-left">
                        <div class="alert alert-warning">Please note a hard refresh may be required to see your updated
                            avatar.</div>
                    </div>
                    <form enctype="multipart/form-data" action="avatar" method="POST">
                        <label>Update Profile Image</label><br>
                        <input type="file" name="avatar">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="pull-right btn btn-sm btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h3>Profile</h3>

            {!! Form::open(['url' => 'account/profile']) !!}
            <div class="form-group">
                {!! Form::label('text', 'Profile Text') !!}
                {!! Form::textarea('profile_text', Auth::user()->profile_text, ['class' => 'form-control wysiwyg']) !!}
            </div>
            <div class="text-right">
                {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h3>Email Address</h3>

            {!! Form::open(['url' => 'account/email']) !!}
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Email Address</label>
                <div class="col-md-10">
                    {!! Form::text('email', Auth::user()->email, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="text-right">
                {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h3>Change Password</h3>

            {!! Form::open(['url' => 'account/password']) !!}
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Old Password</label>
                <div class="col-md-10">
                    {!! Form::password('old_password', ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label">New Password</label>
                <div class="col-md-10">
                    {!! Form::password('new_password', ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Confirm New Password</label>
                <div class="col-md-10">
                    {!! Form::password('new_password_confirmation', ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="text-right">
                {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h3>Two-Factor Authentication</h3>

            <p>Two-factor authentication acts as a second layer of protection for your account. It uses an app on your
                phone-- such as Google Authenticator-- and information provided by the site to generate a random code that
                changes frequently.</p>

            @if (!isset(Auth::user()->two_factor_secret))
                <p>In order to enable two-factor authentication, you will need to scan a QR code with an authenticator app
                    on your phone. Two-factor authentication will not be enabled until you do so and confirm by entering one
                    of the codes provided by your authentication app.</p>
                {!! Form::open(['url' => 'account/two-factor/enable']) !!}
                <div class="text-right">
                    {!! Form::submit('Enable', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            @elseif(isset(Auth::user()->two_factor_secret))
                <p>Two-factor authentication is currently enabled.</p>

                <h4>Disable Two-Factor Authentication</h4>
                <p>To disable two-factor authentication, you must enter a code from your authenticator app.</p>
                {!! Form::open(['url' => 'account/two-factor/disable']) !!}
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Code</label>
                    <div class="col-md-10">
                        {!! Form::text('code', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="text-right">
                    {!! Form::submit('Disable', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            @endif
        </div>
    </div>
@endsection
