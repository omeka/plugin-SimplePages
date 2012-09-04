<?php
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => html_escape(__('Simple Pages | Add Page')));
head($head);
?>
<?php echo flash(); ?>
<form method="post">
    <div class="seven columns alpha">
        <?php include 'form.php'; ?>
    </div>
    <div id="save" class="three columns omega panel">
        <?php echo $this->formSubmit('simple-pages-add-submit', 
                                     __('Add Page'), 
                                     array('id'    => 'simple-pages-add-submit', 
                                           'class' => 'submit big green button')); ?>
    </div>
</form>
<?php foot(); ?>
