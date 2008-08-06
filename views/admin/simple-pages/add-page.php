<?php head(array('title' => 'SimplePages', 'body_class' => 'simple-pages-plugin')); ?>

<div id="primary">
	
	<h1>SimplePages | Add Page</h1>
	<?php echo flash(); ?>

	<?php echo simple_pages_update_slug_javascript(); ?>
	

	<form id="simple-pages-form" style="float:left; width: 600px;" method="post">
		<div class="field">
			<label for="simple_pages_page_title">Title</label>
			<input type="simple_pages_page_title" class="textinput" name="simple_pages_page_title" id="simple_pages_page_title" value="" onkeyup="updateSlug();" />
		</div>

		<div class="field">
			<label for="simple_pages_page_slug">Slug</label>
			<input type="simple_pages_page_slug" class="textinput" name="simple_pages_page_slug" id="simple_pages_page_slug" value="" />
		</div>


		<fieldset>
			<?php echo textarea(array('id'=>'simple_pages_page_html','name'=>'simple_pages_page_html','rows'=>'20','cols'=>'80'), null, 'HTML'); ?>
		</fieldset>
		
		<fieldset>
			<?php echo textarea(array('id'=>'simple_pages_page_css','name'=>'simple_pages_page_css','rows'=>'20','cols'=>'80'), $page['css'], 'CSS'); ?>
		</fieldset>
	
		<div class="field">
			<?php
				echo checkbox(array('name'=>'simple_pages_page_is_published', 'id'=>'simple_pages_page_is_published'), TRUE, null, "Is Published" );
			?>			
		</div>
		
		<p id="submits">
		<?php echo submit('Save'); ?>
		</p>
			
	</form>
	
</div>


<?php foot(); ?>
