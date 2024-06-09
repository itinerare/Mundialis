@extends('pages.layout')

@section('pages-title')
    Special - Most Linked Pages
@endsection

@section('pages-content')
    {!! breadcrumbs(['Special' => 'special', 'Wanted Pages' => 'special/linked-pages']) !!}

    <h1>Special: Most Linked-To Pages</h1>

    <p>This is a list of the most linked-to pages. Note that this list only counts links made within page content.</p>

    {!! $pages->render() !!}

    <ul>
        @foreach ($pages as $group)
            <li>
                {!! $group->first()->linked->displayName !!} ({{ $group->count() }} link{{ $group->count() != 1 ? 's' : '' }}) <a
                    class="collapse-toggle collapsed" href="#group-{{ $group->first()->id }}" data-toggle="collapse">Show <i
                        class="fas fa-caret-right"></i></a></h3>
                <div class="collapse" id="group-{{ $group->first()->id }}">
                    <ul>
                        @foreach ($group as $link)
                            <li>{!! $link->parent->displayName !!}</li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @endforeach
    </ul>

    {!! $pages->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $pages->total() }} result{{ $pages->total() == 1 ? '' : 's' }}
        found.</div>
@endsection
