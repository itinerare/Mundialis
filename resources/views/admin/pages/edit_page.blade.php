@extends('admin.layout')

@section('admin-title') Edit Text Page @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Text Pages' => 'admin/pages', 'Edit Page' => 'admin/pages/edit/'.$page->id]) !!}

<h1>
    Edit Text Page
</h1>

<div class="row">
    <div class="col-md-6 form-group">
        {!! Form::label('Title') !!}
        {!! Form::text('name', $page->title, ['class' => 'form-control', 'disabled']) !!}
    </div>
    <div class="col-md-6 form-group">
        {!! Form::label('Key') !!}
        {!! Form::text('key', $page->key, ['class' => 'form-control', 'disabled']) !!}
    </div>
</div>

{!! Form::open(['url' => 'admin/pages/edit/'.$page->id]) !!}

<div class="form-group">
    {!! Form::label('Content') !!}
    {!! Form::textarea('text', $page->text, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="text-right">
    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

@endsection
