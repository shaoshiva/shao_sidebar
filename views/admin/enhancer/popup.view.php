<?php

Nos\I18n::current_dictionary('noviusos_slideshow::common');

?>
<p style="margin-bottom: 0.5em;">
    <label>
		<?= __('Select a sidebar:') ?>&nbsp;
		<?php
        if ( !empty($params['sidebars']) ) {
			?>
			<select name="side_id">
				<?php
				foreach ($params['sidebars'] as $sidebar) {
					?>
					<option <?= (\Fuel\Core\Input::get('side_id', 0) === $sidebar->side_id ? 'selected="selected"' : '') ?>
						value="<?= $sidebar->side_id ?>"><?= $sidebar->side_title ?></option>
					<?php
				}
            	?>
			</select>
			<?php
}
        ?>
    </label>
</p>