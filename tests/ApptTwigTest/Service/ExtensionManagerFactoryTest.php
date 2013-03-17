<?php
namespace ApptTwigTest\Service;

use PHPUnit_Framework_TestCase;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use ApptTwig\Service\ExtensionManagerFactory;

use Zend\View\HelperPluginManager;

class ExtensionManagerTest extends PHPUnit_Framework_TestCase
{
    public function testCanExtensionManager()
    {
        $factory = new ExtensionManagerFactory();

        $sm = new ServiceManager(new Config(array(
        )));

        $manager = $factory->createService($sm);

        $this->assertInstanceOf('ApptTwig\ExtensionPluginManager', $manager);
    }

    public function testCanAddExtension()
    {
        $factory = new ExtensionManagerFactory();

        $sm = new ServiceManager(new Config(array(
            'factories' => array(
                'ViewHelperManager' => function () {
                    return new HelperPluginManager;
                },
                'Config' => function () {
                    return array(
                        'appt' => array(
                            'twig' => array(
                                'extension_manager' => array(
                                    'factories' => array(
                                        'ZendViewHelpers' => 'ApptTwig\Service\Extension\ZendViewHelpersFactory'
                                    )
                                )
                            )
                        )
                    );
                }
            )
        )));

        $manager = $factory->createService($sm);

        $this->assertInstanceOf('ApptTwig\Extension\ZendViewHelpers', $manager->get('ZendViewHelpers'));
    }

}