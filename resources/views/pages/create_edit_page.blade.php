@extends('pages.layout')

@section('pages-title')
    {{ $page->id ? 'Edit' : 'Create' }} {{ $category->subject['term'] }}
@endsection

@section('pages-content')
    {!! breadcrumbs(
        [
            $category->subject['name'] => $category->subject['key'],
            $category->name => $category->subject['key'] . '/categories/' . $category->id,
        ] +
            ($page->id ? [$page->title => $page->url] : []) + [
                ($page->id ? 'Edit' : 'Create') . ' ' . $category->subject['term'] => $page->id
                    ? 'pages/edit/' . $page->id
                    : 'pages/create/' . $category->id,
            ],
    ) !!}

    <h1>{{ $page->id ? 'Edit' : 'Create' }} {{ $category->subject['term'] }}
        @if ($page->id)
            <a href="#" class="btn btn-danger float-right delete-page-button">Delete
                {{ $category->subject['term'] }}</a>
        @endif
    </h1>

    @if ($page->protection && $page->protection->is_protected)
        <div class="alert alert-warning">
            You are editing a protected page. This page was protected by {!! $page->protection->user->displayName !!} at
            {!! format_date($page->protection->created_at) !!}{{ $page->protection->reason ? ' with the reason: ' . $page->protection->reason : '' }}.
        </div>
    @endif

    {!! Form::open(['url' => $page->id ? 'pages/' . $page->id . '/edit' : 'pages/create', 'id' => 'pageForm']) !!}

    <h2>Basic Information</h2>

    <div class="form-group">
        {!! Form::label('Title') !!}
        {!! Form::text('title', $page->id ? $page->title : str_replace('_', ' ', Request::get('title')), [
            'class' => 'form-control',
        ]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Summary (Optional)') !!} {!! add_help('A short summary of the page\'s contents. This will be displayed on the page index.') !!}
        {!! Form::text('summary', $page->summary, ['class' => 'form-control']) !!}
    </div>

    <p>When editing fields from this point on, wiki link syntax can be used to create links to other pages on this site, and
        in fact is encouraged; not only is it convenient, links created this way are tracked by the site for various
        purposes, not the least of which is tracking wanted pages (pages which have been linked to using this system but
        which do not yet exist). Links can be created like so, using the title with disambiguation for affected pages (e.g.
        <span class="alert-secondary">Link (Place)</span>):
    </p>

    <ul>
        <li>[[Page Title Here]]</li>
        <li>[[Page Title Here|Label/Text Used for Link Here]]</li>
    </ul>

    <div class="form-group">
        {!! Form::label('Introduction (Optional)') !!} {!! add_help(
            'The introduction is the first thing displayed on the page, before all other content (but beside the infobox). It\'s recommended to put a general overview of the page\'s contents here.',
        ) !!}
        {!! Form::textarea('description', isset($page->data['description']) ? $page->data['description'] : null, [
            'class' => 'form-control wysiwyg',
        ]) !!}
    </div>

    @if (!$page->id)
        {!! Form::hidden('category_id', $category->id, ['class' => 'form-control']) !!}
    @endif

    @if (isset($category->subject['segments']['general properties']) &&
            View::exists('pages.form_builder._' . $category->subject['key'] . '_general'))
        @include('pages.form_builder._' . $category->subject['key'] . '_general')
    @endif

    <h2>Infobox</h2>
    @if (isset($category->subject['segments']['infobox']) &&
            View::exists('pages.form_builder._' . $category->subject['key'] . '_infobox'))
        @include('pages.form_builder._' . $category->subject['key'] . '_infobox')
    @endif

    @if (isset($category->template['infobox']))
        @foreach ($category->template['infobox'] as $fieldKey => $field)
            @include('pages.form_builder._field_builder', ['key' => $fieldKey, 'field' => $field])
        @endforeach
    @else
        <p>No infobox fields have been added to this template.</p>
    @endif

    <h2>Sections</h2>
    @if (isset($category->subject['segments']['sections']) &&
            View::exists('pages.form_builder._' . $category->subject['key'] . '_sections'))
        @include('pages.form_builder._' . $category->subject['key'] . '_sections')
    @endif

    @if (isset($category->template['sections']))
        @foreach ($category->template['sections'] as $sectionKey => $section)
            <h3>{{ $section['name'] }}</h3>
            @if (isset($category->template['fields'][$sectionKey]))
                @foreach ($category->template['fields'][$sectionKey] as $fieldKey => $field)
                    @include('pages.form_builder._field_builder', ['key' => $fieldKey, 'field' => $field])
                @endforeach
            @endif
        @endforeach
    @else
        <p>No sections have been added to this template.</p>
    @endif

    <div class="form-group">
        {!! Form::checkbox('is_visible', 1, $page->id ? $page->is_visible : 1, [
            'class' => 'form-check-input',
            'data-toggle' => 'toggle',
        ]) !!}
        {!! Form::label('is_visible', 'Is Visible', ['class' => 'form-check-label ml-3']) !!} {!! add_help(
            'If this is turned off, visitors and regular users will not be able to see this page. Users with editor permissions will still be able to see it.',
        ) !!}
    </div>

    <h2>Tags</h2>

    <p>
        Tags are optional organizational tools that can be used to label and sort pages independent of categories. You can
        view a list of all pages with a given tag, and individual tags can be applied to pages in multiple different
        subjects.
    </p>

    <p>
        <strong>Navbox generation:</strong> Additionally, you may specify a tag with one of two prefixes: <span
            class="alert-secondary">Hub:</span> or <span class="alert-secondary">Context:</span> which will mark it as the
        "hub" page for a tag or for inclusion in the tag's navbox, respectively. Note that only one "hub" page can exist for
        a tag at a time. If any pages exist in a tag with one of these prefixes, pages with that tag will have the tag's
        navbox displayed on it. Otherwise, tags with these prefixes will be treated the same as the normal tag. Note that
        navboxes are organized by subject, category, and then subcategory; pages in sub-subcategories will not be listed in
        the navbox itself.
    </p>

    <div class="form-group">
        {!! Form::label('Tags (Optional)') !!} {!! add_help('Enter one or more tags.') !!}
        {!! Form::text('page_tag', null, [
            'class' => 'form-control tag-list',
            'multiple',
            'data-init-value' => $page->entryTags,
        ]) !!}
    </div>

    <div class="form-group">
        {{ Form::label('utility_tag', 'Maintenance Tags (Optional)') }} {!! add_help(
            'These help keep track of pages around the site that could use more work. Pages with these tags are added to respective maintenance reports for easy tracking.',
        ) !!}
        @foreach (collect(Config::get('mundialis.utility_tags'))->map(function ($tag, $key) {
                return $key = $tag['label'];
            })->toArray() as $key => $answer)
            <div class="choice-wrapper">
                <label>{{ Form::checkbox('utility_tag[]',$key,$page->utilityTags()->tagSearch($key)->first()? $key: null,['class' => 'mr-1']) }}
                    {{ $answer }}</label>
            </div>
        @endforeach
    </div>

    @if ($page->id)
        <div class="text-right">
            <a href="#" class="btn btn-primary" id="submitButton">Edit</a>
        </div>
    @else
        <div class="text-right">
            {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
        </div>
    @endif

    @if ($page->id)
        <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title h5 mb-0">Confirm Edit</span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Please provide some information about your edit before confirming it! This will be added to the
                            page's version history.</p>
                        <div class="form-group">
                            {!! Form::label('Reason (Optional)') !!} {!! add_help(
                                'A short summary of what was edited and why. Optional, but recommended for recordkeeping and communication purposes.',
                            ) !!}
                            {!! Form::text('reason', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('is_minor', 'Is Minor', ['class' => 'form-check-label ml-3']) !!} {!! add_help('Whether or not this edit to the page is minor.') !!}
                            {!! Form::checkbox('is_minor', 1, 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
                        </div>
                        <div class="text-right">
                            <a href="#" id="formSubmit" class="btn btn-primary">Confirm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {!! Form::close() !!}

@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.delete-page-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('pages') }}/{{ $page->id }}/delete", 'Delete Page');
            });

            $.ajax({
                url: "/get/tags",
                type: "GET",
                dataType: 'json',

                error: function() {
                    callback();
                },
                success: function(options) {
                    //console.log(options);
                    $('.tag-list').selectize({
                        plugins: ["restore_on_backspace", "remove_button"],
                        delimiter: ",",
                        valueField: 'tag',
                        labelField: 'tag',
                        searchField: 'tag',
                        persist: false,
                        create: true,
                        preload: true,
                        options: options,
                        onInitialize: function() {
                            var existingOptions = JSON.parse(this.$input.attr(
                                'data-init-value'));
                            var self = this;
                            if (Object.prototype.toString.call(existingOptions) ===
                                "[object Array]") {
                                existingOptions.forEach(function(existingOption) {
                                    self.addOption(existingOption);
                                    self.addItem(existingOption[self.settings
                                        .valueField]);
                                });
                            } else if (typeof existingOptions === 'object') {
                                self.addOption(existingOptions);
                                self.addItem(existingOptions[self.settings.valueField]);
                            }
                        }
                    });
                },
            });
        });
    </script>

    @if ($page->id)
        <script>
            $(document).ready(function() {
                $('#submitButton').on('click', function(e) {
                    e.preventDefault();
                    $('#confirmationModal').modal('show');
                });

                $('#formSubmit').on('click', function(e) {
                    e.preventDefault();
                    $('#pageForm').submit();
                });
            });
        </script>
    @endif
@endsection
