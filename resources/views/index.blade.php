@extends('layouts.app')

@section('title') Home @endsection

@section('sidebar')
    @include('pages._sidebar', ['page' => null])
@endsection

@section('content')
    @if(Settings::get('visitors_can_read') || Auth::check())
        <div class="row">
            <div class="col-md">
                <div class="mb-4">
                    {!! $page ? $page->text : 'Please finish set up!' !!}
                </div>

                <p>
                    Select a subject from the side- or navigation bar to browse!
                    @if(Auth::user() && Auth::user()->canWrite)
                        Or consider contributing by making a <a href="{{ url('special/wanted-pages') }}">wanted page</a> or working on pages flagged for maintenance.
                    @endif
                </p>

                @include('widgets._recent_pages')
            </div>
            <div class="col-md-5">
                @include('widgets._recent_images')
            </div>
        </div>
    @else
        <p class="text-center">This site is only visible to logged-in users!</p>
    @endif
@endsection

@section('scripts')
@parent

@if(Settings::get('visitors_can_read') || Auth::check())
    @include('pages.images._info_popup_js')
@endif
@endsection
