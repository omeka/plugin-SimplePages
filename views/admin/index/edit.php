<?php
$head = array('body_class' => 'simple-pages primary', 
              'title' => "Simple Pages | Edit &#8220;$page->title&#8221;");
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
    <?php echo flash(); ?>
    <p>This page was created by <strong><?php echo $page->getCreatedByUser()->username; ?></strong> 
    on <?php echo date('M j, Y g:ia', strtotime($page->inserted)); ?>, and last 
    modified by <strong><?php echo $page->getModifiedByUser()->username; ?></strong> 
    on <?php echo date('M j, Y g:ia', strtotime($page->updated)); ?></p>
    <form method="post">
        <?php include 'form.php'; ?>
        <?php echo $this->formSubmit('simple-pages-edit-submit', 
                                     'Edit Page', 
                                     array('id'    => 'simple-pages-edit-submit', 
                                           'class' => 'submit submit-medium')); ?>
        <p id="simple-pages-delete">
            <a class="delete" href="<?php echo uri("simple-pages/index/delete/id/$page->id"); ?>">Delete This Page</a>
        </p>
    </form>
</div>
<?php foot(); ?>
