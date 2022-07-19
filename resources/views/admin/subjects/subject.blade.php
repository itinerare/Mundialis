@extends('admin.layout')

@section('admin-title')
    {{ $subject['name'] }}
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', $subject['name'] => 'admin/data/' . $subject['key']]) !!}

    <h1>{{ $subject['name'] }}</h1>

    @if (isset($subject['description']))
        <p>{{ $subject['description'] }}</p>
    @endif

    @if (isset($subject['segments']))
        <p>Keep in mind when constructing templates for this subject that it inherently includes the following special
            fields when creating or editing pages:</p>
        @foreach ($subject['segments'] as $segment => $data)
            <h5>{{ ucfirst($segment) }}:</h5>
            <ul>
                @foreach ($data as $key => $item)
                    <li>
                        {{ $item['name'] }}: {!! $item['description'] !!}
                    </li>
                @endforeach
            </ul>
        @endforeach
    @endif

    <div class="text-right mb-3">
        <a class="btn btn-primary" href="{{ url('admin/data/' . $subject['key'] . '/edit') }}"><i class="fas fa-edit"></i> Edit
            Template</a>
        <a class="btn btn-primary" href="{{ url('admin/data/' . $subject['key'] . '/create') }}"><i class="fas fa-plus"></i>
            Create New Category</a>
        @if (isset($subject['pages']))
            @foreach ($subject['pages'] as $key => $page)
                {!! $loop->first ? '<br/>' : '' !!}
                <a class="btn btn-primary mt-1"
                    href="{{ url('admin/data/' . $subject['key'] . '/' . $key) }}">{!! $page !!}</a>
            @endforeach
        @endif
    </div>


    <p>This is a list of categories that will be used to organize pages. Categories can also have their own template, which
        will be used for pages created within them.</p>

    @if (!count($categories))
        <p>No categories found.</p>
    @else
        <table class="table table-sm category-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Parent Category</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="sortable" class="sortable">
                @foreach ($categories as $category)
                    <tr class="sort-item" data-id="{{ $category->id }}">
                        <td>
                            <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                            {!! $category->name !!}
                        </td>
                        <td>
                            {!! $category->parent ? $category->parent->name : '-' !!}
                        </td>
                        <td>
                        <td class="text-right">
                            <a href="{{ url('admin/data/categories/edit/' . $category->id) }}"
                                class="btn btn-primary">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        <div class="mb-4">
            {!! Form::open(['url' => 'admin/data/' . $subject['key'] . '/sort']) !!}
            {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
            {!! Form::submit('Save Order', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    @endif

@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.handle').on('click', function(e) {
                e.preventDefault();
            });
            $("#sortable").sortable({
                items: '.sort-item',
                handle: ".handle",
                placeholder: "sortable-placeholder",
                stop: function(event, ui) {
                    $('#sortableOrder').val($(this).sortable("toArray", {
                        attribute: "data-id"
                    }));
                },
                create: function() {
                    $('#sortableOrder').val($(this).sortable("toArray", {
                        attribute: "data-id"
                    }));
                }
            });
            $("#sortable").disableSelection();
        });
    </script>
@endsection
