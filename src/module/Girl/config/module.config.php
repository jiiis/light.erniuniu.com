<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Girl\Controller\Girl' => 'Girl\Controller\GirlController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'girl' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/girl[/:action][/:id][/:param0][/:param1]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Girl\Controller\Girl',
                        'action'     => 'index',
                    ),
                ),
            ),
            'profile' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/profile[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Girl\Controller',
                        'controller' => 'Profile',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'girl' => __DIR__ . '/../view',
        ),
    ),
);