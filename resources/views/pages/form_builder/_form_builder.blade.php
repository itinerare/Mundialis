@if(isset($category->subject['segments']['general properties']) && View::exists('pages.form_builder._'.$category->subject['key'].'_general'))
    @include('pages.form_builder._'.$category->subject['key'].'_general', ['page' => $page])
@endif

<h2>Infobox</h2>
@if(isset($category->subject['segments']['infobox']) && View::exists('pages.form_builder._'.$category->subject['key'].'_infobox'))
    @include('pages.form_builder._'.$category->subject['key'].'_infobox', ['page' => $page])
@endif

@if(isset($category->template['infobox']))
    @foreach($category->template['infobox'] as $fieldKey=>$field)
        @include('pages.form_builder._field_builder', ['key' => $fieldKey, 'field' => $field])
    @endforeach
@else
    <p>No infobox fields have been added to this template.</p>
@endif

<h2>Sections</h2>
@if(isset($category->subject['segments']['sections']) && View::exists('pages.form_builder._'.$category->subject['key'].'_sections'))
    @include('pages.form_builder._'.$category->subject['key'].'_sections', ['page' => $page])
@endif

@if(isset($category->template['sections']))
    @foreach($category->template['sections'] as $sectionKey=>$section)
        <h3>{{ $section['name'] }}</h3>
        @if(isset($category->template['fields'][$sectionKey]))
            @foreach($category->template['fields'][$sectionKey] as $fieldKey=>$field)
                @include('pages.form_builder._field_builder', ['key' => $fieldKey, 'field' => $field])
            @endforeach
        @endif
    @endforeach
@else
    <p>No sections have been added to this template.</p>
@endif
