{!! isset($field['is_subsection']) && $field['is_subsection'] ? ($field['is_subsection'] == 1 ? '<h3 id="subsection-'.$key.'">'.$field['label'].'</h3>' : '<h4 id="subsection-'.$key.'">'.$field['label'].'</h4>') : '' !!}
@if($field['type'] == 'checkbox')
    {!! isset($data[$key]) ? ($data[$key] ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>') : '' !!}
@elseif(($field['type'] == 'multiple' || $field['type'] == 'choice') && isset($field['choices']))
    @if($field['type'] == 'multiple')
        @foreach($data[$key] as $choiceKey=>$answer)
            {{ isset($field['choices'][$choiceKey]) ? $field['choices'][$choiceKey] : $answer }}{{ !$loop->last ? ',' : '' }}
        @endforeach
    @else
        {{ isset($data[$key]) ? $field['choices'][$data[$key]] : $data[$key] }}
    @endif
@elseif($field['type'] != 'checkbox')
    {!! isset($data[$key]) ? $data[$key] : '' !!}
@endif
