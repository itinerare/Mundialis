<h2>
    Chronology
    <a href="{{ url('time/timeline') }}" class="float-right btn btn-secondary ml-2">Timeline</a>
</h2>

<p>This is a list of all chronologies present on the site. Chronologies are used for organizing large spans of time and can contain both sub-chronologies and/or events.</p>

@include('pages.subjects._time_category_index_content', ['categories' => $timeCategories])
