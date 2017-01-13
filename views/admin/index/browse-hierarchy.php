<?php
    $topPages = get_db()->getTable('SimplePagesPage')->findChildrenPages(0, false);
?>
<p class="instructions"><?php
    echo ' ' . __('To delete, reorder or nest pages, click and drag a page to the preferred location, then click the update button.');
    echo ' ' . __('To publish or unpublish a page, click on the icon (change is immediate).');
?></p>
<div id="page-hierarchy">
    <div id="pages-list-container">
        <ul id="page-list" class="sortable" href="<?php echo ADMIN_BASE_URL; ?>">
            <?php
                foreach($topPages as $page):
                    echo simple_pages_edit_page_list($page);
                endforeach;
            ?>
        </ul>
    </div>
</div>
