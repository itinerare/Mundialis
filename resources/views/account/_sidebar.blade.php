<ul>
    <li class="sidebar-header"><a href="{{ url('user/'.Auth::user()->name) }}" class="card-link">Your Account</a></li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">Account & Settings</div>
        <div class="sidebar-item"><a href="{{ url('account/settings') }}" class="{{ set_active('account/settings') }}">Settings</a></div>
    </li>
</ul>