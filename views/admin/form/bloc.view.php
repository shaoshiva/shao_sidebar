<?
$bloc_index = (!empty($bloc_index) ? $bloc_index : '0');
//$datas = (!empty($bloc) ? $bloc->to_array() : array());
?>
<h2><em><?= _('Bloc') ?></em></h2>
<div class="bloc ui-state-default">
    <input type="hidden" name="bloc[<?= $bloc_index ?>][sibl_id]" value="<?= $bloc->sibl_id ?>" />
    <input type="hidden" name="bloc[<?= $bloc_index ?>][sibl_published]" value="<?= $bloc->sibl_published ?>" />
	<div class="bloc_title">
		<label>
			<input placeholder="<?= _('Title') ?>" type="text" name="bloc[<?= $bloc_index ?>][sibl_title]" value="<?= $bloc->sibl_title ?>" />
		</label>
	</div>
	<div class="wys">
		<?php
		echo \Nos\Renderer_Wysiwyg::renderer(array(
			'style' => 'width: 100%; height: 220px;',
			'name'	=> 'bloc['.$bloc_index.'][wysiwyg]',
			'value'	=> (!empty($bloc) ? $bloc->wysiwygs->content : ''),
			'renderer_options' => array(
				'mode' => 'exact',
			),
		));
		?>
	</div>
	<div class="options">
		<div class="bloc_class">
			<label>
				<?= _('CSS class property') ?> <input type="text" name="bloc[<?= $bloc_index ?>][sibl_class]" value="<?= $bloc->sibl_class ?>" />
			</label>
		</div>
	</div>
</div>
