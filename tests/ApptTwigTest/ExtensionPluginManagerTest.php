<?php
namespace ApptTwigTest;

use PHPUnit_Framework_TestCase;

use ApptTwig\TwigRenderer;
use ApptTwig\ExtensionPluginManager;
use Zend\ServiceManager\Config;


class ExtensionPluginManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TwigRenderer
     */
    protected $renderer;

    public function setUp()
    {
        $this->renderer = new TwigRenderer();
    }

    public function testGetUnknownExtension()
    {
        $this->setExpectedException('Twig_Error_Runtime');
        $this->renderer->getEngine()->getExtension('AppTwigTestExtension');
    }

    public function testAddBadExtension()
    {
        require_once '_stubs/Extension/ApptTwigTestBadExtension.php';

        $this->setExpectedException('ApptTwig\Extension\Exception\InvalidHelperException');

        $config = new Config(array(
            'invokables' => array(
                'ApptTwigTestBadExtension' => 'ApptTwigTest\Stub\Extension\ApptTwigTestBadExtension'
            )
        ));

        $manager = new ExtensionPluginManager($config);

        $manager->addExtensions($this->renderer);
    }

    public function testBadExtensionConfig()
    {
        $this->setExpectedException('ApptTwig\Extension\Exception\InvalidHelperException', 'Can\'t create extension with name');

        $config = new Config(array(
            'factories' => array(
                ''
            )
        ));

        $manager = new ExtensionPluginManager($config);

        $manager->addExtensions($this->renderer);
    }

    public function testAddExtensions()
    {
        require_once '_stubs/Extension/ApptTwigTestExtension.php';

        $config = new Config(array(
            'invokables' => array(
                'AppTwigTestExtension' => 'ApptTwigTest\Stub\Extension\ApptTwigTestExtension'
            )
        ));

        $manager = new ExtensionPluginManager($config);

        $manager->addExtensions($this->renderer);

        $this->assertNotNull($this->renderer->getEngine()->getExtension('AppTwigTestExtension'));
    }
}

