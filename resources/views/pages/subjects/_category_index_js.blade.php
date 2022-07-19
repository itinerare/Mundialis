<script>
    $(document).ready(function() {
        var $categoryGridButton = $('.category-grid-view-button');
        var $categoryGridView = $('#categoryGridView');
        var $categoryListButton = $('.category-list-view-button');
        var $categoryListView = $('#categoryListView');

        var categoryView = null;

        initCategoryView();

        $categoryGridButton.on('click', function(e) {
            e.preventDefault();
            setCategoryView('categoryGrid');
        });
        $categoryListButton.on('click', function(e) {
            e.preventDefault();
            setCategoryView('categoryList');
        });

        function initCategoryView() {
            categoryView = window.localStorage.getItem('mundialis_category_view');
            if (!categoryView) categoryView = 'categoryList';
            setCategoryView(categoryView);
        }

        function setCategoryView(status) {
            categoryView = status;

            if (categoryView == 'categoryGrid') {
                $categoryGridView.removeClass('hide');
                $categoryGridButton.addClass('active');
                $categoryListView.addClass('hide');
                $categoryListButton.removeClass('active');
                window.localStorage.setItem('mundialis_category_view', 'categoryGrid');
            } else if (categoryView == 'categoryList') {
                $categoryListView.removeClass('hide');
                $categoryListButton.addClass('active');
                $categoryGridView.addClass('hide');
                $categoryGridButton.removeClass('active');
                window.localStorage.setItem('mundialis_category_view', 'categoryList');
            }
        }
    });
</script>
