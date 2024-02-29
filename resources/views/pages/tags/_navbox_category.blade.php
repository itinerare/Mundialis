<div class="row ml-0 mb-1">
    <div class="col-sm-2 col-4 alert-dark text-light rounded pt-1">
        <h6><strong>
                {!! $group->first()->category->displayName !!}
            </strong></h6>
    </div>
    <div class="col-sm col mb-1 pl-2">
        <div>
            @if (
                $navbox['pages']->whereIn(
                        'category_id',
                        $group->first()->category->children()->pluck('id')->toArray())->count())
                @php
                    $subcatPages = $navbox['pages']->whereIn(
                        'category_id',
                        $group
                            ->first()
                            ->category->children()
                            ->pluck('id')
                            ->toArray(),
                    );
                @endphp
                <div class="row pl-2">
                    <div class="col-sm-2 col-6 alert-dark text-light rounded pt-1">
                        <h6><strong>
                                {!! $subcatPages->first()->category->displayName !!}
                            </strong></h6>
                    </div>
                    <div class="col-sm col pl-1">
                        <div class="pt-1">
                            @foreach ($subcatPages as $page)
                                {!! $page->displayName !!}{{ !$loop->last ? '・' : '' }}
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            <div class="pt-1">
                @foreach ($group as $page)
                    {!! $page->displayName !!}{{ !$loop->last ? '・' : '' }}
                @endforeach
            </div>
        </div>
    </div>
</div>
