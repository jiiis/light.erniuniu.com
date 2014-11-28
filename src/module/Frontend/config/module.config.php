<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Frontend\Controller\Frontend' => 'Frontend\Controller\FrontendController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'frontend' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/sex[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Frontend',
                        'action'     => 'warning',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'frontend' => __DIR__ . '/../view',
        ),
    ),
);