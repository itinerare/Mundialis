@if($entry)
    <h3>
        {{ $entry->word }}<span class="small">, {{ $entry->class }}</span>
    </h3>
    @if($entry->pronunciation)
        <h6>({{ $entry->pronunciation }})</h6>
    @endif

    <strong>Meaning:</strong> {{ $entry->meaning }}

    @if($entry->definition)
        <hr/>
        <h5>Definition:</h5>
        {!! $entry->definition !!}
    @endif
@else
    Invalid entry selected.
@endif
