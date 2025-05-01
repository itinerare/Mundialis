@extends('layouts.app')

@section('title')
    Login: Two-Factor Auth
@endsection

@section('content')
    <div class="text-center">
        <h1>Two-Factor Authentication</h1>
    </div>

    {!! Form::open(['action' => '/two-factor-challenge']) !!}
    <div class="form-group row mb-2">
        {!! Form::label('code', 'Code', null, ['class' => 'col-md-3 col-form-label text-md-right']) !!}
        <div class="col-md-7">
            {!! Form::text('code', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group row mb-2">
        {!! Form::label('use_recovery', 'Use a Recovery Code', ['class' => 'form-label text-md-right col-md-6']) !!}
        <div class="col-md-6">
            {!! Form::checkbox('use_recovery', 1, old('use_recovery'), [
                'class' => 'form-check-input',
                'data-toggle' => 'toggle',
                'data-on' => 'Yes',
                'data-off' => 'No',
                'id' => 'useRecovery',
            ]) !!}
        </div>
    </div>
    <div class="mb-3" id="recoveryContainer">
        <div class="form-group row mb-3">
            {!! Form::label('Recovery Code', null, ['class' => 'col-md-3 col-form-label text-md-right']) !!}
            <div class="col-md-7">
                {!! Form::text('recovery_code', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="text-right">
        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@endsection

@section('scripts')
    @parent

    <script>
        $(document).ready(function() {
            var $useRecovery = $('#useRecovery');
            var $recoveryContainer = $('#recoveryContainer');

            var useRecovery = $useRecovery.is(':checked');

            updateOptions();

            $useRecovery.on('change', function(e) {
                useRecovery = $useRecovery.is(':checked');

                updateOptions();
            });

            function updateOptions() {
                if (useRecovery) $recoveryContainer.removeClass('hide');
                else $recoveryContainer.addClass('hide');
            }
        });
    </script>
@endsection
