<div class="gallery-popup card bg-secondary" style="width:90%; margin-left:5%;">
    <div class="card-header text-center">
        <img src="{{ $image->imageUrl }}" class="mw-100 mb-2" style="max-height: 85vh;" />
    </div>
    <div class="card-body bg-light rounded-bottom">
        @include('pages.images._info_content')
    </div>
</div>
