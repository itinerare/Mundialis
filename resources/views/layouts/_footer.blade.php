<nav class="navbar navbar-expand-md navbar-light">
    <ul class="navbar-nav ml-auto mr-auto">
        <li class="nav-item"><a href="{{ url('info/terms') }}" class="nav-link">Terms</a></li>
        <li class="nav-item"><a href="{{ url('info/privacy') }}" class="nav-link">Privacy</a></li>
        <li class="nav-item"><a href="mailto:{{ env('CONTACT_ADDRESS') }}" class="nav-link">Contact</a></li>
        <li class="nav-item"><a href="https://code.itinerare.net/itinerare/mundialis" class="nav-link">Mundialis
                v{{ config('mundialis.settings.version') }}</a></li>
    </ul>
</nav>
<div class="copyright">&copy; {{ config('mundialis.settings.site_name', 'Mundialis') }}
    {{ Carbon\Carbon::now()->year }}</div>
