<table>
    <thead>
        <tr>
            <th><?php echo __('Title'); ?></th>
            <th><?php echo __('Slug'); ?></th>
            <th><?php echo __('Last Modified By'); ?></th>
            <th><?php echo __('Published?'); ?></th>
            <th><?php echo __('Edit?'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php while(loop_simple_pages()): ?>
        <tr>
            <td><a href="<?php echo html_escape(public_uri(simple_page('slug'))); ?>"><?php echo html_escape(simple_page('title')) ; ?></a></td>
            <td><?php echo html_escape(simple_page('slug')); ?></td>
            <td><?php echo __('<strong>%1$s</strong> on %2$s',
                html_escape(get_current_simple_page()->getModifiedByUser()->username),
                html_escape(format_date(simple_page('updated'), Zend_Date::DATETIME_MEDIUM))); ?>
            </td>
            <td><?php echo (simple_page('is_published') ? __('Published') : __('Not Published')); ?></td>
            <td><a class="edit" href="<?php echo html_escape(uri("simple-pages/index/edit/id/" . simple_page('id'))) ?>"><?php echo __('Edit'); ?></a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
