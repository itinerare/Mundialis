<div class="card mb-4">
    <div class="card-header">
        <h4>Recent Images</h4>
    </div>
    <div class="card-body">
        <div class="row ml-md-2 mb-4">
            <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
                <div class="col-md font-weight-bold">Image</div>
                <div class="col-md font-weight-bold">Date</div>
                <div class="col-md font-weight-bold">User</div>
                <div class="col-md font-weight-bold">Type</div>
            </div>
            @foreach ($imageVersions as $version)
                <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                    <div class="col-md text-center align-self-center">
                        @if ($version->image)
                            <a href="{{ url('special/get-image/' . $version->image->id) }}"
                                class="image-link mw-100"><img src="{{ $version->image->thumbnailUrl }}"
                                    class="img-thumbnail mw-100" /></a>
                        @else
                            Deleted image
                        @endif
                    </div>
                    <div class="col-md align-self-center">{!! pretty_date($version->created_at) !!}</div>
                    <div class="col-md align-self-center">{!! $version->user->displayName !!}</div>
                    <div class="col-md align-self-center">{{ $version->type }}{!! $version->is_minor ? ' (<abbr data-toggle="tooltip" title="This edit is minor">m</abbr>)' : '' !!}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
