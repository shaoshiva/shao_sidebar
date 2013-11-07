<?php
namespace Shao\Sidebar;

class Controller_Admin_Sidebar_Crud extends \Nos\Controller_Admin_Crud
{
    public function save($item, $data) {
		$return = parent::save($item, $data);

		// Don't save blocs if sidebar not created
		if (empty($item->side_id)) {
			return $return;
		}

		// Save sidebar blocs
		$blocs = (array) \Arr::get($_REQUEST, 'bloc', false);
		foreach ($blocs as $bloc) {

			// Get model
			$id = \Arr::get($bloc, 'sibl_id', false);
			if ($id) {
				// Find by id
				$model = Model_Sidebarbloc::find($id);
			} else {
				// Create
				$model = Model_Sidebarbloc::forge();
			}

			// Set datas
			$model->set(array(
				'sibl_title'		=> \Arr::get($bloc, 'sibl_title', ''),
				'sibl_class'		=> \Arr::get($bloc, 'sibl_class', ''),
				'sibl_side_id'		=> $item->side_id,
				'sibl_published'	=> \Arr::get($bloc, 'sibl_published', ''),
			));

			// Set wysiwyg
			$model->wysiwygs->content = \Arr::get($bloc, 'wysiwyg', '');

			// Save bloc
			$model->save();
		}

		return $return;
	}

	public function action_delete_bloc($sibl_id) {

		// Get the block
		$model = Model_Sidebarbloc::find($sibl_id);
		if (!$model) {
			// Block not found
			\Response::json(array(
				'error' => __('Block not found'),
			));
		}

		// Delete
		$model->delete();

		\Response::json(array(
			'notify' => __('Block successfully deleted!'),
			'dispatchEvent' => array(
				'name' 		=> 'Shao\\Sidebar',
				'action' 	=> 'delete',
				'id'		=> $sibl_id,
			)
		));
	}
}
