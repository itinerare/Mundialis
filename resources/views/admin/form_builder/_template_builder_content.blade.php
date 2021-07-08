<h2>Infobox</h2>

<p>Fields in this section will be used to build a page's infobox, which displays basic at-a-glance information about the subject of the page. Fields for this section should expect content to be brief.</p>

<div class="text-right mb-3">
    <a href="#" class="btn btn-outline-info" id="add-infobox">Add Field</a>
</div>
<div id="infoboxList">
    @if(isset($template->data['infobox']))
        @foreach($template->data['infobox'] as $key=>$field)
            @include('admin.form_builder._infobox_builder_entry', ['key' => $key, 'field' => $field])
        @endforeach
    @endif
</div>

<h2>Main Page</h2>

<p>To add fields, first add at least one section. Sections are overall headers for portions of a page. Widgets-- page components that display particular information, such as a timeline for events linked to the page-- can also be added to sections after they are created.</p>

<div class="text-right mb-3">
    <a href="#" class="btn btn-outline-info" id="add-section">Add Section</a>
</div>
<div id="sectionList">
    @if(isset($template->data['sections']))
        @foreach($template->data['sections'] as $key=>$name)
            @include('admin.form_builder._section_builder_entry', ['key' => $key, 'name' => $name])
        @endforeach
    @endif
</div>