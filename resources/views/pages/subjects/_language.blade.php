<h2>Lexicon</h2>

<h3>Categories</h3>

<p>This is a list of all lexicon categories present on the site. Lexicon categories can contain both sub-categories and/or lexicon entries.</p>

@include('pages.subjects._lang_category_index_content', ['categories' => $langCategories])

<h3 class="mb-3">
    Entries
    @if(Auth::check() && Auth::user()->canWrite)
        <a href="{{ url('language/lexicon/create') }}" class="btn btn-secondary float-right"><i class="fas fa-plus"></i> Create New Entry</a>
    @endif
</h3>

@include('pages.subjects._lang_entry_index_content')
