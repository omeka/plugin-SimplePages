<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Slug</th>
            <th>Last Modified By</th>
            <th>Published?</th>
            <th>Edit?</th>
        </tr>
    </thead>
    <tbody>
    <?php while(loop_simple_pages()): ?>
        <tr>
            <td><a href="<?php echo html_escape(public_uri(simple_page('slug'))); ?>"><?php echo html_escape(simple_page('title')) ; ?></a></td>
            <td><?php echo html_escape(simple_page('slug')); ?></td>
            <td><strong><?php echo html_escape(get_current_simple_page()->getModifiedByUser()->username); ?></strong> on <?php echo html_escape(date('M j, Y g:ia', strtotime(simple_page('updated')))); ?></td>
            <td><?php if (simple_page('is_published')): ?>
            Published
            <?php else: ?>
            Not Published
            <?php endif; ?></td>
            <td><a class="edit" href="<?php echo html_escape(uri("simple-pages/index/edit/id/" . simple_page('id'))) ?>">Edit</a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>