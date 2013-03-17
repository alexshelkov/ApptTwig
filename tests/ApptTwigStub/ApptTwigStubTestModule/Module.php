<?php
namespace ApptTwigStubTestModule;

class Module
{
    static public $config = array();

    public function getConfig()
    {
        return array(
            'appt' => array(
                'twig' => self::$config
            ),
            'router' => array(
                'routes' => array(
                    'testLayoutWithTemplate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'testLayoutWithTemplate',
                            'defaults' => array(
                                'controller' => 'ApptTwigStubTestModule\Controller\Test',
                                'action' => 'testLayoutWithTemplate',
                            )
                        ),
                    ),
                    'testTemplate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'testTemplate',
                            'defaults' => array(
                                'controller' => 'ApptTwigStubTestModule\Controller\Test',
                                'action' => 'testTemplate',
                            )
                        ),
                    ),
                    'testLayout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'testLayout',
                            'defaults' => array(
                                'controller' => 'ApptTwigStubTestModule\Controller\Test',
                                'action' => 'testLayout',
                            )
                        ),
                    ),
                )
            ),
            'view_manager' => array(
                'template_map' => array(
                    'layout/layout' => __DIR__ . '/view/appt-twig-stub-test-module/test/layout.phtml',
                ),
                'template_path_stack' => array(
                    __DIR__ . '/view/'
                ),
            ),
            'controllers' => array(
                'invokables' => array(
                    'ApptTwigStubTestModule\Controller\Test' => 'ApptTwigStubTestModule\Controller\TestController',
                ),
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}