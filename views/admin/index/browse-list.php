<p class="instructions"><?php
    echo ' ' . __('To publish or unpublish a page, click on the icon.');
    echo ' ' . __('Changes are immediate.');
?></p>
<table class="full">
    <thead>
        <tr>
            <?php echo browse_sort_links(array(
                __('Title') => 'title',
                __('Slug') => 'slug',
                __('Last Modified') => 'updated'), array('link_tag' => 'th scope="col"', 'list_tag' => ''));
            ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach (loop('simple_pages_pages') as $simplePage): ?>
        <tr>
            <td>
                <span class="title">
                    <a href="<?php echo html_escape(record_url('simple_pages_page')); ?>">
                        <?php echo metadata('simple_pages_page', 'title'); ?>
                    </a>
                </span>
                <ul class="action-links group">
                    <li><?php printf('<a href="%s" id="simplepage-%s" class="simplepage toggle-status status %s">%s</a>',
                        ADMIN_BASE_URL,
                       $simplePage->id,
                       ($simplePage->is_published ? 'public' : 'private'),
                       ($simplePage->is_published ? __('Published') : __('Private')));
                    ?></li>
                    <li><a class="edit" href="<?php echo html_escape(record_url('simple_pages_page', 'edit')); ?>">
                        <?php echo __('Edit'); ?>
                    </a></li>
                    <li><?php printf('<a href="%s" id="simplepage-delete-%s" class="simplepage delete-confirm">%s</a>',
                        ADMIN_BASE_URL, $simplePage->id, __('Delete'));
                    ?></li>
                </ul>
            </td>
            <td><?php echo metadata('simple_pages_page', 'slug'); ?></td>
            <td><?php echo __('<strong>%1$s</strong> on %2$s',
                metadata('simple_pages_page', 'modified_username'),
                html_escape(format_date(metadata('simple_pages_page', 'updated'), Zend_Date::DATETIME_SHORT))); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
