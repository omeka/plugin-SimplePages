jQuery(document).ready(function() {
    // Handle published / not published from any status.
    jQuery('.simplepage.toggle-status').click(function(event) {
        event.preventDefault();
        var id = jQuery(this).attr('id');
        var current = jQuery('#' + id);
        id = id.substr(id.lastIndexOf('-') + 1);
        var ajaxUrl = jQuery(this).attr('href') + '/simple-pages/ajax/update';
        jQuery(this).addClass('transmit');
        if (jQuery(this).hasClass('public')) {
            jQuery.post(ajaxUrl,
                {
                    status: 'private',
                    id: id
                },
                function(data) {
                    current.addClass('private');
                    current.removeClass('public');
                    current.removeClass('transmit');
                    if (current.text() != '') {
                        current.text(Omeka.messages.simplepages.private);
                    }
                }
            );
        } else {
            jQuery.post(ajaxUrl,
                {
                    status: 'public',
                    id: id
                },
                function(data) {
                    current.addClass('public');
                    current.removeClass('private');
                    current.removeClass('transmit');
                    if (current.text() != '') {
                        current.text(Omeka.messages.simplepages.public);
                    }
                }
            );
        }
    });

    // Handle deletion on list page.
    jQuery('.simplepage.delete-confirm').click(function(event) {
        event.preventDefault();
        if (!confirm(Omeka.messages.simplepages.confirmation)) {
            return;
        }
        var id = jQuery(this).attr('id');
        var current = jQuery('#' + id);
        id = id.substr(id.lastIndexOf('-') + 1);
        var row = jQuery(this).closest('tr');
        var ajaxUrl = jQuery(this).attr('href') + '/simple-pages/ajax/delete';
        jQuery(this).addClass('transmit');
        jQuery.post(ajaxUrl,
            {
                id: id
            },
            function(data) {
                current.removeClass('transmit');
                row.remove();
            }
        );
    });

    // Enable drag and drop sorting for elements on hierarchy page.
    jQuery('.sortable').nestedSortable({
        listType: 'ul',
        items: 'li.page',
        handle: '.sortable-item',
        revert: 200,
        forcePlaceholderSize: true,
        forceHelperSize: true,
        toleranceElement: '> div',
        placeholder: 'ui-sortable-highlight',
        containment: 'document',
        maxLevels: 9
    });

    // Handle deletion on hierarchy page.
    jQuery('#page-list .delete-element').click(function(event) {
        event.preventDefault();
        var header = jQuery(this).parent();
        if (jQuery(this).hasClass('delete-element')) {
            jQuery(this).removeClass('delete-element').addClass('undo-delete');
            header.addClass('deleted');
        } else {
            jQuery(this).removeClass('undo-delete').addClass('delete-element');
            header.removeClass('deleted');
        }
    });

    // Handle changes on hierarchy page.
    jQuery('.update-pages').click(function(event) {
        event.preventDefault();
        var sortable = jQuery('.sortable');
        sortable.nestedSortable({disabled: true});
        var remove = jQuery('#page-list .deleted').parent()
            .map(function() {return parseInt(this.id.substr(this.id.lastIndexOf('_') + 1));})
            .get();
        var order = sortable.nestedSortable('toArray', {startDepthCount: 0});
        order = jQuery.map(order, function(value) {return {id: value.item_id, parent_id: value.parent_id};});
        var ajaxUrl = jQuery(this).attr('href') + '/simple-pages/ajax/change';
        jQuery('.update-pages').addClass('transmit red').removeClass('blue');
        jQuery.post(ajaxUrl,
            {
                remove: remove,
                order: order
            },
            function(data) {
                // TODO Rebuild instead of reload (simply delete removeds).
                location.reload(true);
            }
        );
    });
});
