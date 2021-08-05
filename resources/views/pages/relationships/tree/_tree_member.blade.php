<div class="col-md text-center align-self-center">
    @if($member['page']->personRelations('parents'))
        <div class="row mb-2">
            @foreach($member['page']->personRelations('parents') as $parent)
                @include('pages.relationships.tree._tree_member', ['member' => $parent])
            @endforeach
        </div>
        @if($member['page']->personRelations('parents')->count() > 1)
            <div class="border-top-0 border border-secondary rounded-bottom mb-2" style="width:{{ 25*$member['page']->personRelations('parents')->count() }}%; margin-left:{{ (100-(25*$member['page']->personRelations('parents')->count()))/2 }}%; height:20px;"></div>
        @endif
    @endif
    @if($member['page']->image)
        <img src="{{ $member['page']->image->thumbnailUrl }}" style="width:100px;" class="img-thumbnail mw-100"/>
    @else
        <img src="{{ asset('images/logo.png') }}" style="width:100px;" class="img-thumbnail mw-100"/>
    @endif
    <div>
        {!! $member['page']->displayName !!}<br/>
        {{ $member['displayType'] }}
    </div>
</div>
