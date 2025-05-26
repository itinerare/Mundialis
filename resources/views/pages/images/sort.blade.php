@extends('pages.layout')

@section('pages-title')
    {{ $page->title }} - Reorder Images
@endsection

@section('meta-img')
    {{ $page->image ? $page->image->thumbnailUrl : asset('images/logo.png') }}
@endsection

@section('meta-desc')
    {{ $page->summary ? $page->summary : config('mundialis.settings.site_desc') }}
@endsection

@section('pages-content')
    {!! breadcrumbs([
        $page->category->subject['name'] => $page->category->subject['key'],
        $page->category->name => $page->category->subject['key'] . '/categories/' . $page->category->id,
        $page->title => $page->url,
        'Gallery' => $page->url . '/gallery',
        'Reorder Images' => '/sort',
    ]) !!}

    @include('pages._page_header', ['section' => 'Reorder Images'])

    <p>Drag and drop images to reorder them. Note that the order set here is only for this page and does not impact the
        ordering of other pages' images, even if they are associated with multiple pages.</p>

    <div id="sortable" class="row sortable">
        @foreach ($images as $image)
            <div class="col-md-3 col-6 text-center mb-2" data-id="{{ $image->id }}">
                <div>
                    <img src="{{ $image->thumbnailUrl }}" class="img-thumbnail"
                        alt="Thumbnail for image #{{ $image->id }}" />
                </div>
            </div>
        @endforeach
    </div>

    {!! Form::open(['action' => '/pages/' . $page->id . '/gallery/sort', 'class' => 'text-right']) !!}
    {!! Form::hidden('sort', null, ['id' => 'sortableOrder']) !!}
    {!! Form::submit('Save Order', ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $("#sortable").sortable({
                characters: '.sort-item',
                placeholder: "sortable-placeholder col-md-3 col-6",
                stop: function(event, ui) {
                    $('#sortableOrder').val($(this).sortable("toArray", {
                        attribute: "data-id"
                    }));
                },
                create: function() {
                    $('#sortableOrder').val($(this).sortable("toArray", {
                        attribute: "data-id"
                    }));
                }
            });
            $("#sortable").disableSelection();
        });
    </script>
@endsection
