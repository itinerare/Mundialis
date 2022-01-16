@if(isset($page->data['people_name']))
    <div class="row mb-2">
        <div class="col-sm-5 bg-dark text-light rounded pt-1"><h6><strong>Name</strong></h6></div>
        <div class="col-sm">
            <div class="pt-1">
                {{ isset($page->data['people_name']) ? $page->data['people_name'] : '' }}
            </div>
        </div>
    </div>
@endif
@foreach(['birth', 'death'] as $segment)
    @if(isset($page->data[$segment]))
        <div class="row mb-2">
            <div class="col-sm-5 bg-dark text-light rounded pt-1"><h6><strong>{{ ucfirst($segment) }}</strong></h6></div>
            <div class="col-sm">
                <div class="pt-1">
                    {!! isset($page->data[$segment]['date']) ? $dateHelper->formatTimeDate($page->data[$segment]['date']) : '' !!}{!! isset($page->data[$segment]['chronology']) ? ' '.App\Models\Subject\TimeChronology::where('id', $page->data[$segment]['chronology'])->first()->displayName : '' !!}@if($segment == 'death' && $page->personAge($page->data['birth'], $page->data['death'])) (age {{ $page->personAge($page->data['birth'], $page->data['death']) }})@endif{!! isset($page->data[$segment]['place']) && App\Models\Page\Page::visible(Auth::check() ? Auth::user() : null)->where('id', $page->data[$segment]['place'])->first() && isset($page->data[$segment]['date']) ? ',<br/>' : null !!}{!! isset($page->data[$segment]['place']) && App\Models\Page\Page::visible(Auth::check() ? Auth::user() : null)->where('id', $page->data[$segment]['place'])->first() ? App\Models\Page\Page::visible(Auth::check() ? Auth::user() : null)->where('id', $page->data[$segment]['place'])->first()->displayName : null !!}
                </div>
            </div>
        </div>
    @endif
@endforeach
