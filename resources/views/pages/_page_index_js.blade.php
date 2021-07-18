<script>
    $(document).ready(function() {
        var $pageGridButton = $('.page-grid-view-button');
        var $pageGridView = $('#pageGridView');
        var $pageListButton = $('.page-list-view-button');
        var $pageListView = $('#pageListView');

        var pageView = null;

        initPageView();

        $pageGridButton.on('click', function(e) {
            e.preventDefault();
            setPageView('pageGrid');
        });
        $pageListButton.on('click', function(e) {
            e.preventDefault();
            setPageView('pageList');
        });

        function initPageView()
        {
            pageView = window.localStorage.getItem('mundialis_page_view');
            if(!pageView) pageView = 'pageGrid';
            setPageView(pageView);
        }

        function setPageView(status)
        {
            pageView = status;

            if(pageView == 'pageGrid') {
                $pageGridView.removeClass('hide');
                $pageGridButton.addClass('active');
                $pageListView.addClass('hide');
                $pageListButton.removeClass('active');
                window.localStorage.setItem('mundialis_page_view', 'pageGrid');
            }
            else if (pageView == 'pageList') {
                $pageListView.removeClass('hide');
                $pageListButton.addClass('active');
                $pageGridView.addClass('hide');
                $pageGridButton.removeClass('active');
                window.localStorage.setItem('mundialis_page_view', 'pageList');
            }
        }
    });
</script>
