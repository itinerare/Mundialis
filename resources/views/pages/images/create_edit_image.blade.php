@extends('pages.layout')

@section('pages-title') {{ $page->title }} @endsection

@section('pages-content')
{!! breadcrumbs([$page->category->subject['name'] => $page->category->subject['key'], $page->category->name => $page->category->subject['key'].'/categories/'.$page->category->id, $page->title => $page->url, 'Gallery' => 'pages/'.$page->id.'/gallery', ($image->id ? 'Edit' : 'Create').' Image' => 'pages/gallery/'.$page->id.'/create']) !!}

@include('pages._page_header', ['section' => ($image->id ? 'Edit' : 'Create').' Image'])
@if($image->id)
    <a href="#" class="btn btn-danger float-right delete-image-button">Delete Image</a>
@endif

{!! Form::open(['url' => $image->id ? 'pages/'.$page->id.'/gallery/edit/'.$image->id : 'pages/'.$page->id.'/gallery/create', 'files' => true]) !!}

<div class="form-group">
    {!! Form::label('Character Image') !!} {!! add_help('This is the full masterlist image. Note that the image is not protected in any way, so take precautions to avoid art/design theft.') !!}
    <div>{!! Form::file('image', ['id' => 'mainImage']) !!}</div>
</div>

@if(Config::get('mundialis.settings.image_thumbnail_automation') === 1)
    <div class="form-group">
        {!! Form::checkbox('use_cropper', 1, 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle', 'id' => 'useCropper']) !!}
        {!! Form::label('use_cropper', 'Use Thumbnail Automation', ['class' => 'form-check-label ml-3']) !!} {!! add_help('A thumbnail is required. You can use the thumbnail automation or upload a custom thumbnail.') !!}
    </div>
    <div class="card mb-3" id="thumbnailCrop">
        <div class="card-body">
            <div id="cropSelect">By using this function, the thumbnail will be automatically generated from the full image.</div>
            {!! Form::hidden('x0', 1) !!}
            {!! Form::hidden('x1', 1) !!}
            {!! Form::hidden('y0', 1) !!}
            {!! Form::hidden('y1', 1) !!}
        </div>
    </div>
@else
    <div class="form-group">
        {!! Form::checkbox('use_cropper', 1, 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle', 'id' => 'useCropper']) !!}
            {!! Form::label('use_cropper', 'Use Image Cropper', ['class' => 'form-check-label ml-3']) !!} {!! add_help('A thumbnail is required. You can use the image cropper (crop dimensions can be adjusted in the site code), or upload a custom thumbnail.') !!}
    </div>
    <div class="card mb-3" id="thumbnailCrop">
        <div class="card-body">
            <div id="cropSelect">Select an image to use the thumbnail cropper.</div>
            <img src="#" id="cropper" class="hide" />
            {!! Form::hidden('x0', null, ['id' => 'cropX0']) !!}
            {!! Form::hidden('x1', null, ['id' => 'cropX1']) !!}
            {!! Form::hidden('y0', null, ['id' => 'cropY0']) !!}
            {!! Form::hidden('y1', null, ['id' => 'cropY1']) !!}
        </div>
    </div>
@endif
<div class="card mb-3" id="thumbnailUpload">
    <div class="card-body">
        {!! Form::label('Thumbnail Image') !!} {!! add_help('This image is shown on page index and in the infobox if the image is the page\'s primary image, or in the page\'s gallery.') !!}
        <div>{!! Form::file('thumbnail') !!}</div>
        <div class="text-muted">Recommended size: {{ Config::get('mundialis.settings.image_thumbnails.width') }}px x {{ Config::get('mundialis.settings.image_thumbnails.height') }}px</div>
    </div>
</div>

<h3>Image Information</h3>
<div class="form-group">
    {!! Form::label('Page(s) (Optional)') !!} {!! add_help('Pages to associate this image with <strong>in addition to</strong> this one.') !!}
    {!! Form::select('page_id[]', $pageOptions, $image->pages ? $image->pages()->pluck('pages.id')->toArray() : null, ['class' => 'form-control select-page', 'multiple']) !!}
</div>

<div class="form-group">
    {!! Form::label('Creator(s)') !!} {!! add_help('Either select an on-site user, or enter the URL of an off-site user\'s profile.') !!}
    <div id="creatorList">
        @if(!$image->id)
            <div class="mb-2 d-flex">
                {!! Form::select('creator_id[]', $users, null, ['class'=> 'form-control mr-2 selectize', 'placeholder' => 'Select a Creator']) !!}
                {!! Form::text('creator_url[]', null, ['class' => 'form-control mr-2', 'placeholder' => 'Creator URL']) !!}
                <a href="#" class="add-creator btn btn-link" data-toggle="tooltip" title="Add another creator">+</a>
            </div>
        @else
            @foreach($image->creators as $creator)
                <div class="mb-2 d-flex">
                    {!! Form::select('creator_id[]', $users, $creator->user_id, ['class'=> 'form-control mr-2 selectize', 'placeholder' => 'Select a Creator']) !!}
                    {!! Form::text('creator_url[]', $creator->url, ['class' => 'form-control mr-2', 'placeholder' => 'Creator URL']) !!}
                    <a href="#" class="add-creator btn btn-link" data-toggle="tooltip" title="Add another creator">+</a>
                </div>
            @endforeach
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $image->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="row">
    <div class="col-md">
        <div class="form-group">
            {!! Form::checkbox('is_visible', 1, $image->id ? $image->is_visible : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('is_visible', 'Is Visible', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If this is turned off, the image will still be hidden from visitors or users without write permissions.') !!}
        </div>
    </div>
    <div class="col-md">
        <div class="form-group">
            {!! Form::checkbox('is_valid', 1, $image->id ? $image->pivot->is_valid : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('is_valid', 'Is Valid', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If this is turned off, the image will still be visible, but displayed with a note that the image is not a valid reference <strong>for this specific page</strong>. The image may still remain valid for other pages.') !!}
        </div>
    </div>
    @if(!$image->id)
        <div class="col-md">
            <div class="form-group">
                {!! Form::checkbox('mark_invalid', 1, 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
                {!! Form::label('mark_invalid', 'Invalidate Old Images', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If this is turned on, this page\'s old images will be marked invalid. This will not impact other pages that use the same image.') !!}
            </div>
        </div>
    @endif
    @if((!$page->image_id || $page->image_id != $image->id) && $page->images()->where('page_images.id', $image->id)->first()->pivot->is_valid)
        <div class="col-md">
            <div class="form-group">
                {!! Form::checkbox('mark_active', 1, !$page->image_id || !$image->id ? 1 : 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
                {!! Form::label('mark_active', 'Set Active', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If this is turned on, this image will be set as the page\'s active image.') !!}
            </div>
        </div>
    @endif
</div>

<div class="text-right">
    {!! Form::submit(($image->id ? 'Edit' : 'Create').' Image', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}

<div class="creator-row hide mb-2">
    {!! Form::select('creator_id[]', $users, null, ['class'=> 'form-control mr-2 creator-select', 'placeholder' => 'Select a Creator']) !!}
    {!! Form::text('creator_url[]', null, ['class' => 'form-control mr-2', 'placeholder' => 'Creator URL']) !!}
    <a href="#" class="add-creator btn btn-link mb-2" data-toggle="tooltip" title="Add another creator">+</a>
</div>

@endsection

@section('scripts')
@parent
    @include('pages.images._image_upload_js')

<script>
    $( document ).ready(function() {
        $('.delete-image-button').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('pages') }}/{{ $page->id }}/gallery/delete/{{ $image->id }}", 'Delete Image');
        });
    });

</script>
@endsection
