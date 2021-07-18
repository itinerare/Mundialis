<ul>
    @if(isset($page) && $page->id)
        <li class="sidebar-header"><a href="{{ url('pages/'.$page->category->subject['key']) }}" class="card-link">{{ $page->category->subject['name'] }}</a></li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">{{ $page->title }}</div>
            <div class="sidebar-item"><a href="{{ $page->url }}" class="{{ set_active('pages/view/'.$page->id.'.'.$page->slug) }}">Read</a></div>
            @if(Auth::check() && Auth::user()->canWrite)
                <div class="sidebar-item"><a href="{{ url('pages/edit/'.$page->id) }}" class="{{ set_active('pages/edit/'.$page->id) }}">Edit</a></div>
            @endif
        </li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">Page Tools</div>
        </li>
    @else
        <li class="sidebar-header"><a href="{{ url('pages') }}" class="card-link">Pages</a></li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">Subjects</div>
            @foreach(Config::get('mundialis.subjects') as $subject=>$values)
                <div class="sidebar-item"><a href="{{ url('pages/'.$subject) }}" class="{{ set_active('pages/'.$subject.'*') }}">{{ isset($values['name']) ? $values['name'] : ucfirst($subject) }}</a></div>
            @endforeach
        </li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">Special Pages</div>
            <div class="sidebar-item"><a href="{{ url('special/all-pages') }}" class="{{ set_active('special/all-pages') }}">All Pages</a></div>
        </li>
    @endif
</ul>
