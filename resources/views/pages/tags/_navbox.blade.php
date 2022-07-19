<div class="card mb-2">
    <div class="card-header pt-2 pb-0 text-center">
        <h5>
            {!! isset($navbox['hub']) ? $navbox['hub']->page->displayName : $tag->displayNameBase !!}
            <a class="small collapse-toggle collapsed float-right" href="#navbox-{{ $tag->id }}"
                data-toggle="collapse">Hide</a></h3>
        </h5>
    </div>

    <div class="collapse {{ !isset($navbox['pages']) || $navbox['pages']->count() < 30 ? 'show' : '' }}"
        id="navbox-{{ $tag->id }}">
        @if (isset($navbox['subjects']))
            <div class="px-2">
                @foreach (Config::get('mundialis.subjects') as $subjectKey => $subjectValues)
                    @if (isset($navbox['subjects'][$subjectKey]))
                        <div class="row my-1">
                            <div class="col-md-2 bg-dark text-light rounded pt-1">
                                <h6><strong>
                                        {{ $subjectValues['name'] }}
                                    </strong></h6>
                            </div>
                            <div class="col-md pl-1">
                                <div>
                                    @foreach ($navbox['pages']->groupBy('category_id') as $group)
                                        @if (!$group->first()->category->parent_id && $group->first()->category->subject['key'] == $subjectKey)
                                            @include('pages.tags._navbox_category')
                                        @elseif($group->first()->category->parent_id &&
                                            !$group->where('category_id', $group->first()->category->parent_id)->count() &&
                                            $group->first()->category->subject['key'] == $subjectKey)
                                            @include('pages.tags._navbox_category')
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        @if (isset($navbox['hub']) || $tag->hasTimeline)
            <div class="card-header pt-2 pb-0 text-center">
                <h6>
                    {!! isset($navbox['hub']) ? $tag->displayNameBase : null !!}
                    {{ isset($navbox['hub']) && $tag->hasTimeline ? '・' : '' }}
                    {!! $tag->hasTimeline
                        ? '<a href="' . url('time/timeline?tags[]=' . str_replace(' ', '+', $tag->baseTag)) . '">Timeline</a>'
                        : '' !!}
                </h6>
            </div>
        @endif
    </div>
</div>
