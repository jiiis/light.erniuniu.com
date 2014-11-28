<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Comment\Controller\Comment' => 'Comment\Controller\CommentController',
            'Comment\Form\CommentForm' => 'Comment\Form\CommentForm',
        ),
    ),


    'router' => array(
        'routes' => array(
            'comment' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/comment[/:action][/:id][/:param0]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Comment\Controller\Comment',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'comment' => __DIR__ . '/../view',
        ),
    ),
);