@extends('admin.layout')

@section('admin-title') {{ $subjectName }} - Template @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', $subjectName => 'admin/data/'.$subject, 'Template' => 'admin/data/'.$subject.'/edit']) !!}

<h1>Subject Template ({{ $subjectName }})</h1>

<p>This is the overall template that will be used for this subject's pages. Categories' templates can be further customized, but it's recommended to make smart use of this to minimize as much redundancy as possible. <strong>Note that removing fields from this template will hide them/inputted information from any pages using them, and will cause any inputted information to be deleted on the next edit to the page.</strong> Re-adding the field with the same key and information will cause it to reappear.</p>

{!! Form::open(['url' => 'admin/data/'.$subject.'/edit']) !!}

<h2>Infobox</h2>

<p>Fields in this section will be used to build a page's infobox, which displays basic at-a-glance information about the subject of the page.</p>

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

<p>To add fields, first add at least one section. Sections are overall headers for portions of a page.</p>

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

<div class="text-right">
    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

<div class="hide mb-2" id="infobox-row">
    @include('admin.form_builder._infobox_builder_row')
</div>

<div class="hide mb-2" id="section-row">
    @include('admin.form_builder._section_builder_row')
</div>

<div class="hide mb-2" id="field-row">
    @include('admin.form_builder._field_builder_row')
</div>

@endsection

@section('scripts')
@parent

@include('admin.form_builder._field_builder_js')

@endsection
