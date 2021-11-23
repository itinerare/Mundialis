<ul>
    <li class="sidebar-header"><a href="{{ url('user/'.Auth::user()->name) }}" class="card-link">Your Account</a></li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">Account & Settings</div>
        <div class="sidebar-item"><a href="{{ url('account/settings') }}" class="{{ set_active('account/settings') }}">Settings</a></div>
        <div class="sidebar-item"><a href="{{ url('notifications') }}" class="{{ set_active('notifications') }}">Notifications</a></div>
    </li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">Watched Pages</div>
        <div class="sidebar-item"><a href="{{ url('account/watched-pages') }}" class="{{ set_active('account/watched-pages') }}">Watched Pages</a></div>
    </li>
</ul>
