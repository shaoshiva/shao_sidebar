<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

Nos\I18n::current_dictionary('noviusos_slideshow::common');

?>
<div>
	<img style="display: block; float: left; width: 64px; height: 64px;" src="static/apps/shao_sidebar/img/64/icon.png">
	<h1 style="margin-left: 80px;"><?= strtr(__('Sidebar ‘{{title}}’'), array('{{title}}' => $sidebar->side_title)) ?></h1>
	<? foreach ($sidebar->blocs as $bloc) {?>
		<div style="margin: 10px 10px 10px 80px; padding: 10px; background: #eee;">
			<?= !empty($bloc->sibl_title) ? $bloc->sibl_title : '<em>Bloc</em>' ?>
		</div>
	<?php } ?>
</div>
