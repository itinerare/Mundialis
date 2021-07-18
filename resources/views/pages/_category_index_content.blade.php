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
        @foreach($categories->chunkWhile(function ($value, $key, $chunk) {return substr($value->name, 0, 1) === substr($chunk->first()->name, 0, 1);}) as $chunk)
            {!! $loop->first || $loop->iteration == 3 ? '<div class="col-md-3">' : '' !!}
                <h4>{{ ucfirst(substr($chunk->last()->name, 0, 1)) }}</h4>
                <ul>
                    @foreach($chunk as $category)
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
