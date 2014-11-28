<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Rates\Controller\Rates' => 'Rates\Controller\RatesController',
            'Rates\Controller\RatesCat' => 'Rates\Controller\RatesCatController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'rates' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/rates[/:action][/:id][/:info][/:price]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
					//	'name'	=> '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Rates\Controller\Rates',
                        'action'     => 'index',
                    ),
                ),
            ),
            'ratescat' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/ratescat[/:action][/:id][/:name]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
					//	'name'	=> '*',
                    ),
                    'defaults' => array(
                        'controller' => 'Rates\Controller\RatesCat',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'rates' => __DIR__ . '/../view',
        ),
    ),
);