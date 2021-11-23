@extends('pages.layout')

@section('pages-title') {{ $page->title }} - Relationships @endsection

@section('meta-img')
    {{ $page->image ? $page->image->thumbnailUrl : asset('images/logo.png') }}
@endsection

@section('meta-desc')
    {{ $page->summary ? $page->summary : Config::get('mundialis.settings.site_desc') }}
@endsection

@section('pages-content')
{!! breadcrumbs([$page->category->subject['name'] => $page->category->subject['key'], $page->category->name => $page->category->subject['key'].'/categories/'.$page->category->id, $page->title => $page->url, 'Relationships' => 'pages/'.$page->id.'/relationships']) !!}

@include('pages._page_header', ['section' => 'Relationships'])

<div class="text-right mb-4">
    @if($page->personRelations())
        <a href="{{ url('pages/'.$page->id.'/relationships/tree') }}" class="btn btn-secondary mt-4 ml-2">Family Tree</a>
    @endif
    @if(Auth::check() && Auth::user()->canEdit($page))
        <a href="#" class="btn btn-primary mt-4 ml-2 add-relationship-button">Add Relationship</a>
    @endif
</div>

<div>
    {!! Form::open(['method' => 'GET', 'class' => '']) !!}
        <div class="form-inline justify-content-end">
            <div class="form-group mr-3 mb-3">
                {!! Form::select('sort', [
                    'newest'         => 'Newest First',
                    'oldest'         => 'Oldest First',
                ], Request::get('sort') ? : 'oldest', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group mb-3">
                {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}
</div>

{!! $relationships->render() !!}

@if($relationships->count())
    @foreach($relationships as $relationship)
        <div class="row mb-4">
            @foreach([($relationship->page_one_id == $page->id ? 'one' : 'two'), ($relationship->page_one_id == $page->id ? 'two' : 'one')] as $iteration)
                @include('pages.relationships._relationship', [
                    // These are are the properties, etc from the relationship that are
                    // used multiple times, so determining them here significantly cleans
                    // up the included view
                    'relationshipPage' => ($iteration == 'one' ? $relationship->pageOne : $relationship->pageTwo),
                    'type' => ($iteration == 'one' ? $relationship->type_one : $relationship->type_two),
                    'displayType' => ($iteration == 'one' ? $relationship->displayTypeOne : $relationship->displayTypeTwo)
                ])
            @endforeach
            @if(Auth::check() && Auth::user()->canEdit($page))
                <div class="col-sm-1 text-center">
                    <div class="row h-100">
                        <div class="col mb-2">
                            <a href="#" data-id="{{ $relationship->id }}" class="edit-relationship-button btn btn-primary h-100 w-100">
                                <div class="mobile-hide mt-3">
                                    <h5><i class="fas fa-edit"></i></h5>
                                </div>
                                <div class="mobile-show">
                                    <h5><i class="fas fa-edit"></i></h5>
                                </div>
                            </a>
                        </div>
                        <div class="col mb-2">
                            <a href="#" data-id="{{ $relationship->id }}" class="delete-relationship-button btn btn-danger h-100 w-100">
                                <div class="mobile-hide mt-3">
                                    <h5><i class="fas fa-trash-alt"></i></h5>
                                </div>
                                <div class="mobile-show">
                                    <h5><i class="fas fa-trash-alt"></i></h5>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
@else
    <p>No relationships found.</p>
@endif

{!! $relationships->render() !!}

<div class="text-center mt-4 small text-muted">{{ $relationships->total() }} result{{ $relationships->total() == 1 ? '' : 's' }} found.</div>

@endsection

@section('scripts')
@parent

<script>
    $( document ).ready(function() {
        $('.add-relationship-button').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('pages') }}/{{ $page->id }}/relationships/create", 'Create Relationship');
        });

        $('.edit-relationship-button').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('pages') }}/{{ $page->id }}/relationships/edit/" + $(this).data('id'), 'Edit Relationship');
        });

        $('.delete-relationship-button').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('pages') }}/{{ $page->id }}/relationships/delete/" + $(this).data('id'), 'Delete Relationship');
        });

        $(".selectize").selectize();
    });

</script>
@endsection
