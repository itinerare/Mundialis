@if (isset($image->pivot) && !$image->pivot->is_valid)
    <div class="alert alert-danger">
        This image is outdated for this page, and only noted here for recordkeeping purposes.
    </div>
@endif
<div class="row no-gutters">
    <div class="col-md mb-2">
        <h5>
            Details{!! $image->is_visible ? '' : '<i class="fas fa-eye-slash"></i>' !!}
            <div class="float-right">
                <span class="badge badge-secondary mr-1">Image #{{ $image->id }}</span>
            </div>
        </h5>
        {!! $image->description ? $image->description : '<i>No description provided.</i><br/>' !!}
        <small>
            Created {!! $image->created_at->format('d F Y') !!} ・ Updated {!! $image->updated_at->format('d F Y') !!}
        </small>
    </div>
    <div class="col-md pl-md-1 mb-2">
        <h5>Creators</h5>
        @foreach ($image->creators as $creator)
            {!! $creator->displayName !!}{{ !$loop->last ? ', ' : '' }}
        @endforeach
    </div>
    <div class="col-md pl-md-1 mb-2">
        <h5>
            Pages
            @if (isset($page))
                <div class="float-right">
                    <a href="{{ url('pages/' . $page->id . '/gallery/' . $image->id) }}" class="btn btn-sm btn-primary"
                        data-toggle="tooltip" title="View Page and Version History"><i class="fas fa-link"></i></a>
                    @if (Auth::check() && Auth::user()->canWrite && (!$image->isProtected || Auth::user()->isAdmin))
                        <a href="{{ url('pages/' . $page->id . '/gallery/edit/' . $image->id) }}"
                            class="btn btn-sm btn-primary mr-1" data-toggle="tooltip" title="Edit Image"><i
                                class="fas fa-pencil-alt"></i></a>
                    @endif
                </div>
            @endif
        </h5>
        @foreach ($image->pages()->visible(Auth::user() ?? null)->get() as $page)
            {!! $page->image_id == $image->id
                ? '<i class="fas fa-star text-info" data-toggle="tooltip" title="This image is this page\'s primary image."></i> '
                : '' !!}
            {!! !$page->pivot->is_valid
                ? '<i class="fas fa-exclamation-triangle text-danger" data-toggle="tooltip" title="This image is outdated for this page."></i> '
                : '' !!}
            <strong>{!! $page->displayName !!}</strong>{{ !$loop->last ? ', ' : '' }}
        @endforeach
    </div>
</div>
