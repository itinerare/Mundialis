<div class="card mb-2">
    <div class="row no-gutters">
        @if ($page->image)
            <div class="col-md-4 mobile-hide">
                <a href="{{ $page->url }}"><img class="img-thumbnail mw-100" src="{{ $page->image->thumbnailUrl }}" />
                </a>
            </div>
        @endif
        <div class="{{ $page->image ? 'col-md-8' : 'col-md' }}">
            <div class="card-header p-md-2 p-0">
                <div class="row no-gutters">
                    @if ($page->image)
                        <div class="col-4 mobile-show">
                            <a href="{{ $page->url }}"><img class="img-thumbnail mw-100"
                                    src="{{ $page->image->thumbnailUrl }}" />
                            </a>
                        </div>
                    @endif
                    <div class="col mx-2 mx-md-0 pt-3 pt-md-2">
                        <h5>
                            {!! $page->displayName !!}
                            {!! isset($page->data['date']['start'])
                                ? ' ・ ' .
                                    $dateHelper->formatTimeDate($page->data['date']['start']) .
                                    (isset($page->data['date']['start']) && isset($page->data['date']['end']) ? '-' : '') .
                                    (isset($page->data['date']['end']) ? $dateHelper->formatTimeDate($page->data['date']['end']) : '')
                                : '' !!}
                            <small>
                                <br />{!! $page->category->displayName !!}
                            </small>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p>{!! $page->summary ? nl2br(htmlentities($page->summary)) : '<i>No summary provided.</i>' !!}</p>

                @if ($page->tags->count())
                    <div>
                        <strong>Tags:</strong>
                        @foreach ($page->tags as $tag)
                            {!! $tag->displayName !!}{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
