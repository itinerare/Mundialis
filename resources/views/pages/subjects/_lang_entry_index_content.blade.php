<div>
    {!! Form::open(['method' => 'GET', 'class' => '']) !!}
    <div class="form-inline justify-content-end">
        <div class="form-group mb-3">
            {!! Form::text('word', Request::get('word'), ['class' => 'form-control', 'placeholder' => 'Search Word']) !!}
        </div>
        <div class="form-group mx-3 mb-3">
            {!! Form::text('meaning', Request::get('meaning'), [
                'class' => 'form-control',
                'placeholder' => 'Search Meaning',
            ]) !!}
        </div>
        <div class="form-group mb-3">
            {!! Form::text('pronounciation', Request::get('pronounciation'), [
                'class' => 'form-control',
                'placeholder' => 'Search Pronounciation',
            ]) !!}
        </div>
    </div>
    <div class="form-inline justify-content-end">
        <div class="form-group mb-3">
            {!! Form::select('category_id', $classOptions, Request::get('category_id'), [
                'class' => 'form-control',
                'placeholder' => 'Select a Part of Speech',
            ]) !!}
        </div>
        <div class="form-group mx-3 mb-3">
            {!! Form::select(
                'sort',
                [
                    'alpha' => 'Alphabetical Sort',
                    'alpha-reverse' => 'Alphabetical Sort (Reverse)',
                    'meaning' => 'Sort by Meaning',
                    'meaning-reverse' => 'Sort by Meaning (Reverse)',
                    'newest' => 'Sort by Newest First',
                    'oldest' => 'Sort by Oldest First',
                ],
                Request::get('sort') ?: 'alpha',
                ['class' => 'form-control'],
            ) !!}
        </div>
        <div class="form-group mb-3">
            {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

{!! $entries->render() !!}

<div class="row ml-md-2 mb-4">
    <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
        <div class="col-md-3 font-weight-bold">Word</div>
        <div class="col-md-2 font-weight-bold">Part of Speech</div>
        <div class="col-md font-weight-bold">Meaning</div>
        <div class="col-md-2 font-weight-bold"></div>
    </div>
    @foreach ($entries as $entry)
        <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
            <div class="col-md-3">{{ $entry->word }}</div>
            <div class="col-md-2">{{ $entry->class }}</div>
            <div class="col-md">{{ $entry->meaning }}</div>
            <div class="col-md-2 text-right">
                <a href="" class="btn btn-info btn-sm lang-entry-item" data-id="{{ $entry->id }}">See More</a>
                @if (Auth::check() && Auth::user()->canWrite)
                    <a href="{{ url('language/lexicon/edit/' . $entry->id) }}" class="btn btn-primary btn-sm">Edit</a>
                @endif
            </div>
        </div>
    @endforeach
</div>

{!! $entries->render() !!}

<div class="text-center mt-4 small text-muted">{{ $entries->total() }} result{{ $entries->total() == 1 ? '' : 's' }}
    found.</div>
