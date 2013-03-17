<?php
namespace ApptTwigTest\Service;

use PHPUnit_Framework_TestCase;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use ApptTwig\Service\TwigResolverFactory;

class TwigResolverFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCanCreateResolver()
    {
        $factory = new TwigResolverFactory();

        $sm = new ServiceManager(new Config(array(
        )));

        $resolver = $factory->createService($sm);

        $this->assertInstanceOf('ApptTwig\TwigResolver', $resolver);
    }

    public function testOptionForPathResolver()
    {
        $factory = new TwigResolverFactory();

        $sm = new ServiceManager(new Config(array(
            'factories' => array(
                'Config' => function () {
                    return array(
                        'appt' => array(
                            'twig' => array(
                                'template_path_stack' => array(__DIR__ . '/../_templates'),
                                'template_map' => array(
                                    'custom_test_name' => __DIR__ . '/../_templates/test.twig',
                                ),
                            )
                        )
                    );
                }
            )
        )));

        $resolver = $factory->createService($sm);

        $this->assertInstanceOf('ApptTwig\TwigResolver', $resolver);
    }
}