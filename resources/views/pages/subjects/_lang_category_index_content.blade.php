<div class="text-right mb-3">
    <div class="btn-group">
        <button type="button" class="btn btn-secondary active lang-category-grid-view-button" data-toggle="tooltip"
            title="Grid View" alt="Grid View"><i class="fas fa-th"></i></button>
        <button type="button" class="btn btn-secondary lang-category-list-view-button" data-toggle="tooltip"
            title="List View" alt="List View"><i class="fas fa-bars"></i></button>
    </div>
</div>

{!! $categories->render() !!}

<div id="langCategoryGridView" class="hide">
    <div class="row">
        @foreach ($categories as $category)
            {!! $loop->remaining + 1 == $loop->count % 3 ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header text-center">
                        @if ($category->has_image)
                            <a href="{{ $category->url }}"><img src="{{ $category->imageUrl }}"
                                    class="mw-100 mb-1" /></a>
                        @endif
                        <h3>
                            {!! $category->displayName !!}
                        </h3>
                    </div>
                    @if ($category->summary)
                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item">
                                {{ $category->summary }}
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
            {!! $loop->count % 3 != 0 && $loop->last ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            {!! $loop->iteration % 3 == 0 ? '<div class="w-100"></div>' : '' !!}
            @php
                if ($loop->last) {
                    unset($category);
                }
            @endphp
        @endforeach
    </div>
</div>
<div id="langCategoryListView" class="hide">
    <div class="row">
        @foreach ($categories->chunk(10) as $chunk)
            <div class="col-md">
                @foreach ($chunk->groupBy(function ($item, $key) {
        return substr(strtolower($item->name), 0, 1);
    })
    as $group)
                    <h4>{{ ucfirst(substr($group->last()->name, 0, 1)) }}</h4>
                    <ul>
                        @foreach ($group as $category)
                            <li>{!! $category->displayName !!}</li>
                        @endforeach
                    </ul>
                    @php
                        if ($loop->last) {
                            unset($category);
                        }
                    @endphp
                @endforeach
            </div>
        @endforeach
    </div>
</div>

{!! $categories->render() !!}

<div class="text-center mt-4 small text-muted">{{ $categories->total() }}
    result{{ $categories->total() == 1 ? '' : 's' }} found.</div>
