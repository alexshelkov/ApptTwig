<?php
namespace ApptTwigTest;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

use ApptTwigStubTestModule\Module;

class AuthenticationControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $config = include TEST_APPLICATION_CONFIG;
        $config['module_listener_options']['module_paths']['ApptTwigStubTestModule'] =
                'tests/ApptTwigStub/ApptTwigStubTestModule';

        $config['modules'][] = 'ApptTwigStubTestModule';

        $this->setApplicationConfig($config);
        $this->getApplication();

        Module::$config = array();

        parent::setUp();
    }

    public function testLayoutWithTemplate()
    {
        Module::$config = array(
            'template_map' => array(
                'layout/layout' => TEST_STUB . '/ApptTwigStubTestModule/view/appt-twig-stub-test-module/test/layout.twig'
            ),
            'template_path_stack' => array(
                TEST_STUB . '/ApptTwigStubTestModule/view/'
            )
        );

        $this->dispatch('testLayoutWithTemplate');

        $content = $this->getResponse()->getContent();

        $this->assertRegExp('#Twig layout.*testLayoutWithTemplate baz#s', $content);
    }

    public function testTemplate()
    {
        Module::$config = array(
            'template_path_stack' => array(
                TEST_STUB . '/ApptTwigStubTestModule/view/'
            )
        );

        $this->dispatch('testTemplate');

        $content = $this->getResponse()->getContent();

        $this->assertRegExp('#Php layout.*testTemplate bar#s', $content);
    }

    public function testLayout()
    {
        Module::$config = array(
            'template_map' => array(
                'layout/layout' => TEST_STUB . '/ApptTwigStubTestModule/view/appt-twig-stub-test-module/test/layout.twig'
            )
        );

        $this->dispatch('testLayout');

        $content = $this->getResponse()->getContent();

        $this->assertRegExp('#Twig layout.*testLayout#s', $content);
    }

    public function testSuffix()
    {
        Module::$config = array(
            'default_template_suffix' => 'twg',
            'template_path_stack' => array(
                TEST_STUB . '/ApptTwigStubTestModule/view/'
            )
        );

        $this->dispatch('testSuffix');

        $content = $this->getResponse()->getContent();

        $this->assertRegExp('#Php layout.*testSuffix#s', $content);
    }

    public function testZendHelpers()
    {
        Module::$config = array(
            'template_map' => array(
                'layout/layout' => TEST_STUB . '/ApptTwigStubTestModule/view/appt-twig-stub-test-module/test/layout.twig'
            ),
            'template_path_stack' => array(
                TEST_STUB . '/ApptTwigStubTestModule/view/'
            )
        );

        $this->dispatch('testZendHelpers');

        $content = $this->getResponse()->getContent();

        $this->assertRegExp('#Twig layout.*testZendHelpers.*<title>test</title>.*appt-twig-stub-test-module/test/test-zend-helpers#s', $content);
    }
}
