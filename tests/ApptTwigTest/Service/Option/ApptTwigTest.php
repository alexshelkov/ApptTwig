<?php
namespace ApptTwigTest;

use PHPUnit_Framework_TestCase;
use ApptTwig\Service\Option\ApptTwig;

class ApptTwigTest extends PHPUnit_Framework_TestCase
{
    public function testEmptyOptions()
    {
        $options = new ApptTwig;

        $this->assertSame('twig', $options->getDefaultTemplateSuffix());
        $this->assertSame(array(), $options->getEngineOptions());
        $this->assertSame(array(), $options->getTemplateMap());
        $this->assertSame(array(), $options->getTemplatePathStack());
        $this->assertSame(array(), $options->getExtensionManager());
    }

    public function testSetOptionsFromArray()
    {
        $defaultTemplateSuffix = 'twg';
        $engine = array('debug' => true, 'cache' => '/dir');
        $templateMap = array('index' => '/dir/index.twg');
        $templatePathStack = array('/dir/templates');
        $extensionManager = array('invokables' => array(
            'ApptTwigTestBadExtension' => 'ApptTwigTest\Stub\Extension\ApptTwigTestBadExtension'
        ));

        $options = new ApptTwig(array(
            'default_template_suffix' => $defaultTemplateSuffix,
            'engine_options' => $engine,
            'template_map' => $templateMap,
            'template_path_stack' => $templatePathStack,
            'extension_manager' => $extensionManager
        ));

        $this->assertSame($defaultTemplateSuffix, $options->getDefaultTemplateSuffix());
        $this->assertSame($engine, $options->getEngineOptions());
        $this->assertSame($templateMap, $options->getTemplateMap());
        $this->assertSame($templatePathStack, $options->getTemplatePathStack());
        $this->assertSame($extensionManager, $options->getExtensionManager());
    }
}