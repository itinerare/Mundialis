<div class="card mb-4">
    <div class="card-header">
        <h4>Recent Pages</h4>
    </div>
    <div class="card-body">
        <div class="row ml-md-2 mb-4">
            <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
                <div class="col-md font-weight-bold">Page</div>
                <div class="col-md font-weight-bold">Date</div>
                <div class="col-md font-weight-bold">User</div>
                <div class="col-md font-weight-bold">Type</div>
            </div>
            @foreach($pageVersions as $version)
            <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
                <div class="col-md">
                    {!! $version->page ? $version->page->displayName : 'Deleted page' !!}
                </div>
                <div class="col-md">
                    {!! pretty_date($version->created_at) !!}
                </div>
                <div class="col-md">{!! $version->user->displayName !!}</div>
                <div class="col-md">{{ $version->type }}{!! $version->is_minor ? ' (<abbr data-toggle="tooltip" title="This edit is minor">m</abbr>)' : '' !!}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
