<div class="col-md mb-2">
    <div class="card h-100">
        <div class="row no-gutters">
            @if ($loop->iteration == 1 && $relationshipPage->image)
                <div class="col-sm-4">
                    <a href="{{ $relationshipPage->url }}"><img class="img-thumbnail mw-100"
                            src="{{ Storage::url($relationshipPage->image->thumbnailUrl) }}" />
                    </a>
                </div>
            @endif
            <div class="{{ $relationshipPage->image ? 'col-md-8' : 'col-md' }}">
                <div class="card-header {{ $loop->iteration == 1 ? 'text-right' : '' }}">
                    <h5>
                        {{ $loop->iteration == 1 ? $displayType . ' ・' : '' }}
                        {!! $relationshipPage->displayName !!}
                        {{ $loop->iteration == 2 ? '・ ' . $displayType : '' }}
                    </h5>
                    {{ $type != 'custom' && $type != 'romantic_custom'
                        ? // This is a little redundant, but it's faster with type
                        // determined ahead of time
                        ($iteration == 'one'
                            ? $relationship->type_one_info
                            : $relationship->type_two_info)
                        : '' }}
                </div>
                <div class="card-body">
                    {!! nl2br(htmlentities($iteration == 'one' ? $relationship->details_one : $relationship->details_two)) !!}
                </div>
            </div>
            @if ($loop->iteration == 2 && $relationshipPage->image)
                <div class="col-sm-4">
                    <a href="{{ $relationshipPage->url }}"><img class="img-thumbnail mw-100"
                            src="{{ Storage::url($relationshipPage->image->thumbnailUrl) }}" />
                </div>
            @endif
        </div>
    </div>
</div>
