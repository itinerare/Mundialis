<div class="card">
    <div class="mt-2 mx-1">
        @if($page->image)
            <a href="{{ url('pages/get-image/'.$page->id.'/'.$page->image->id) }}" class="image-link"><img src="{{ $page->image->thumbnailUrl }}" class="img-thumbnail mw-100 mb-2" /></a>
        @endif
        @if(isset($page->category->subject['segments']['infobox']) && View::exists('pages.content_builder._'.$page->category->subject['key'].'_infobox'))
            @include('pages.content_builder._'.$page->category->subject['key'].'_infobox')
        @endif
        @if(isset($page->category->template['infobox']))
            @foreach($page->category->template['infobox'] as $key=>$field)
                @if(isset($page->data[$key]))
                    <div class="row mb-2">
                        <div class="col-sm-5 bg-dark text-light rounded pt-1"><h6><strong>{{ $field['label'] }}</strong></h6></div>
                        <div class="col-sm">
                            <div class="pt-1">
                                @if($field['type'] == 'checkbox')
                                    {!! isset($page->data[$key]) ? ($page->data[$key] ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>') : '' !!}
                                @elseif(($field['type'] == 'multiple' || $field['type'] == 'choice') && isset($field['choices']))
                                    @if($field['type'] == 'multiple')
                                        @foreach($page->data[$key] as $answer)
                                            {{ isset($field['choices'][$answer]) ? $field['choices'][$answer] : $answer }}{{ !$loop->last ? ',' : '-' }}
                                        @endforeach
                                    @else
                                        {{ isset($page->data[$key]) ? $field['choices'][$page->data[$key]] : $page->data[$key] }}
                                    @endif
                                @elseif($field['type'] != 'checkbox')
                                    {!! isset($page->data[$key]) ? nl2br(htmlentities($page->data[$key])) : '-' !!}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>
