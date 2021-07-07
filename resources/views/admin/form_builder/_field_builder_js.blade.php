<script>
    $(document).ready(function() {
        $('#infoboxList .infobox-list-entry').each(function(index) {
            attachFieldTypeListener($(this));
        });

        $('#add-infobox').on('click', function(e) {
            e.preventDefault();
            addInfoboxRow();
        });
        $('.remove-infobox').on('click', function(e) {
            e.preventDefault();
            removeInfoboxRow($(this));
        })
        function addInfoboxRow() {
            var $clone = $('#infobox-row').clone();
            $('#infoboxList').append($clone);
            $clone.removeClass('hide infobox-row');
            $clone.find('.remove-infobox').on('click', function(e) {
                e.preventDefault();
                removeInfoboxRow($(this));
            });
            attachFieldTypeListener($clone.find('.form-field-type'));
        }
        function removeInfoboxRow($trigger) {
            $trigger.parent().parent().remove();
        }

        $('#add-section').on('click', function(e) {
            e.preventDefault();
            addSectionRow();
        });
        $('.remove-section').on('click', function(e) {
            e.preventDefault();
            removeSectionRow($(this));
        })
        function addSectionRow() {
            var $clone = $('#section-row').clone();
            $('#sectionList').append($clone);
            $clone.removeClass('hide section-row');
            $clone.find('.remove-section').on('click', function(e) {
                e.preventDefault();
                removeSectionRow($(this));
            });
            attachListeners($clone);
        }
        function removeSectionRow($trigger) {
            $trigger.parent().parent().remove();
        }
        $('#sectionList .section-list-entry').each(function(index) {
            attachListeners($(this));
        });

        function attachListeners(node) {
            node.find('.add-field').on('click', function(e) {
                e.preventDefault();
                var $clone = $('#field-row').clone();
                $(this).parent().parent().find('.field-list').append($clone);
                $clone.removeClass('hide field-row');
                $clone.find('.remove-field').on('click', function(e) {
                    e.preventDefault();
                    removeFieldRow($(this));
                });
                $clone.find('.field-section').attr('value', $(this).attr("value"));
                attachFieldTypeListener($clone.find('.form-field-type'));
            });
        }

        $('.fieldList .field-list-entry').each(function(index) {
            attachFieldTypeListener($(this).find('.character-rewardable-type'));
        });
        function removeFieldRow($trigger) {
            $trigger.parent().parent().remove();
        }

        function attachFieldTypeListener(node) {
            node.on('change', function(e) {
                var val = $(this).val();
                var $cell = $(this).parent().parent().parent().find('.chooseOptions');

                $cell.children().addClass('hide');
                $cell.children().children().val(null);

                if (val == 'choice' || val == 'multiple'){
                    $cell.children('.choiceOptions').addClass('show');
                    $cell.children('.choiceOptions').removeClass('hide');
                }
            });
        }
    });
</script>
