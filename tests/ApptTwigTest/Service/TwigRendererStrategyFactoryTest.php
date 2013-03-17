<?php
namespace ApptTwigTest\Service;

use PHPUnit_Framework_TestCase;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use ApptTwig\Service\TwigRendererStrategyFactory;

use Zend\View\HelperPluginManager;

class TwigRendererStrategyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCanCreateStrategy()
    {
        $factory = new TwigRendererStrategyFactory();

        $sm = new ServiceManager(new Config(array(
            'factories' => array (
                'ViewHelperManager' => function() {
                    return new HelperPluginManager;
                },
                'Config' => function() {
                    return array(
                    );
                },
                'appt.twig.renderer' => 'ApptTwig\Service\TwigRendererFactory',
                'appt.twig.resolver' => 'ApptTwig\Service\TwigResolverFactory',
                'appt.twig.extension_manager' => 'ApptTwig\Service\ExtensionManagerFactory',
            )
        )));

        $strategy = $factory->createService($sm);

        $this->assertInstanceOf('ApptTwig\TwigRendererStrategy', $strategy);
    }
}