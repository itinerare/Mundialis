<div class="card">
    <div class="mt-2 mx-1">
        @if ($page->image)
            <div class="text-center mb-2">
                @include('pages.images._image_thumb', ['image' => $page->image])
            </div>
        @endif
        @if (isset($page->category->subject['segments']['infobox']) &&
                View::exists('pages.content_builder._' . $page->category->subject['key'] . '_infobox'))
            @include('pages.content_builder._' . $page->category->subject['key'] . '_infobox')
        @endif
        @if (isset($page->category->template['infobox']))
            @foreach ($page->category->template['infobox'] as $key => $field)
                @if (isset($data[$key]))
                    <div class="row mb-2">
                        <div class="col-sm-5 col-4 bg-dark text-light rounded pt-1">
                            <h6><strong>{{ $field['label'] }}</strong></h6>
                        </div>
                        <div class="col-sm col">
                            <div class="pt-1">
                                @if ($field['type'] == 'checkbox')
                                    {!! isset($data[$key])
                                        ? ($data[$key]
                                            ? '<i class="fas fa-check text-success"></i>'
                                            : '<i class="fas fa-times text-danger"></i>')
                                        : '' !!}
                                @elseif(($field['type'] == 'multiple' || $field['type'] == 'choice') && isset($field['choices']))
                                    @if ($field['type'] == 'multiple' && isset($data[$key]))
                                        @foreach ($data[$key] as $choiceKey => $answer)
                                            {{ isset($field['choices'][$choiceKey]) ? $field['choices'][$choiceKey] : $answer }}{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    @elseif($field['type'] == 'choice')
                                        {{ isset($data[$key]) ? $field['choices'][$data[$key]] : '-' }}
                                    @endif
                                @elseif($field['type'] != 'checkbox')
                                    {!! isset($data[$key]) ? $data[$key] : '-' !!}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>
