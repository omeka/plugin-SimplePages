<h2><?php echo __("Appearance"); ?></h2>

<div class="field">
	<div class="two columns alpha">
		<?php echo $this->formLabel('simple_pages_hide_breadcrumbs', __('Hide breadcrumbs')); ?>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, hides breadcrumbs in all pages.'); ?>
		</p>
		<?php echo $this->formCheckbox('simple_pages_hide_breadcrumbs', get_option('simple_pages_hide_breadcrumbs'), null, array('1', '0')); ?>
	</div>
</div>
