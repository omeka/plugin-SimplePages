<?php
$head = array('body_class' => 'simple-pages primary', 
                   'title' => 'Simple Pages | Browse');
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<p id="add-page" class="add-button"><a class="add" href="<?php echo uri('simple-pages/index/add'); ?>">Add a Page</a></p>
<div id="primary">
    <?php echo flash(); ?>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Created By</th>
                <th>Published?</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($pages as $page): ?>
            <tr>
                <td><?php if ($page->published): ?>
                <a href="<?php echo public_uri($page->slug); ?>"><?php echo $page->title; ?></a>
                <?php else: ?>
                <?php echo $page->title; ?>
                <?php endif; ?></td>
                <td><?php echo $page->slug; ?></td>
                <td><?php echo $page->getCreatedByUser()->username; ?></td>
                <td><?php echo $page->published ? 'published' : 'not published'; ?></td>
                <td><a class="edit" href="<?php echo uri("simple-pages/index/edit/id/$page->id") ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php foot(); ?>