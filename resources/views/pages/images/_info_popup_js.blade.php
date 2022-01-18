<script>
    $(document).ready(function() {
        <?php
            if (isset($gallery) && !$gallery) {
                echo "var gallery = false;";
            } else {
                echo "var gallery = true;";
            }
        ?>
        $('.image-link').magnificPopup({
            type: 'ajax',
            gallery: {
                enabled: gallery
            },
            ajax: {
                settings: null,
                cursor: 'mfp-ajax-cur',
                tError: '<a href="%url%">The content</a> could not be loaded.'
            },
            callbacks: {
                parseAjax: function(mfpResponse) {
                    console.log('Ajax content loaded:', mfpResponse);
                },
                ajaxContentAdded: function() {
                    console.log(this.content);
                }
            }
        });
    });
</script>
