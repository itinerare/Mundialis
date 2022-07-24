@extends('layouts.app')

@section('title')
    Banned
@endsection

@section('content')
    {!! breadcrumbs(['Banned' => 'banned']) !!}

    <h1>Banned</h1>

    <p>You are banned from site functions effective {!! format_date(Auth::user()->banned_at) !!}.
        {{ Auth::user()->ban_reason ? 'The following reason was given:' : '' }}</p>

    @if (Auth::user()->ban_reason)
        <div class="alert alert-danger">
            {!! nl2br(htmlentities(Auth::user()->ban_reason)) !!}
        </div>
    @endif

    <p>As such, you may not continue to to use site features.</p>
    <p>Contact an admin if you feel this decision has been made in error, but please respect their final judgement on the
        matter.</p>
@endsection
