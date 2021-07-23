{!! isset($field['is_subsection']) && $field['is_subsection'] ? ($field['is_subsection'] == 1 ? '<h3 id="subsection-'.$key.'">'.$field['label'].'</h3>' : '<h4 id="subsection-'.$key.'">'.$field['label'].'</h4>') : '' !!}
@if($field['type'] == 'checkbox')
    {!! isset($page->data[$key]) ? ($page->data[$key] ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>') : '' !!}
@elseif(($field['type'] == 'multiple' || $field['type'] == 'choice') && isset($field['choices']))
    @if($field['type'] == 'multiple')
        @foreach($page->data[$key] as $answer)
            {{ isset($field['choices'][$answer]) ? $field['choices'][$answer] : $answer }}{{ !$loop->last ? ',' : '' }}
        @endforeach
    @else
        {{ isset($page->data[$key]) ? $field['choices'][$page->data[$key]] : $page->data[$key] }}
    @endif
@elseif($field['type'] != 'checkbox')
    {!! isset($page->data[$key]) ? $page->data[$key] : '' !!}
@endif
