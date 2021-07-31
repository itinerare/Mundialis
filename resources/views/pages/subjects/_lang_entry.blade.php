@if($entry)
    <h3>
        {{ $entry->word }}<span class="small">, {{ $entry->class }}</span>
    </h3>
    @if($entry->pronunciation)
        <h6>({{ $entry->pronunciation }})</h6>
    @endif

    <strong>Means:</strong> {{ $entry->meaning }}

    @if($entry->etymologies->count())
        <hr/>
        <h5>Etymology</h5>
        {!! ucfirst($entry->getEtymology()) !!}.
    @endif

    @if($entry->definition)
        <hr/>
        <h5>Definition:</h5>
        {!! $entry->definition !!}
    @endif

    @if($entry->descendants->count())
        <hr/>
        <h5>Descendants</h5>
        {!! $entry->getDescendants() !!}
    @endif
@else
    Invalid entry selected.
@endif
