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

    @if($entry->parsed_definition)
        <hr/>
        <h5>Definition:</h5>
        {!! $entry->parsed_definition !!}
    @endif

    @if($entry->category->classCombinations($entry->lexicalClass->id))
        <hr/>
        <h5>
            Conjugation/Declension:
            <a class="small collapse-toggle collapsed" href="#conjdecl" data-toggle="collapse">Show</a></h3>
        </h5>
        <div class="row mb-2 collapse" id="conjdecl">
            @foreach($entry->category->classCombinations($entry->lexicalClass->id) as $key=>$combination)
                <div class="col-md-{{ (count($entry->category->data[$entry->lexicalClass->id]['properties']) <= 2 && (count(collect($entry->category->data[$entry->lexicalClass->id]['properties'])->first()['dimensions']) == 2 || count(collect($entry->category->data[$entry->lexicalClass->id]['properties'])->last()['dimensions']) == 2)) && count($entry->category->classCombinations($entry->lexicalClass->id)) < 20 ? 6 : (count($entry->category->classCombinations($entry->lexicalClass->id))%3 == 0 && count($entry->category->classCombinations($entry->lexicalClass->id)) < 30 ? 4 : (count($entry->category->classCombinations($entry->lexicalClass->id))%4 == 0 ? 3 : (count($entry->category->classCombinations($entry->lexicalClass->id)) < 20 ? 6 : 2))) }} text-center mb-2">
                    <h6><strong>{{ $combination }}</strong></h6>
                    {{ isset($entry->data[$combination]) ? $entry->data[$combination] : '-' }}
                </div>
            @endforeach
        </div>
    @endif

    @if($entry->descendants->count())
        <hr/>
        <h5>Descendants</h5>
        {!! $entry->getDescendants() !!}
    @endif
@else
    Invalid entry selected.
@endif
