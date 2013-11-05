<?
if (empty($sidebar->blocs)) {
	return ;
}
?>
<div class="sidebar" id="sidebar-<?= $sidebar->side_id ?>">
	<?php
	foreach ($sidebar->blocs as $bloc) {
		if (!empty($bloc->wysiwygs->content)) {
			?>
			<div class="panel">
				<?= \Nos\Nos::parse_wysiwyg($bloc->wysiwygs->content) ?>
			</div>
			<?
		}
	}
	?>
</div>
