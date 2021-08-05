<script>
    $(document).ready(function() {
        var $timeCategoryGridButton = $('.time-category-grid-view-button');
        var $timeCategoryGridView = $('#timeCategoryGridView');
        var $timeCategoryListButton = $('.time-category-list-view-button');
        var $timeCategoryListView = $('#timeCategoryListView');

        var timeCategoryView = null;

        initTimeCategoryView();

        $timeCategoryGridButton.on('click', function(e) {
            e.preventDefault();
            setTimeCategoryView('timeCategoryGrid');
        });
        $timeCategoryListButton.on('click', function(e) {
            e.preventDefault();
            setTimeCategoryView('timeCategoryList');
        });

        function initTimeCategoryView()
        {
            timeCategoryView = window.localStorage.getItem('mundialis_time_category_view');
            if(!timeCategoryView) timeCategoryView = 'timeCategoryList';
            setTimeCategoryView(timeCategoryView);
            console.log(timeCategoryView);
        }

        function setTimeCategoryView(status)
        {
            timeCategoryView = status;

            if(timeCategoryView == 'timeCategoryGrid') {
                $timeCategoryGridView.removeClass('hide');
                $timeCategoryGridButton.addClass('active');
                $timeCategoryListView.addClass('hide');
                $timeCategoryListButton.removeClass('active');
                window.localStorage.setItem('mundialis_time_category_view', 'timeCategoryGrid');
            }
            else if (timeCategoryView == 'timeCategoryList') {
                $timeCategoryListView.removeClass('hide');
                $timeCategoryListButton.addClass('active');
                $timeCategoryGridView.addClass('hide');
                $timeCategoryGridButton.removeClass('active');
                window.localStorage.setItem('mundialis_time_category_view', 'timeCategoryList');
            }
        }

        $('.time-entry-item').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('timeuage/lexicon/entries') }}/" + $(this).data('id'), 'Entry Details');
        });
    });
</script>
