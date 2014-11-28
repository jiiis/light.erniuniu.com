<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Notice\Controller\Notice' => 'Notice\Controller\NoticeController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'notice' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/notice[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Notice\Controller\Notice',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'notice' => __DIR__ . '/../view',
        ),
    ),
);