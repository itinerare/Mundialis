<ul>
    <li class="sidebar-header"><a href="{{ url('admin') }}" class="card-link">Admin Home</a></li>

    <li class="sidebar-section">
        <div class="sidebar-section-header">Subjects</div>
        <div class="sidebar-item"><a href="{{ url('admin/data/people') }}" class="{{ set_active('admin/people*') }}">People</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/data/places') }}" class="{{ set_active('admin/places*') }}">Places</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/data/species') }}" class="{{ set_active('admin/species*') }}">Flora & Fauna</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/data/things') }}" class="{{ set_active('admin/things*') }}">Things</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/cdata/oncepts') }}" class="{{ set_active('admin/concepts*') }}">Concepts</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/data/time') }}" class="{{ set_active('admin/time*') }}">Time & Events</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/data/misc') }}" class="{{ set_active('admin/misc*') }}">Misc</a></div>
    </li>

    <li class="sidebar-section">
        <div class="sidebar-section-header">Maintenance</div>
        <div class="sidebar-item"><a href="{{ url('admin/users') }}" class="{{ set_active('admin/users*') }}">User Index</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/changes') }}" class="{{ set_active('admin/changes*') }}">Recent Changes</a></div>
    </li>

    <li class="sidebar-section">
        <div class="sidebar-section-header">Site Settings</div>
        <div class="sidebar-item"><a href="{{ url('admin/ranks') }}" class="{{ set_active('admin/ranks*') }}">User Ranks</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/pages') }}" class="{{ set_active('admin/pages*') }}">Site Pages</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/site-settings') }}" class="{{ set_active('admin/site-settings*') }}">Site Settings</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/site-images') }}" class="{{ set_active('admin/site-images*') }}">Site Images</a></div>
    </li>

</ul>
