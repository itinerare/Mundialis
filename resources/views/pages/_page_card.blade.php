<div class="card h-100">
    <div class="card-header text-center">
        @if ($page->image)
            <a href="{{ $page->url }}"><img src="{{ Storage::url($page->image->thumbnailUrl) }}" class="mw-100 mb-1" /></a>
        @endif
        <h3>
            {!! $page->displayName !!}
        </h3>
        @if (isset($category))
            {!! $page->category->subject['key'] == 'time' && isset($page->data['date']['start'])
                ? $dateHelper->formatTimeDate($page->data['date']['start']) .
                    (isset($page->data['date']['start']) && isset($page->data['date']['end']) ? '-' : '') .
                    (isset($page->data['date']['end']) ? $dateHelper->formatTimeDate($page->data['date']['end']) : '')
                : '' !!}{{ (isset($page->data['date']['start']) || isset($page->data['date']['end'])) && ($page->parent && (isset($page->parent->is_visible) ? $page->parent->is_visible || (Auth::check() && Auth::user()->canWrite) : 1)) ? ' ・ ' : '' }}{!! $page->parent &&
            (isset($page->parent->is_visible) ? $page->parent->is_visible || (Auth::check() && Auth::user()->canWrite) : 1)
                ? $page->parent->displayName
                : '' !!}
        @else
            {!! $page->category->subject['term'] . ' ・ ' . $page->category->displayName !!}{!! $page->category->subject['key'] == 'time' && isset($page->data['date']['start'])
                ? ' ・ ' .
                    $dateHelper->formatTimeDate($page->data['date']['start']) .
                    (isset($page->data['date']['start']) && isset($page->data['date']['end']) ? '-' : '') .
                    (isset($page->data['date']['end']) ? $dateHelper->formatTimeDate($page->data['date']['end']) : '')
                : '' !!}{!! $page->parent &&
            (isset($page->parent->is_visible) ? $page->parent->is_visible || (Auth::check() && Auth::user()->canWrite) : 1)
                ? ' ・ ' . $page->parent->displayName
                : '' !!}
        @endif
    </div>
    @if ($page->summary || $page->tags->count())
        <ul class="list-group list-group-flush text-center">
            @if ($page->summary)
                <li class="list-group-item">
                    {{ $page->summary }}
                </li>
            @endif
            @if ($page->tags->count())
                <li class="list-group-item">
                    <strong>Tags:</strong>
                    @foreach ($page->tags as $tag)
                        {!! $tag->displayName !!}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                </li>
            @endif
        </ul>
    @endif
</div>
