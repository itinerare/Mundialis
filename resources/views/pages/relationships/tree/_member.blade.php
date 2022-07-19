<div class="col-md text-center align-self-center">
    @if ($member['page']->image)
        <img src="{{ $member['page']->image->thumbnailUrl }}" style="width:100px;" class="img-thumbnail mw-100" />
    @else
        <img src="{{ asset('images/logo.png') }}" style="width:100px;" class="img-thumbnail mw-100" />
    @endif
    <div>
        {!! $member['page']->displayName !!}<br />
        {{ $member['displayType'] }}
    </div>
</div>
