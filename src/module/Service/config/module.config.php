<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Service\Controller\Service' => 'Service\Controller\ServiceController',
            'Service\Form\ServiceForm' => 'Service\Form\ServiceForm',
        ),
    ),


    'router' => array(
        'routes' => array(
            'service' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/service[/:action][/:id][/:order][/:content][/:contentplus]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Service\Controller\Service',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'service' => __DIR__ . '/../view',
        ),
    ),
);