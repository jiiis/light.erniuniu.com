<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Roster\Controller\Roster' => 'Roster\Controller\RosterController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'roster' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/roster[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Roster\Controller\Roster',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'roster' => __DIR__ . '/../view',
        ),
    ),
);