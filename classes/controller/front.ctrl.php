<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Shao\Sidebar;

class Controller_Front extends \Nos\Controller_Front_Application
{
	public function action_display($args = array()) {

		$sidebar = Model_Sidebar::find(\Arr::get($args, 'side_id', 'first'));

		return \View::forge('shao_sidebar::front/sidebar', array(
			'sidebar'	=> $sidebar,
		), false);
	}
}
