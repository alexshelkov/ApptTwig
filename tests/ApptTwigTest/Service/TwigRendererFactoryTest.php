<?php
namespace ApptTwigTest\Service;

use PHPUnit_Framework_TestCase;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use ApptTwig\Service\TwigRendererFactory;

use Zend\View\HelperPluginManager;

use Zend\View\Model\ViewModel;

class TwigRendererFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCanCreateRenderer()
    {
        $factory = new TwigRendererFactory();

        $sm = new ServiceManager(new Config(array(
            'factories' => array (
                'appt.twig.resolver' => 'ApptTwig\Service\TwigResolverFactory',
                'ViewHelperManager' => function() {
                    return new HelperPluginManager;
                },
                'Config' => function() {
                    return array(
                    );
                }
            )
        )));

        $renderer = $factory->createService($sm);

        $this->assertInstanceOf('ApptTwig\TwigRenderer', $renderer);
    }

    public function testConfigTemplatePaths()
    {
        $factory = new TwigRendererFactory();

        $sm = new ServiceManager(new Config(array(
            'factories' => array (
                'appt.twig.resolver' => 'ApptTwig\Service\TwigResolverFactory',
                'ViewHelperManager' => function() {
                    return new HelperPluginManager;
                },
                'Config' => function() {
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

        $renderer = $factory->createService($sm);

        $model = new ViewModel();
        $model->setTemplate('empty');

        $content = $renderer->render($model);
        $this->assertContains('Empty view', $content);

        $model = new ViewModel();
        $model->setTemplate('test');
        $model->setVariable('bar', 'bar');

        $content = $renderer->render($model);
        $this->assertContains('foo bar baz', $content);
    }

    public function testConfigExtensionManager()
    {
        $factory = new TwigRendererFactory();

        $sm = new ServiceManager(new Config(array(
            'factories' => array (
                'appt.twig.resolver' => 'ApptTwig\Service\TwigResolverFactory',
                'ViewHelperManager' => function() {
                    return new HelperPluginManager;
                },
                'Config' => function() {
                    return array(
                        'appt' => array(
                            'twig' => array(
                                'engine_options' => array(
                                    'autoescape' => false
                                ),
                                'template_path_stack' => array(__DIR__ . '/../_templates'),
                                'extension_manager' => array(
                                    'factories' => array (
                                        'ZendViewHelpers' => 'ApptTwig\Service\Extension\ZendViewHelpersFactory'
                                    )
                                )
                            )
                        )
                    );
                }
            )
        )));

        $renderer = $factory->createService($sm);

        $this->assertContains('Zend view helpers &lt;br&gt', $renderer->render('zend_view_helpers'));
    }
}