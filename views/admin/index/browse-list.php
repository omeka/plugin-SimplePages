<table class="full">
    <thead>
        <tr>
            <?php echo browse_headings(array(
                __('Title') => 'title',
                __('Slug') => 'slug',
                __('Last Modified') => 'updated'));
            ?>
        </tr>
    </thead>
    <tbody>
    <?php while(loop_simple_pages()): ?>
        <tr>
            <td>
                <span class="title">
                    <a href="<?php echo html_escape(public_uri(simple_page('slug'))); ?>"><?php echo html_escape(simple_page('title')) ; ?></a>
                    <?php if(!simple_page('is_published')): ?>
                        (<?php echo __('Not Published'); ?>)
                    <?php endif; ?>
                </span>
                <ul class="action-links group">
                    <li><a class="edit" href="<?php echo html_escape(uri("simple-pages/index/edit/id/" . simple_page('id'))) ?>"><?php echo __('Edit'); ?></a></li>
                    <li><a class="delete-confirm" href="<?php echo html_escape(uri("simple-pages/index/delete-confirm/id/" . simple_page('id'))) ?>"><?php echo __('Delete'); ?></a></li>
                </ul>
            </td>
            <td><?php echo html_escape(simple_page('slug')); ?></td>
            <td><?php echo __('<strong>%1$s</strong> on %2$s',
                html_escape(get_current_simple_page()->getModifiedByUser()->username),
                html_escape(format_date(simple_page('updated'), Zend_Date::DATETIME_MEDIUM))); ?>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
