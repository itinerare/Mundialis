<script>
    $(document).ready(function() {
        var $langCategoryGridButton = $('.lang-category-grid-view-button');
        var $langCategoryGridView = $('#langCategoryGridView');
        var $langCategoryListButton = $('.lang-category-list-view-button');
        var $langCategoryListView = $('#langCategoryListView');

        var langCategoryView = null;

        initLangCategoryView();

        $langCategoryGridButton.on('click', function(e) {
            e.preventDefault();
            setLangCategoryView('langCategoryGrid');
        });
        $langCategoryListButton.on('click', function(e) {
            e.preventDefault();
            setLangCategoryView('langCategoryList');
        });

        function initLangCategoryView() {
            langCategoryView = window.localStorage.getItem('mundialis_lang_category_view');
            if (!langCategoryView) langCategoryView = 'langCategoryList';
            setLangCategoryView(langCategoryView);
            console.log(langCategoryView);
        }

        function setLangCategoryView(status) {
            langCategoryView = status;

            if (langCategoryView == 'langCategoryGrid') {
                $langCategoryGridView.removeClass('hide');
                $langCategoryGridButton.addClass('active');
                $langCategoryListView.addClass('hide');
                $langCategoryListButton.removeClass('active');
                window.localStorage.setItem('mundialis_lang_category_view', 'langCategoryGrid');
            } else if (langCategoryView == 'langCategoryList') {
                $langCategoryListView.removeClass('hide');
                $langCategoryListButton.addClass('active');
                $langCategoryGridView.addClass('hide');
                $langCategoryGridButton.removeClass('active');
                window.localStorage.setItem('mundialis_lang_category_view', 'langCategoryList');
            }
        }

        $('.lang-entry-item').on('click', function(e) {
            e.preventDefault();
            loadModal("{{ url('language/lexicon/entries') }}/" + $(this).data('id'), 'Entry Details');
        });
    });
</script>
