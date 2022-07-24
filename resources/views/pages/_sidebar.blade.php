<ul>
    @if (isset($page) && $page->id)
        <li class="sidebar-header"><a href="{{ url($page->category->subject['key']) }}"
                class="card-link">{{ $page->category->subject['name'] }}/{!! $page->category->displayName !!}</a></li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">{{ $page->title }}</div>
            <div class="sidebar-item"><a href="{{ $page->url }}"
                    class="{{ set_active('pages/' . $page->id . '.' . $page->slug) }}">Read Page</a></div>
            <div class="sidebar-item"><a href="{{ url('pages/' . $page->id . '/history') }}"
                    class="{{ set_active('pages/' . $page->id . '/history*') }}">History</a></div>
            <div class="sidebar-item"><a href="{{ url('pages/' . $page->id . '/gallery') }}"
                    class="{{ set_active('pages/' . $page->id . '/gallery*') }}">Gallery</a></div>
            @if ($page->category->subject['key'] == 'people')
                <div class="sidebar-item"><a href="{{ url('pages/' . $page->id . '/relationships') }}"
                        class="{{ set_active('pages/' . $page->id . '/relationships*') }}">Relationships</a></div>
            @endif
        </li>

        @if (Auth::check() && Auth::user()->canEdit($page))
            <li class="sidebar-section">
                <div class="sidebar-section-header">Page Tools</div>
                <div class="sidebar-item"><a href="{{ url('pages/' . $page->id . '/edit') }}"
                        class="{{ set_active('pages/' . $page->id . '/edit') }}">Edit Page</a></div>
                <div class="sidebar-item"><a href="{{ url('pages/' . $page->id . '/gallery/create') }}"
                        class="{{ set_active('pages/' . $page->id . '/gallery/create') }}">Upload Image</a></div>
                @if (Auth::user()->isAdmin)
                    <div class="sidebar-item"><a href="{{ url('pages/' . $page->id . '/protect') }}"
                            class="{{ set_active('pages/' . $page->id . '/protect') }}">{{ $page->protection ? 'Edit Page Protection' : 'Protect Page' }}</a>
                    </div>
                @endif
                <div class="sidebar-item"><a href="{{ url('pages/' . $page->id . '/move') }}"
                        class="{{ set_active('pages/' . $page->id . '/move') }}">Move Page</a></div>
            </li>
        @endif

        <li class="sidebar-section">
            <div class="sidebar-section-header">More</div>
            <div class="sidebar-item"><a href="{{ url('pages/' . $page->id . '/links-here') }}"
                    class="{{ set_active('pages/' . $page->id . '/links-here*') }}">What Links Here</a></div>
            @if (Auth::check())
                {!! Form::open(['url' => 'account/watched-pages/' . $page->id, 'id' => 'watchForm']) !!}
                <div class="sidebar-item"><a href="#"
                        onclick="document.getElementById('watchForm').submit();">{{ Auth::user()->watched->where('id', $page->id)->first()? 'Unw': 'W' }}atch
                        Page</a></div>
                {!! Form::close() !!}
            @endif
        </li>
    @else
        <li class="sidebar-header"><a href="{{ url('/') }}" class="card-link">Pages</a></li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">Subjects</div>
            @foreach (Config::get('mundialis.subjects') as $subject => $values)
                <div class="sidebar-item"><a href="{{ url($subject) }}"
                        class="{{ set_active($subject . '*') }}">{{ isset($values['name']) ? $values['name'] : ucfirst($subject) }}</a>
                </div>
            @endforeach
        </li>

        <li class="sidebar-section">
            <div class="sidebar-section-header">Special Pages</div>
            <div class="sidebar-item"><a href="{{ url('special') }}" class="{{ set_active('special*') }}">All
                    Special Pages</a></div>
            <div class="sidebar-item"><a href="{{ url('special/all-pages') }}"
                    class="{{ set_active('special/all-pages') }}">All Pages</a></div>
            <div class="sidebar-item"><a href="{{ url('special/random-page') }}">Random Page</a></div>
            @if (Auth::check() && Auth::user()->canWrite)
                @foreach (Config::get('mundialis.utility_tags') as $key => $tag)
                    <div class="sidebar-item"><a href="{{ url('special/' . $key . '-pages') }}"
                            class="{{ set_active('special/' . $key . '-pages') }}">{{ $tag['name'] }}</a></div>
                @endforeach
                <div class="sidebar-item"><a href="{{ url('special/wanted-pages') }}"
                        class="{{ set_active('special/wanted-pages') }}">Wanted Pages</a></div>
            @endif
        </li>
    @endif
</ul>
