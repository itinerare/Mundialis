@if ($page)
    <p>
        Here you can {{ $relationship->id ? 'edit' : 'create' }} a link with another
        {{ strtolower($page->category->subject['term']) }}. This link is bidirectional and can be edited from either
        side.
        @if ($page->category->subject['key'] == 'people')
            For family members, it is recommended to only specify immediate family or non-familial (e.g. friendly)
            relationships with extended family, as the site can surmise for instance that Person A and Person B are
            cousins from existing family links. Moreover, some relationships are two-part, such as parent/child or
            mentor/mentee relationships; the expectation is that for these kinds of relationships, generally the types
            used for the link will be each part of the 'pair'.
        @endif
    </p>

    {!! Form::open([
        'url' => $relationship->id
            ? 'pages/' . $page->id . '/relationships/edit/' . $relationship->id
            : 'pages/' . $page->id . '/relationships/create',
    ]) !!}

    <div class="form-group">
        {!! Form::label(
            !$relationship->id || $relationship->page_one_id == $page->id ? 'page_two_id' : 'page_one_id',
            'Other ' . $page->category->subject['term'],
        ) !!}
        {!! Form::select(
            !$relationship->id || $relationship->page_one_id == $page->id ? 'page_two_id' : 'page_one_id',
            $pageOptions,
            $relationship->page_one_id == $page->id ? $relationship->page_two_id : $relationship->page_one_id,
            ['class' => 'form-control selectize', 'placeholder' => 'Select a ' . $page->category->subject['term']],
        ) !!}
        {!! Form::hidden(
            $relationship->id && $relationship->page_one_id != $page->id ? 'page_two_id' : 'page_one_id',
            $relationship->id
                ? ($relationship->page_one_id != $page->id
                    ? $relationship->page_two_id
                    : $relationship->page_one_id)
                : $page->id,
        ) !!}
    </div>

    <hr />

    <p>This side of the link is about this {{ strtolower($page->category->subject['term']) }}'s relationship with the
        other {{ strtolower($page->category->subject['term']) }}.</p>

    <div class="form-group">
        {!! Form::label(
            $relationship->page_one_id != $page->id && $relationship->id ? 'type_two' : 'type_one',
            'Relationship Type',
        ) !!}
        {!! Form::select(
            $relationship->page_one_id != $page->id && $relationship->id ? 'type_two' : 'type_one',
            $relationshipOptions,
            $relationship->id
                ? ($relationship->page_one_id != $page->id
                    ? $relationship->type_two
                    : $relationship->type_one)
                : null,
            ['class' => 'form-control selectize', 'placeholder' => 'Select a Type'],
        ) !!}
    </div>

    <div class="form-group">
        {!! Form::label(
            $relationship->page_one_id != $page->id && $relationship->id ? 'type_two_info' : 'type_one_info',
            'Info (Semi-Optional)',
        ) !!} {!! add_help(
            'Any additional specifications for the relationship. If \'custom\' is selected avove, this field is <strong>required</strong>.',
        ) !!}
        {!! Form::text(
            $relationship->page_one_id != $page->id && $relationship->id ? 'type_two_info' : 'type_one_info',
            $relationship->page_one_id != $page->id ? $relationship->type_two_info : $relationship->type_one_info,
            ['class' => 'form-control'],
        ) !!}
    </div>

    <div class="form-group">
        {!! Form::label(
            $relationship->page_one_id != $page->id && $relationship->id ? 'details_two' : 'details_one',
            'Details (Optional)',
        ) !!}
        {!! Form::textarea(
            $relationship->page_one_id != $page->id && $relationship->id ? 'details_two' : 'details_one',
            $relationship->page_one_id != $page->id ? $relationship->details_two : $relationship->details_one,
            ['class' => 'form-control'],
        ) !!}
    </div>

    <hr />

    <p>This side of the link is about the other {{ strtolower($page->category->subject['term']) }}'s relationship with
        this {{ strtolower($page->category->subject['term']) }}.</p>

    <div class="form-group">
        {!! Form::label(
            $relationship->page_one_id == $page->id || !$relationship->id ? 'type_two' : 'type_one',
            'Relationship Type',
        ) !!}
        {!! Form::select(
            $relationship->page_one_id == $page->id || !$relationship->id ? 'type_two' : 'type_one',
            $relationshipOptions,
            $relationship->id
                ? ($relationship->page_one_id == $page->id
                    ? $relationship->type_two
                    : $relationship->type_one)
                : null,
            ['class' => 'form-control selectize', 'placeholder' => 'Select a Type'],
        ) !!}
    </div>

    <div class="form-group">
        {!! Form::label(
            $relationship->page_one_id == $page->id || !$relationship->id ? 'type_two_info' : 'type_one_info',
            'Info (Semi-Optional)',
        ) !!} {!! add_help(
            'Any additional specifications for the relationship. If \'custom\' is selected avove, this field is <strong>required</strong>.',
        ) !!}
        {!! Form::text(
            $relationship->page_one_id == $page->id || !$relationship->id ? 'type_two_info' : 'type_one_info',
            $relationship->page_one_id == $page->id ? $relationship->type_two_info : $relationship->type_one_info,
            ['class' => 'form-control'],
        ) !!}
    </div>

    <div class="form-group">
        {!! Form::label(
            $relationship->page_one_id == $page->id || !$relationship->id ? 'details_two' : 'details_one',
            'Details (Optional)',
        ) !!}
        {!! Form::textarea(
            $relationship->page_one_id == $page->id || !$relationship->id ? 'details_two' : 'details_one',
            $relationship->page_one_id == $page->id ? $relationship->details_two : $relationship->details_one,
            ['class' => 'form-control'],
        ) !!}
    </div>

    <div class="text-right">
        {!! Form::submit($relationship->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

    <script>
        $(document).ready(function() {
            $(".selectize").selectize();
        });
    </script>
@else
    Invalid page selected.
@endif
