@extends('admin.layout')

@section('admin-title')
    Site Settings
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Site Settings' => 'admin/settings']) !!}

    <h1>Site Settings</h1>

    <p>This is a list of settings that can be quickly modified to alter the site behaviour. Please make sure that the values
        correspond to the possible options as stated in the descriptions! Incorrect values can cause the site to stop
        working.</p>

    @if (!count($settings))
        <p>No settings found.</p>
    @else
        <!-- Site Settings -->
        <h2>General Settings</h2>
        <div class="row">
            <div class="col-md-6 mb-2">
                {!! Form::open(['url' => 'admin/site-settings/visitors_can_read']) !!}
                <div class="form-group h-100">
                    {!! Form::checkbox('value', 1, $settings->where('key', 'visitors_can_read')->first()->value, [
                        'class' => 'form-check-input mb-3',
                        'data-toggle' => 'toggle',
                    ]) !!}
                    <strong>{!! Form::label('Visitors can Read') !!}:</strong>
                    {{ $settings->where('key', 'visitors_can_read')->first()->description }}<br />
                    <div class="form-group text-right mb-3">
                        {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="col-md-6 mb-2">
                {!! Form::open(['url' => 'admin/site-settings/is_registration_open']) !!}
                <div class="form-group h-100">
                    {!! Form::checkbox('value', 1, $settings->where('key', 'is_registration_open')->first()->value, [
                        'class' => 'form-check-input mb-3',
                        'data-toggle' => 'toggle',
                    ]) !!}
                    <strong>{!! Form::label('Is Registration Open') !!}:</strong>
                    {{ $settings->where('key', 'is_registration_open')->first()->description }}
                </div>
                <div class="form-group text-right mb-3">
                    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection
