<div class="row">
    @if($page->image || isset($page->category->template['infobox']) || (isset($page->category->subject['segments']['infobox']) && View::exists('pages.content_builder._'.$page->category->subject['key'].'_infobox')))
        <div class="mobile-show col-lg-4 m-2">
            @include('pages.content_builder._infobox_builder')
        </div>
    @endif
    <div class="col-lg-12 col-md">
        <!-- INFOBOX -->
        @if($page->image || isset($page->category->template['infobox']) || (isset($page->category->subject['segments']['infobox']) && View::exists('pages.content_builder._'.$page->category->subject['key'].'_infobox')))
            <div class="float-right mobile-hide m-2 ml-4" style="width:25vw;">
                @include('pages.content_builder._infobox_builder')
            </div>
        @endif

        <!-- INTRO -->
        {!! isset($data['description']) ? $data['description'] : '' !!}

        @if(isset($page->category->subject['segments']['general properties']) && View::exists('pages.content_builder._'.$page->category->subject['key'].'_general'))
            @include('pages.content_builder._'.$page->category->subject['key'].'_general')
        @endif

        <!-- AUTO-TOC -->
        @if(isset($page->category->template['sections']) && count($page->category->template['sections']) >= 3)
            <div class="card mb-2" style="width: 25vh;">
                <div class="card-body">
                    <div class="row">
                        <div class="my-auto col mobile-hide">
                            <hr/>
                        </div>
                        <h5 class="text-center col-lg-auto mx-2 my-auto">Contents</h5>
                        <div class="my-auto col mobile-hide">
                            <hr/>
                        </div>
                    </div>
                    @foreach($page->category->template['sections'] as $sectionKey=>$section)
                        {{ $loop->iteration }}. <a href="#section-{{ $sectionKey }}">{{ $section['name'] }}</a><br/>
                        @if(isset($page->category->template['fields'][$sectionKey]))
                            @php $sectionLoop = $loop; @endphp
                            @php $iteration = 0; @endphp
                            @foreach($page->category->template['fields'][$sectionKey] as $fieldKey=>$field)
                                @switch($field['is_subsection'])
                                    @case(1)
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        {{ $sectionLoop->iteration.'.'.$loop->iteration.'.' }} <a href="#subsection-{{ $fieldKey }}">{{ $field['label'] }}</a><br/>
                                        @php $fieldLoop = $loop; @endphp
                                        @php $iteration = 0; @endphp
                                    @break
                                    @case(2)
                                        @php $iteration++; @endphp
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{ $sectionLoop->iteration.'.'.(isset($fieldLoop) ? $fieldLoop->iteration : 0).'.'.$iteration.'.' }} <a href="#subsection-{{ $fieldKey }}">{{ $field['label'] }}</a><br/>
                                    @break
                                @endswitch
                            @endforeach
                            @php $iteration = 0; @endphp
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- MAIN CONTENT -->
        @if(isset($page->category->subject['segments']['sections']) && View::exists('pages.content_builder._'.$category->subject['key'].'_sections'))
            @include('pages.content_builder._'.$page->category->subject['key'].'_sections')
        @endif

        @if(isset($page->category->template['sections']))
            @foreach($page->category->template['sections'] as $sectionKey=>$section)
            @php
                $length = 0;
                if(isset($page->category->template['fields'][$sectionKey])) {
                    foreach($page->category->template['fields'][$sectionKey] as $fieldKey=>$field) {
                        if(isset($data[$fieldKey]) && $field['type'] != 'checkbox') {
                            $length = $length += strlen($data[$fieldKey]);
                        }
                    }
                }
            @endphp
                <h2 id="section-{{ $sectionKey }}">
                    {{ $section['name'] }}
                    <a class="small collapse-toggle collapsed" href="#collapse-{{ $sectionKey }}" data-toggle="collapse">Show</a></h3>
                </h2>
                <div class="collapse {{ $length < 3000 ? 'show' : '' }}" id="collapse-{{ $sectionKey }}">
                    @if(isset($page->category->template['fields'][$sectionKey]))
                        @foreach($page->category->template['fields'][$sectionKey] as $fieldKey=>$field)
                            @include('pages.content_builder._body_builder', ['key' => $fieldKey, 'field' => $field])
                        @endforeach
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
