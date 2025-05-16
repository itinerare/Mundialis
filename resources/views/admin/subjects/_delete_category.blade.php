@if ($category)
    {!! Form::open(['action' => '/admin/data/categories/delete/' . $category->id]) !!}

    <p>You are about to delete the category <strong>{{ $category->name }}</strong>. This is not reversible. If pages
        or sub-categories in this category exist, you will not be able to delete this category. This will
        <strong>permanently</strong> delete any deleted pages in this category (they will not be recoverable).
    </p>
    <p>Are you sure you want to delete <strong>{{ $category->name }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Category', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid category selected.
@endif
