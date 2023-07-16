<p>Create or edit template information here. <strong>Note that removing fields and/or sections from this template and/or
        changing their keys will hide them/inputted information from any pages using them, and will cause any inputted
        information to be deleted on the next edit to the page.</strong> Re-adding the field/section with the same key
    and information will cause it to reappear. Please also note that certain keys are used by the site for general page information and may not be used as field keys. These are:</p>
<ul>
    <li>title</li>
    <li>summary</li>
    <li>description</li>
</ul>

<h2>Infobox</h2>

<p>Fields in this section will be used to build a page's infobox, which displays basic at-a-glance information about the
    subject of the page. Fields for this section should expect content to be brief. Fields can also be reordered;
    however, note that moving fields does not cascade due to how change detection is handled.</p>

<div id="infoboxList" class="sortable">
    @if (isset($template->data['infobox']))
        @foreach ($template->data['infobox'] as $key => $field)
            @include('admin.form_builder._infobox_builder_entry', ['key' => $key, 'field' => $field])
        @endforeach
    @endif
</div>
<div class="text-right mb-3">
    <a href="#" class="btn btn-outline-info" id="add-infobox">Add Field</a>
</div>

<h2>Main Page</h2>

<p>To add fields, first add at least one section. Sections are overall headers for portions of a page. Note that
    <strong>changing a section's key will cause all fields for it to be deleted</strong>. Sections can also be
    reordered; however, note that moving sections does not cascade due to how change detection is handled.
</p>

<div id="sectionList" class="sortable">
    @if (isset($template->data['sections']))
        @foreach ($template->data['sections'] as $key => $section)
            @include('admin.form_builder._section_builder_entry', ['key' => $key, 'section' => $section])
        @endforeach
    @endif
</div>
<div class="text-right mb-3">
    <a href="#" class="btn btn-outline-info" id="add-section">Add Section</a>
</div>
