@if ($key != 00 && isset($divisions[$i + 1]))
    {!! $i == 0 ? '<h3>' : '<h5>' !!}
    {!! $divisions[$i]->displayName !!} {{ $key }}
    {!! $i == 0 ? '</h3>' : '</h5>' !!}
@endif

<div class="mb-2">
    @if ($i + 1 < $divisions->count())
        @foreach ($group as $subKey => $subGroup)
            @include('pages.subjects._time_timeline_group', [
                'key' => $subKey,
                'group' => $subGroup,
                'i' => $i + 1,
            ])
        @endforeach
    @else
        @foreach ($group as $page)
            @include('pages.subjects._time_event')
        @endforeach
    @endif
</div>
