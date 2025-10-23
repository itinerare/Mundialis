<div class="container">
    <div class="content align-self-center">
        @if ((isset($image->content_warning) && $image->content_warning) || (isset($image->pivot) && !$image->pivot->is_valid))
            <div class="image-badge badge-warning">
                @if (isset($image->content_warning) && $image->content_warning)
                    <abbr data-toggle="tooltip" title="Content warning: {{ $image->content_warning }}"><i
                            class="fas fa-exclamation-triangle"></i></abbr>
                @endif
                @if (isset($image->content_warning) && $image->content_warning && (isset($image->pivot) && !$image->pivot->is_valid))
                    /
                @endif
                @if (isset($image->pivot) && !$image->pivot->is_valid)
                    <abbr data-toggle="tooltip" title="This image is outdated for this page."><i
                            class="fas fa-times-circle"></i></abbr>
                @endif
            </div>
        @endif
        <a class="align-self-center image-link"
            href="{{ url(!isset($context) || $context == 'page' ? 'pages/get-image/' . $page->id . '/' . $image->id : 'special/get-image/' . $image->id) }}">
            <div class="text-center align-self-center my-auto">
                <img src="{{ Storage::url($image->thumbnailUrl) }}"
                    class="img-thumbnail mw-100 {{ isset($image->pivot) && !$image->pivot->is_valid ? 'invalid-image' : '' }} {{ isset($image->content_warning) && $image->content_warning ? 'content-warning-image' : '' }} {{ isset($context) && $context == 'recent' ? 'recent-image' : '' }}" />
            </div>
        </a>
    </div>
</div>
