@extends('pages.layout')

@section('pages-title')
    {{ $page->title }} - Relationships
@endsection

@section('meta-img')
    {{ $page->image ? $page->image->thumbnailUrl : asset('images/logo.png') }}
@endsection

@section('meta-desc')
    {{ $page->summary ? $page->summary : Config::get('mundialis.settings.site_desc') }}
@endsection

@section('pages-content')
    {!! breadcrumbs([
        $page->category->subject['name'] => $page->category->subject['key'],
        $page->category->name => $page->category->subject['key'] . '/categories/' . $page->category->id,
        $page->title => $page->url,
        'Relationships' => 'pages/' . $page->id . '/relationships',
        'Family Tree' => 'pages/' . $page->id . '/relationships/tree',
    ]) !!}

    @include('pages._page_header', ['section' => 'Family Tree'])

    <div class="text-right mb-4">
        <a href="{{ url('pages/' . $page->id . '/relationships') }}" class="btn btn-secondary mt-4 ml-2">Back to Index</a>
    </div>

    <p>This {{ strtolower($page->category->subject['term']) }}'s ancestroy and immediate relations are listed here based
        on their existing relationships.</p>

    <h2>Ancestry</h2>
    <div class="mx-auto">
        <div class="row">
            @include('pages.relationships.tree._tree_member', [
                'member' => [
                    'link' => null,
                    'type' => null,
                    'displayType' => '[Current Page]',
                    'page' => $page,
                ],
            ])
        </div>
    </div>

    @if ($page->personRelations('siblings'))
        <h2>Siblings</h2>
        <div class="row">
            @foreach ($page->personRelations('siblings') as $member)
                {!! $loop->remaining + 1 == $loop->count % 2 ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
                <div class="col-md-6 mb-3">
                    @include('pages.relationships.tree._member')
                </div>
                {!! $loop->count % 2 != 0 && $loop->last ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
                {!! $loop->iteration % 2 == 0 ? '<div class="w-100"></div>' : '' !!}
            @endforeach
        </div>
    @endif

    @if ($page->personRelations('children'))
        <h2>Children</h2>
        <div class="row">
            @foreach ($page->personRelations('children') as $member)
                {!! $loop->remaining + 1 == $loop->count % 2 ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
                <div class="col-md-6 mb-3">
                    @include('pages.relationships.tree._member')
                </div>
                {!! $loop->count % 2 != 0 && $loop->last ? '<div class="my-auto col mobile-hide"></div>' : '' !!}
                {!! $loop->iteration % 2 == 0 ? '<div class="w-100"></div>' : '' !!}
            @endforeach
        </div>
    @endif

    <script>
        $(function() {
            $('.tree ul').hide();
            $('.tree>ul').show();
            $('.tree ul.active').show();
            $('.tree li').on('click', function(e) {
                var children = $(this).find('> ul');
                if (children.is(":visible")) children.hide('fast').removeClass('active');
                else children.show('fast').addClass('active');
                e.stopPropagation();
            });
        });
    </script>

@endsection
