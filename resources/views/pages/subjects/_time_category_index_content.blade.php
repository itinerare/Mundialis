<div class="text-right mb-3">
    <div class="btn-group">
        <button type="button" class="btn btn-secondary active time-category-grid-view-button" data-toggle="tooltip"
            title="Grid View" alt="Grid View"><i class="fas fa-th"></i></button>
        <button type="button" class="btn btn-secondary time-category-list-view-button" data-toggle="tooltip"
            title="List View" alt="List View"><i class="fas fa-bars"></i></button>
    </div>
</div>

{!! $categories->render() !!}

<div id="timeCategoryGridView" class="hide">
    <div class="row">
        @foreach ($categories as $category)
            {!! $loop->remaining + 1 == $loop->count % 3 ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header text-center">
                        <h3>
                            {!! $category->displayNameFull !!}
                        </h3>
                    </div>
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
<div id="timeCategoryListView" class="hide">
    <div class="row">
        @foreach ($categories->chunk(10) as $chunk)
            <div class="col-md">
                @foreach ($chunk->groupBy(function ($item, $key) {
        return substr(strtolower($item->name), 0, 1);
    }) as $group)
                    <h4>{{ ucfirst(substr($group->last()->name, 0, 1)) }}</h4>
                    <ul>
                        @foreach ($group as $category)
                            <li>{!! $category->displayNameFull !!}</li>
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
