<div class="form-group">
    @if($field['type'] == 'checkbox')
        {!! Form::checkbox($key, 1, isset($page->data[$key]) ? $page->data[$key] : ($page->id && isset($field['value']) ? $field['value'] : 0), ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
    @endif
    @if(isset($field['label']))
        {!! isset($field['is_subsection']) && $field['is_subsection'] ? ($field['is_subsection'] == 1 ? '<h4>' : '<h5>') : '' !!} {!! Form::label(($field['type'] == 'multiple' ? $key.'[]' : $key), $field['label'], ['class' => 'label-class'.($field['type'] == 'checkbox' ? ' ml-3' : '').(isset($field['rules']) && $field['rules'] ? ' '.$field['rules'] : '' )]) !!} @if(isset($field['help'])) {!! isset($field['is_subsection']) && $field['is_subsection'] ? ($field['is_subsection'] == 1 ? '</h4>' : '</h5>') : '' !!} {!! add_help($field['help']) !!} @endif
    @endif
    @if(($field['type'] == 'choice' || $field['type'] == 'multiple') && isset($field['choices']))
        @foreach($field['choices'] as $value=>$choice)
            <div class="choice-wrapper">
                <input class="form-check-input ml-0 pr-4"
                    name="{{ $field['type'] == 'multiple' ? $key.'['.$value.']' : $key }}"
                    id="{{ $field['type'] == 'multiple' ? $key.'['.$value.']' : $key.'_'.$value }}"
                    type="{{ $field['type'] == 'multiple' ? 'checkbox' : 'radio' }}"
                    value="{{ $field['type'] == 'choice' ? $value : 1 }}"
                    {{ $field['type'] == 'multiple' ? (isset($page->data[$key][$value]) && $page->data[$key][$value] ? 'checked="checked"' : '') : (isset($page->data[$key]) && $page->data[$key] == $value ? 'checked="checked"' : '') }}
                >
                <label for="{{ $key }}[]" class="label-class ml-3">{{ $choice }}</label>
            </div>
        @endforeach
    @elseif($field['type'] != 'checkbox')
        @switch($field['type'])
            @case('text')
                {!! Form::text($key, isset($page->data[$key]) ? $page->data[$key] : $field['value'], ['class' => 'form-control']) !!}
            @break
            @case('textarea')
                {!! Form::textarea($key, isset($page->data[$key]) ? $page->data[$key] : $field['value'], ['class' => 'form-control wysiwyg']) !!}
            @break
            @case('number')
                {!! Form::number($key, isset($page->data[$key]) ? $page->data[$key] : $field['value'], ['class' => 'form-control']) !!}
            @break
            @default
                <input class="form-control" name="{{ $key }}" type="{{ $field['type'] }}" id="{{ $key }}" value="{{ isset($page->data[$key]) ? $page->data[$key] : $field['value'] }}">
        @endswitch
    @endif
</div>
