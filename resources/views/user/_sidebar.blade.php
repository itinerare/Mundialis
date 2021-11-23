<ul>
    <li class="sidebar-header"><a href="{{ $user->url }}" class="card-link">{{ Illuminate\Support\Str::limit($user->name, 10, $end='...') }}</a></li>
    <li class="sidebar-section">
        <div class="sidebar-section-header">Revision Logs</div>
        <div class="sidebar-item"><a href="{{ $user->url.'/page-revisions' }}" class="{{ set_active('user/'.$user->name.'/page-revisions*') }}">Page Revisions</a></div>
        <div class="sidebar-item"><a href="{{ $user->url.'/image-revisions' }}" class="{{ set_active('user/'.$user->name.'/image-revisions*') }}">Image Revisions</a></div>
    </li>

    @if(Auth::check() && Auth::user()->isAdmin)
        <li class="sidebar-section">
            <div class="sidebar-section-header">Admin</div>
            <div class="sidebar-item"><a href="{{ $user->adminUrl }}">Edit User</a></div>
        </li>
    @endif
</ul>
