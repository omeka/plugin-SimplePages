<?php head(array('title' => 'SimplePages', 'body_class' => 'simple-pages-plugin')); ?>

<div id="primary">
	<h1>SimplePages | Edit Page</h1>
	<?php echo flash(); ?>

	<p><a href="<?php echo WEB_DIR; ?>/pages/browse">Browse Pages</a></p>
	<p><a href="<?php echo simple_pages_slug_url($page); ?>">View</a></p>
	
	<form id="simple-pages-form" style="float:left; width: 600px;" method="post">
			
		<div class="field">
			<label for="simple_pages_page_title">Title</label>
			<input type="simple_pages_page_title" class="textinput" name="simple_pages_page_title" id="simple_pages_page_title" value="<?php echo $page['title'] ?>" />
		</div>

		<div class="field">
				<label for="simple_pages_page_slug">Slug</label>
				<input type="simple_pages_page_slug" class="textinput" name="simple_pages_page_slug" id="simple_pages_page_slug" value="<?php echo $page['slug'] ?>" />
		</div>
			
		<fieldset>
			<?php textarea(array('id'=>'simple_pages_page_html','name'=>'simple_pages_page_html','rows'=>'20','cols'=>'80'), $page['html'], 'HTML'); ?>
		</fieldset>
		
			<fieldset>
				<?php textarea(array('id'=>'simple_pages_page_css','name'=>'simple_pages_page_css','rows'=>'20','cols'=>'80'), $page['css'], 'CSS'); ?>
			</fieldset>

		<div class="field">
			<?php
				checkbox(array('name'=>'simple_pages_page_is_published', 'id'=>'simple_pages_page_is_published'), $page['is_published'] ? TRUE : FALSE, null, "Is Published" );
			?>			
		</div>		
		
		<p id="submits">
		<?php submit('Save'); ?>
		<?php submit('Delete','delete'); ?>
		</p>
	</form>
	
</div>


<?php foot(); ?>
