<div class="text-right mb-3">
    <div class="btn-group">
        <button type="button" class="btn btn-secondary active category-grid-view-button" data-toggle="tooltip" title="Grid View" alt="Grid View"><i class="fas fa-th"></i></button>
        <button type="button" class="btn btn-secondary category-list-view-button" data-toggle="tooltip" title="List View" alt="List View"><i class="fas fa-bars"></i></button>
    </div>
</div>

{!! $categories->render() !!}

<div id="categoryGridView" class="hide">
    <div class="row">
        @foreach($categories as $category)
            {!! ($loop->remaining+1) == ($loop->count%3) ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header text-center">
                        <h3>
                            {!! $category->displayName !!}
                        </h3>
                    </div>
                </div>
            </div>
            {!! $loop->count%3 != 0 && $loop->last ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
            {!! $loop->iteration % 3 == 0 ? '<div class="w-100"></div>' : '' !!}
            @php if($loop->last) unset($category); @endphp
        @endforeach
    </div>
</div>
<div id="categoryListView" class="hide">
    <div class="row">
        @foreach($categories->groupBy(function ($item, $key) {return substr(strtolower($item->title), 0, 1);}) as $group)
            {!! $loop->first || $loop->iteration == 3 ? '<div class="col-md-3">' : '' !!}
                <h4>{{ ucfirst(substr($group->last()->name, 0, 1)) }}</h4>
                <ul>
                    @foreach($group as $category)
                        <li>{!! $category->displayName !!}</li>
                    @endforeach
                </ul>
            {!! $loop->last || $loop->iteration == 3 ? '</div>' : '' !!}
            @php if($loop->last) unset($category); @endphp
        @endforeach
    </div>
</div>

{!! $categories->render() !!}

<div class="text-center mt-4 small text-muted">{{ $categories->total() }} result{{ $categories->total() == 1 ? '' : 's' }} found.</div>
