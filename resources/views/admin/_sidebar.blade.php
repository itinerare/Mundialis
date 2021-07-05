<ul>
    <li class="sidebar-header"><a href="{{ url('admin') }}" class="card-link">Admin Home</a></li>

    <li class="sidebar-section">
        <div class="sidebar-section-header">Maintenance</div>
        <div class="sidebar-item"><a href="{{ url('admin/pages') }}" class="{{ set_active('admin/pages*') }}">Site Pages</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/site-settings') }}" class="{{ set_active('admin/site-settings*') }}">Site Settings</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/site-images') }}" class="{{ set_active('admin/site-images*') }}">Site Images</a></div>
    </li>

</ul>
