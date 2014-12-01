<?php
$currentView = isset($_GET['view']) && $_GET['view'] == 'hierarchy' ? 'hierarchy' : 'list';

queue_css_file('simple-pages', 'screen');
queue_js_file(array('vendor/jquery.nestedSortable', 'simple-pages'));

$head = array(
    'title' => html_escape(__('Simple Pages | Browse')),
    'bodyclass' => 'simple-pages primary',
    'content_class' => 'horizontal-nav',
);
echo head($head);
?>
<ul id="section-nav" class="navigation">
    <li<?php echo $currentView == 'list' ? ' class="current"' : ''; ?>>
        <a href="<?php echo html_escape(url('simple-pages/index/browse?view=list')); ?>"><?php echo __('List View'); ?></a>
    </li>
    <li<?php echo $currentView == 'hierarchy' ? ' class="current"' : ''; ?>>
        <a href="<?php echo html_escape(url('simple-pages/index/browse?view=hierarchy')); ?>"><?php echo __('Hierarchy View'); ?></a>
    </li>
</ul>
<?php echo flash(); ?>

<a class="add-page button small green" href="<?php echo html_escape(url('simple-pages/index/add')); ?>"><?php echo __('Add a Page'); ?></a>
<?php if ($currentView == 'hierarchy'): ?>
<a class="update-pages button small blue" href="<?php echo ADMIN_BASE_URL; ?>"><?php echo __('Update changes'); ?></a>
<?php endif; ?>

<?php if (!has_loop_records('simple_pages_page')): ?>
    <p><?php echo __('There are no simple pages.'); ?> <a href="<?php echo html_escape(url('simple-pages/index/add')); ?>"><?php echo __('Add a page.'); ?></a></p>
<?php else: ?>
    <?php if ($currentView == 'hierarchy'): ?>
        <?php echo $this->partial('index/browse-hierarchy.php', array('simplePages' => $simple_pages_pages)); ?>
    <?php else: ?>
        <?php echo $this->partial('index/browse-list.php', array('simplePages' => $simple_pages_pages)); ?>
    <?php endif; ?>
<a class="add-page button small green" href="<?php echo html_escape(url('simple-pages/index/add')); ?>"><?php echo __('Add a Page'); ?></a>
<?php if ($currentView == 'hierarchy'): ?>
<a class="update-pages button small blue" href="<?php echo ADMIN_BASE_URL; ?>"><?php echo __('Update changes'); ?></a>
<?php endif; ?>

<script type="text/javascript">
    Omeka.messages = jQuery.extend(Omeka.messages,
        {'simplepages':{
            'public':<?php echo json_encode(__('Published')); ?>,
            'private':<?php echo json_encode(__('Private')); ?>,
            'confirmation':<?php echo json_encode(__('Are your sure to remove this simple page?')); ?>
        }}
    );
</script>
<?php endif; ?>
<?php echo foot(); ?>
