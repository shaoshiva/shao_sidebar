<?php

namespace Shao\Sidebar;

class Model_Sidebarbloc extends \Nos\Orm\Model
{

    protected static $_primary_key = array('sibl_id');
    protected static $_table_name = 'shao_sidebar_bloc';

    protected static $_properties = array(
        'sibl_id',
		'sibl_side_id',
		'sibl_title',
		'sibl_class',
		'sibl_sort',
		'sibl_published',
        'sibl_created_at',
        'sibl_updated_at',
    );


    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => true,
            'property'=>'sibl_created_at'
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_save'),
            'mysql_timestamp' => true,
            'property'=>'sibl_updated_at'
        )
    );

    protected static $_behaviours = array(
		'Nos\Orm_Behaviour_Sortable' => array(
			'events' => array('before_insert', 'before_save', 'after_save'),
			'sort_property' => 'sibl_sort',
		),
        /*
        'Nos\Orm_Behaviour_Publishable' => array(
            'publication_state_property' => 'sibl__publication_status',
            'publication_start_property' => 'sibl__publication_start',
            'publication_endproperty' => 'sibl__publication_end',
        ),
        */
        /*
        'Nos\Orm_Behaviour_Urlenhancer' => array(
            'enhancers' => array('shao_sidebar_sidebarbloc'),
        ),
        */
        /*
        'Nos\Orm_Behaviour_Virtualname' => array(
            'events' => array('before_save', 'after_save'),
            'virtual_name_property' => 'sibl_virtual_name',
        ),
        */
        /*
        'Nos\Orm_Behaviour_Twinnable' => array(
            'events' => array('before_insert', 'after_insert', 'before_save', 'after_delete', 'change_parent'),
            'context_property'      => 'sibl__context',
            'common_id_property' => 'sibl__context_common_id',
            'is_main_property' => 'sibl__context_is_main',
            'invariant_fields'   => array(),
        ),
        */
    );

    protected static $_belongs_to  = array(
        /*
        'key' => array( // key must be defined, relation will be loaded via $sidebarbloc->key
            'key_from' => 'sibl_...', // Column on this model
            'model_to' => 'Shao\Sidebar\Model_...', // Model to be defined
            'key_to' => '...', // column on the other model
            'cascade_save' => false,
            'cascade_delete' => false,
            //'conditions' => array('where' => ...)
        ),
        */
    );
    protected static $_has_many  = array(
        /*
        'key' => array( // key must be defined, relation will be loaded via $sidebarbloc->key
            'key_from' => 'sibl_...', // Column on this model
            'model_to' => 'Shao\Sidebar\Model_...', // Model to be defined
            'key_to' => '...', // column on the other model
            'cascade_save' => false,
            'cascade_delete' => false,
            //'conditions' => array('where' => ...)
        ),
        */
    );
    protected static $_many_many = array(
        /*
            'key' => array( // key must be defined, relation will be loaded via $sidebarbloc->key
                'table_through' => '...', // intermediary table must be defined
                'key_from' => 'sibl_...', // Column on this model
                'key_through_from' => '...', // Column "from" on the intermediary table
                'key_through_to' => '...', // Column "to" on the intermediary table
                'key_to' => '...', // Column on the other model
                'cascade_save' => false,
                'cascade_delete' => false,
                'model_to'       => 'Shao\Sidebar\Model_...', // Model to be defined
            ),
        */
    );
}
