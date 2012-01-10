<?php
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => html_escape(__('Simple Pages | Add Page')));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
    <?php echo flash(); ?>
    <form method="post">
        <?php include 'form.php'; ?>
        <?php echo $this->formSubmit('simple-pages-add-submit', 
                                     __('Add Page'), 
                                     array('id'    => 'simple-pages-add-submit', 
                                           'class' => 'submit submit-medium')); ?>
    </form>
</div>
<?php foot(); ?>
