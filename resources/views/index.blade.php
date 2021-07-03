@extends('layouts.app')

@section('title') Home @endsection

@section('content')
    @if(Settings::get('viewers_can_read') || Auth::check())
        {!! $page ? $page->text : 'Please finish set up!' !!}
    @else
        <p>This site is only visible to logged-in users!</p>
    @endif
@endsection
