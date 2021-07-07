<ul>
    <li class="sidebar-header"><a href="{{ url('admin') }}" class="card-link">Admin Home</a></li>

    <li class="sidebar-section">
        <div class="sidebar-section-header">Subjects</div>
        @foreach(Config::get('mundialis.subjects') as $subject=>$values)
            <div class="sidebar-item"><a href="{{ url('admin/data/'.$subject) }}" class="{{ set_active('admin/'.$subject.'*') }}">{{ isset($values['name']) ? $values['name'] : ucfirst($subject) }}</a></div>
        @endforeach
    </li>

    <li class="sidebar-section">
        <div class="sidebar-section-header">Maintenance</div>
        <div class="sidebar-item"><a href="{{ url('admin/changes') }}" class="{{ set_active('admin/changes*') }}">Recent Changes</a></div>
    </li>

    <li class="sidebar-section">
        <div class="sidebar-section-header">Users</div>
        <div class="sidebar-item"><a href="{{ url('admin/users') }}" class="{{ set_active('admin/users') }}">User Index</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/users/invitations') }}" class="{{ set_active('admin/users/invitations*') }}">Invitation Codes</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/users/ranks') }}" class="{{ set_active('admin/users/ranks*') }}">User Ranks</a></div>
    </li>

    <li class="sidebar-section">
        <div class="sidebar-section-header">Site Settings</div>
        <div class="sidebar-item"><a href="{{ url('admin/pages') }}" class="{{ set_active('admin/pages*') }}">Site Pages</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/site-settings') }}" class="{{ set_active('admin/site-settings*') }}">Site Settings</a></div>
        <div class="sidebar-item"><a href="{{ url('admin/site-images') }}" class="{{ set_active('admin/site-images*') }}">Site Images</a></div>
    </li>

</ul>
