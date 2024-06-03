<script>
    $(document).ready(function() {

        $(".select-page").selectize({
            maxItems: 10,
        });

        // Cropper ////////////////////////////////////////////////////////////////////////////////////

        var $useCropper = $('#useCropper');
        var $thumbnailCrop = $('#thumbnailCrop');
        var $thumbnailUpload = $('#thumbnailUpload');

        var useCropper = $useCropper.is(':checked');

        updateCropper();

        $useCropper.on('change', function(e) {
            useCropper = $useCropper.is(':checked');

            updateCropper();
        });

        function updateCropper() {
            if (useCropper) {
                $thumbnailUpload.addClass('hide');
                $thumbnailCrop.removeClass('hide');
            } else {
                $thumbnailCrop.addClass('hide');
                $thumbnailUpload.removeClass('hide');
            }
        }

        // Creators ///////////////////////////////////////////////////////////////////////////////////

        $('.selectize').selectize();

        $('.add-creator').on('click', function(e) {
            e.preventDefault();
            addCreatorRow($(this));
        });

        function addCreatorRow($trigger) {
            var $clone = $('.creator-row').clone();
            $('#creatorList').append($clone);
            $clone.removeClass('hide creator-row');
            $clone.addClass('d-flex');
            $clone.find('.add-creator').on('click', function(e) {
                e.preventDefault();
                addCreatorRow($(this));
            })
            $trigger.css({
                visibility: 'hidden'
            });
            $clone.find('.creator-select').selectize();
        }

        // Croppie ////////////////////////////////////////////////////////////////////////////////////

        var thumbnailWidth = {{ config('mundialis.settings.image_thumbnails.width') }};
        var thumbnailHeight = {{ config('mundialis.settings.image_thumbnails.height') }};
        var $cropper = $('#cropper');
        var c = null;
        var $x0 = $('#cropX0');
        var $y0 = $('#cropY0');
        var $x1 = $('#cropX1');
        var $y1 = $('#cropY1');
        var zoom = 0;

        @if (isset($useUploaded) && $useUploaded)
            // This is for modification of an existing image:
            c = new Croppie($cropper[0], {
                viewport: {
                    width: thumbnailWidth,
                    height: thumbnailHeight
                },
                boundary: {
                    width: thumbnailWidth + 100,
                    height: thumbnailHeight + 100
                },
                update: function() {
                    updateCropValues();
                }
            });
            c.bind({
                url: $cropper.data('url'),
                // points: [$x0.val(),$x1.val(),$y0.val(),$y1.val()], // this does not work
            }).then(function() {
                updateCropValues();
            });
            console.log(($x1.val() - $x0.val()) / thumbnailWidth);
        @else
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $cropper.attr('src', e.target.result);
                        c = new Croppie($cropper[0], {
                            viewport: {
                                width: thumbnailWidth,
                                height: thumbnailHeight
                            },
                            boundary: {
                                width: thumbnailWidth + 100,
                                height: thumbnailHeight + 100
                            },
                            update: function() {
                                updateCropValues();
                            }
                        });
                        console.log(c);
                        updateCropValues();
                        $('#cropSelect').addClass('hide');
                        $cropper.removeClass('hide');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#mainImage").change(function() {
                readURL(this);
            });
        @endif

        function updateCropValues() {
            var values = c.get();
            console.log(values);
            //console.log([$x0.val(),$x1.val(),$y0.val(),$y1.val()]);
            $x0.val(values.points[0]);
            $y0.val(values.points[1]);
            $x1.val(values.points[2]);
            $y1.val(values.points[3]);
        }

    });
</script>
