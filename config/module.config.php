<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'forgotpassword' => __DIR__ . '/../view',
        ),        
    ),
    'controllers' => array(
        'invokables' => array(
            'forgotpassword_forgot' => 'ForgotPassword\Controller\ForgotController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'child_routes' => array(
                    'forgotpassword' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/forgot-password',
                            'defaults' => array(
                                'controller' => 'forgotpassword_forgot',
                                'action'     => 'forgot',
                            ),
                        ),
                    ),
                    'resetpassword' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/reset-password/:userId/:token',
                            'defaults' => array(
                                'controller' => 'forgotpassword_forgot',
                                'action'     => 'reset',
                            ),
                            'constraints' => array(
                                'token' => '[A-F0-9]+',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
