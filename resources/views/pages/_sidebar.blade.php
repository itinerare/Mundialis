<ul>
    @if(isset($page) && $page->id)
        <li class="sidebar-header"><a href="{{ url($page->category->subject['key']) }}" class="card-link">{{ $page->category->subject['name'] }}/{!! $page->category->displayName !!}</a></li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">{{ $page->title }}</div>
            <div class="sidebar-item"><a href="{{ $page->url }}" class="{{ set_active('pages/'.$page->id.'.'.$page->slug) }}">Read Page</a></div>
            <div class="sidebar-item"><a href="{{ url('pages/'.$page->id.'/history') }}" class="{{ set_active('pages/'.$page->id.'/history*') }}">History</a></div>
            <div class="sidebar-item"><a href="{{ url('pages/'.$page->id.'/gallery') }}" class="{{ set_active('pages/'.$page->id.'/gallery*') }}">Gallery</a></div>
        </li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">Page Tools</div>
            @if(Auth::check() && Auth::user()->canWrite)
                <div class="sidebar-item"><a href="{{ url('pages/'.$page->id.'/edit') }}" class="{{ set_active('pages/'.$page->id.'/edit') }}">Edit Page</a></div>
                <div class="sidebar-item"><a href="{{ url('pages/'.$page->id.'/gallery/create') }}" class="{{ set_active('pages/'.$page->id.'/gallery/create') }}">Upload Image</a></div>
            @endif
        </li>

    @else
        <li class="sidebar-header"><a href="{{ url('/') }}" class="card-link">Pages</a></li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">Subjects</div>
            @foreach(Config::get('mundialis.subjects') as $subject=>$values)
                <div class="sidebar-item"><a href="{{ url($subject) }}" class="{{ set_active($subject.'*') }}">{{ isset($values['name']) ? $values['name'] : ucfirst($subject) }}</a></div>
            @endforeach
        </li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">Special Pages</div>
            <div class="sidebar-item"><a href="{{ url('special') }}" class="{{ set_active('special') }}">All Special Pages</a></div>
            <div class="sidebar-item"><a href="{{ url('special/all-pages') }}" class="{{ set_active('special/all-pages') }}">All Pages</a></div>
            <div class="sidebar-item"><a href="{{ url('special/random-page') }}">Random Page</a></div>
            @if(Auth::check() && Auth::user()->canWrite)
                @foreach(Config::get('mundialis.utility_tags') as $key=>$tag)
                    <div class="sidebar-item"><a href="{{ url('special/'.$key.'-pages') }}" class="{{ set_active('special/'.$key.'-pages') }}">{{ $tag['name'] }}</a></div>
                @endforeach
                <div class="sidebar-item"><a href="{{ url('special/wanted-pages') }}" class="{{ set_active('special/wanted-pages') }}">Wanted Pages</a></div>
            @endif
        </li>
    @endif
</ul>
