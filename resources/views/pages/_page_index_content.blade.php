{!! $pages->render() !!}

<div id="pageGridView" class="hide">
    <div class="row">
        @foreach($pages as $page)
            {!! ($loop->remaining+1) == ($loop->count%3) ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header text-center">
                        @if($page->thumbnailUrl)
                            <a href="{{ $page->url }}"><img src="{{ $page->thumbnailUrl }}" class="mw-100 mb-1" /></a>
                        @endif
                        <h3>
                            {!! $page->displayName !!}
                        </h3>
                        {!! $page->category->subject['key'] == 'time' && isset($page->data['date']['start']) ? $dateHelper->formatTimeDate($page->data['date']['start']).(isset($page->data['date']['start']) && isset($page->data['date']['end']) ? '-' : '' ).(isset($page->data['date']['end']) ? $dateHelper->formatTimeDate($page->data['date']['end']) : '') : '' !!}{{ ((isset($page->data['date']['start']) || isset($page->data['date']['end'])) && ($page->parent && (isset($page->parent->is_visible) ? ($page->parent->is_visible || (Auth::check() && Auth::user()->canWrite)) : 1))) ? ' ・ ' : '' }}{!! $page->parent && (isset($page->parent->is_visible) ? ($page->parent->is_visible || (Auth::check() && Auth::user()->canWrite)) : 1) ? $page->parent->displayName : '' !!}
                    </div>
                    @if(isset($page->summary) && $page->summary)
                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item">
                                {{ $page->summary }}
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
            {!! $loop->count%3 != 0 && $loop->last ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            {!! $loop->iteration % 3 == 0 ? '<div class="w-100"></div>' : '' !!}
            @php if($loop->last) unset($page); @endphp
        @endforeach
    </div>
</div>
<div id="pageListView" class="hide">
    <div class="row">
        @foreach($pages->chunkWhile(function ($value, $key, $chunk) {return substr($value->title, 0, 1) === substr($chunk->first()->title, 0, 1);}) as $chunk)
            {!! $loop->first || $loop->iteration == 3 ? '<div class="col-md-3">' : '' !!}
                <h4>{{ ucfirst(substr($chunk->first()->title, 0, 1)) }}</h4>
                <ul>
                    @foreach($chunk as $page)
                        <li>{!! $page->displayName !!}</li>
                    @endforeach
                </ul>
            {!! $loop->last || $loop->iteration == 2 ? '</div>' : '' !!}
            @php if($loop->last) unset($page); @endphp
        @endforeach
    </div>
</div>

{!! $pages->render() !!}

<div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }} found.</div>
