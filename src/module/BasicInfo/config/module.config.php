<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'BasicInfo\Controller\BasicInfo' => 'BasicInfo\Controller\BasicInfoController',
            'BasicInfo\Controller\Section' => 'BasicInfo\Controller\SectionController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'shopinfo' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/shopinfo[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'BasicInfo\Controller\BasicInfo',
                        'action'     => 'index',
                    ),
                ),
            ),
            'section' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/section[/:action][/:id][/:param0]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'param0' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'BasicInfo\Controller\Section',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'basicinfo' => __DIR__ . '/../view',
            'section' => __DIR__ . '/../view',
        ),
    ),
);
