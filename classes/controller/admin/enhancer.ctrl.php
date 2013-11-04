<?php

namespace Shao\Sidebar;

class Controller_Admin_Enhancer extends \Nos\Controller_Admin_Enhancer
{
	public function action_popup()
	{
		$options = array(
			'order_by' => array('side_title' => 'asc'),
		);
//		$nosContext = \Arr::get(\Input::get(), 'nosContext', null);
//		if (!empty($nosContext)) {
//			$options['where'] = array(array('context', $nosContext));
//		}

		$this->config['popup']['params']['sidebars'] = Model_Sidebar::find('all', $options);

		return parent::action_popup();
	}

	public function action_save(array $args = null)
	{
		$sidebar = Model_Sidebar::find(\Input::post('side_id', 'first'));

		if (empty($args)) {
			$args = $_POST;
		}

		$body = array(
			'config'  => $args,
			'preview' => \View::forge($this->config['preview']['view'], array(
				'sidebar'	=> $sidebar,
			), false)->render(),
		);
		\Response::json($body);
	}
}
