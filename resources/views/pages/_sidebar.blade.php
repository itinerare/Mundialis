<ul>
    <li class="sidebar-header"><a href="{{ url('pages') }}" class="card-link">Pages</a></li>

    <li class="sidebar-section">
        <div class="sidebar-section-header">Subjects</div>
        @foreach(Config::get('mundialis.subjects') as $subject=>$values)
            <div class="sidebar-item"><a href="{{ url('pages/'.$subject) }}" class="{{ set_active('pages/'.$subject.'*') }}">{{ isset($values['name']) ? $values['name'] : ucfirst($subject) }}</a></div>
        @endforeach
    </li>

</ul>
