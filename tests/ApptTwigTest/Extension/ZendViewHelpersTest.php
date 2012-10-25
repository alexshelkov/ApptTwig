<?php
namespace ApptTwigTest\Extension;

use PHPUnit_Framework_TestCase;

use Zend\ServiceManager\ServiceManager;
use ApptTwig\TwigRenderer;
use ApptTwig\ExtensionPluginManager;
use Zend\ServiceManager\Config;
use Zend\View\Resolver\TemplatePathStack;

use ApptTwig\Extension\ZendViewHelpers;
use Zend\View\HelperPluginManager;

class ZendViewHelpersTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TwigRenderer
     */
    protected $renderer;

    public function setUp()
    {
        $stack = new TemplatePathStack();;
        $stack->setDefaultSuffix('twig');
        $stack->addPath(__DIR__ . '/../_templates');

        $renderer = new TwigRenderer();
        $renderer->setEngineOptions(array('autoescape' => false));
        $renderer->resolver()->attach($stack);

        $this->renderer = $renderer;
    }

    public function testAddFunctionsAsHelpers()
    {
        $config = new Config(array(
            'factories' => array(
                'ZendViewHelpers' => function() {
                    $helpers = new HelperPluginManager();
                    $zendViewHelpers = new ZendViewHelpers;
                    $zendViewHelpers->setHelpers($helpers);

                    return $zendViewHelpers;
                },
            )
        ));

        $manager = new ExtensionPluginManager($config);

        $manager->addExtensions($this->renderer);

        $this->assertNotNull($this->renderer->getEngine()->getExtension('ZendViewHelpers'));

        $this->assertRegExp('#Zend view helpers &lt;br&gt;#s', $this->renderer->render('zend_view_helpers'));
    }

    public function testZendViewHelpersFactory()
    {
        $sm = new ServiceManager(new Config(array(
            'factories' => array (
                'ViewHelperManager' => function() {
                    return new HelperPluginManager;
                }
            )
        )));

        $manager = new ExtensionPluginManager(new Config(array(
            'factories' => array (
                'ZendViewHelpers' => 'ApptTwig\Service\Extension\ZendViewHelpersFactory'
            )
        )));
        $manager->setServiceLocator($sm);

        $manager->addExtensions($this->renderer);

        $this->assertNotNull($this->renderer->getEngine()->getExtension('ZendViewHelpers'));

        $this->assertRegExp('#Zend view helpers &lt;br&gt;#s', $this->renderer->render('zend_view_helpers'));
    }

}

