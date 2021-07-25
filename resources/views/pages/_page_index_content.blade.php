{!! $pages->render() !!}

<div>
    {!! Form::open(['method' => 'GET', 'class' => '']) !!}
        <div class="form-inline justify-content-end">
            <div class="form-group mb-3">
                {!! Form::text('title', Request::get('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
            </div>
            @if(!isset($category))
                <div class="form-group ml-3 mb-3">
                    {!! Form::select('category_id', $categoryOptions, Request::get('category_id'), ['class' => 'form-control', 'placeholder' => 'Select a Category']) !!}
                </div>
            @endif
        </div>
        <div class="form-inline justify-content-end">
            <div class="form-group mr-3 mb-3">
                {!! Form::select('sort', [
                    'newest'         => 'Newest First',
                    'oldest'         => 'Oldest First',
                    'alpha'          => 'Sort Alphabetically (A-Z)',
                    'alpha-reverse'  => 'Sort Alphabetically (Z-A)',
                ], Request::get('sort') ? : 'alpha', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group mb-3">
                {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}
</div>

<div class="text-right mb-3">
    <div class="btn-group">
        <button type="button" class="btn btn-secondary active page-grid-view-button" data-toggle="tooltip" title="Grid View" alt="Grid View"><i class="fas fa-th"></i></button>
        <button type="button" class="btn btn-secondary page-list-view-button" data-toggle="tooltip" title="List View" alt="List View"><i class="fas fa-bars"></i></button>
    </div>
</div>

<div id="pageGridView" class="hide">
    <div class="row">
        @foreach($pages as $page)
            {!! ($loop->remaining+1) == ($loop->count%3) ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header text-center">
                        @if($page->image)
                            <a href="{{ $page->url }}"><img src="{{ $page->image->thumbnailUrl }}" class="mw-100 mb-1" /></a>
                        @endif
                        <h3>
                            {!! $page->displayName !!}
                        </h3>
                        @if(isset($category))
                            {!! $page->category->subject['key'] == 'time' && isset($page->data['date']['start']) ? $dateHelper->formatTimeDate($page->data['date']['start']).(isset($page->data['date']['start']) && isset($page->data['date']['end']) ? '-' : '' ).(isset($page->data['date']['end']) ? $dateHelper->formatTimeDate($page->data['date']['end']) : '') : '' !!}{{ ((isset($page->data['date']['start']) || isset($page->data['date']['end'])) && ($page->parent && (isset($page->parent->is_visible) ? ($page->parent->is_visible || (Auth::check() && Auth::user()->canWrite)) : 1))) ? ' ・ ' : '' }}{!! $page->parent && (isset($page->parent->is_visible) ? ($page->parent->is_visible || (Auth::check() && Auth::user()->canWrite)) : 1) ? $page->parent->displayName : '' !!}
                        @else
                            {!! $page->category->subject['term'].' ・ '.$page->category->displayName !!}{!! $page->category->subject['key'] == 'time' && isset($page->data['date']['start']) ? ' ・ '.$dateHelper->formatTimeDate($page->data['date']['start']).(isset($page->data['date']['start']) && isset($page->data['date']['end']) ? '-' : '' ).(isset($page->data['date']['end']) ? $dateHelper->formatTimeDate($page->data['date']['end']) : '') : '' !!}{!! $page->parent && (isset($page->parent->is_visible) ? ($page->parent->is_visible || (Auth::check() && Auth::user()->canWrite)) : 1) ? ' ・ '.$page->parent->displayName : '' !!}
                        @endif
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
        @foreach($pages->chunk(10) as $chunk)
            <div class="col-md">
                @foreach($chunk->groupBy(function ($item, $key) {return substr(strtolower($item->title), 0, 1);}) as $group)
                    <h4>{{ ucfirst(substr($group->first()->title, 0, 1)) }}</h4>
                    <ul>
                        @foreach($group as $page)
                            <li>{!! $page->displayName !!}{!! !isset($category) ? ' ('.$page->category->subject['term'].', '.$page->category->displayName.')' : '' !!}</li>
                        @endforeach
                    </ul>
                    @php if($loop->last) unset($page); @endphp
                @endforeach
            </div>
        @endforeach
    </div>
</div>

{!! $pages->render() !!}

<div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }} found.</div>
