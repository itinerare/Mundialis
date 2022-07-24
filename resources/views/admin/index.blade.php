@extends('admin.layout')

@section('admin-title')
    Dashboard
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Home' => 'admin']) !!}

    <p>This is your site's admin panel. From here, you can edit the templates and categories used to construct editors and
        pages, as well as view maintenance reports and change site settings.</p>
@endsection
