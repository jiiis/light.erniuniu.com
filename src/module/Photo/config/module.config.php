<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Photo\Controller\Photo' => 'Photo\Controller\PhotoController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'photo' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/photo[/:action][/:id][/:param0]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'param0' => '[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Photo\Controller\Photo',
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
                        '__NAMESPACE__' => 'Photo\Controller',
                        'controller' => 'Profile',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'photo' => __DIR__ . '/../view',
        ),
    ),
);