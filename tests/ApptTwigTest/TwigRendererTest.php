<?php
namespace ApptTwigTest;

use PHPUnit_Framework_TestCase;

use ApptTwig\TwigRenderer;
use ApptTwig\TwigResolver;
use Zend\View\Model\ViewModel;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\View\Resolver\TemplatePathStack;

class TwigRendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TwigRenderer
     */
    protected $renderer;

    public function setUp()
    {
        $this->renderer = new TwigRenderer();
    }

    public function testGetEngineIsTwigEnvironment()
    {
        $this->assertSame(get_class($this->renderer->getEngine()), 'Twig_Environment');
    }

    public function testSetEngineOptions()
    {
        $this->renderer->setEngineOptions(array(
            'auto_reload' => true,
            'debug' => true,
            'cache' => '/dir/cache'
        ));

        $twig = $this->renderer->getEngine();

        $this->assertTrue($twig->isDebug());
        $this->assertSame('/dir/cache', $twig->getCache());
        $this->assertTrue($twig->isAutoReload());
    }

    public function testUsesTwigResolverAsDefaultResolver()
    {
        $this->assertInstanceOf('ApptTwig\TwigResolver', $this->renderer->resolver());
    }

    public function testCanSetResolverInstance()
    {
        $resolver = new TwigResolver();
        $this->renderer->setResolver($resolver);
        $this->assertSame($resolver, $this->renderer->resolver());
    }

    public function testCanSetLoaderInstance()
    {
        $loader = new TwigResolver();
        $this->renderer->setLoader($loader);
        $this->assertSame($loader, $this->renderer->getLoader());
        $this->assertSame($loader, $this->renderer->getEngine()->getLoader());
    }

    public function testInvalidLoaderWhenTryingGetEngineThrowsException()
    {
        $this->setExpectedException('ApptTwig\Exception\InvalidArgumentException', 'must be an instance');
        $this->renderer->setResolver(new TemplatePathStack());
        $this->renderer->getEngine();
    }

    public function testDefaultLoaderIsResolver()
    {
        $resolver = new TwigResolver();
        $this->renderer->setResolver($resolver);

        $this->assertSame($resolver, $this->renderer->getLoader());
        $this->assertSame($resolver, $this->renderer->getEngine()->getLoader());
    }

    public function testPassingNameToResolverReturnsScriptName()
    {
        $resolver = new TemplatePathStack();;
        $resolver->setDefaultSuffix('twig');
        $resolver->addPath(__DIR__ . '/_templates');

        $this->renderer->resolver()->attach($resolver);

        $filename = $this->renderer->resolver('test.twig');
        $this->assertEquals(realpath(__DIR__ . '/_templates/test.twig'), $filename);
    }

    public function testUsesHelperPluginManagerByDefault()
    {
        $this->assertInstanceOf('Zend\View\HelperPluginManager', $this->renderer->getHelperPluginManager());
    }

    public function testPassingValidStringClassToSetHelperPluginManagerCreatesIt()
    {
        $this->renderer->setHelperPluginManager('Zend\View\HelperPluginManager');
        $this->assertInstanceOf('Zend\View\HelperPluginManager', $this->renderer->getHelperPluginManager());
    }

    public function testPassingInvalidStringClassToSetHelperPluginManagerThrowsException()
    {
        $this->setExpectedException('ApptTwig\Exception\InvalidArgumentException', 'helpers class provided');
        $this->renderer->setHelperPluginManager('__BadClassName__');
    }

    public function invalidPluginManagers()
    {
        return array(
            array(true),
            array(1),
            array(1.0),
            array(array('foo')),
            array(new \stdClass),
        );
    }

    /**
     * @dataProvider invalidPluginManagers
     */
    public function testPassingInvalidArgumentToSetHelperPluginManagerRaisesException($plugins)
    {
        $this->setExpectedException('ApptTwig\Exception\InvalidArgumentException', 'must extend');
        $this->renderer->setHelperPluginManager($plugins);
    }

    public function testInjectsSelfIntoHelperPluginManager()
    {
        $plugins = $this->renderer->getHelperPluginManager();
        $this->assertSame($this->renderer, $plugins->getRenderer());
    }

    public function testPluginShortCat()
    {
        $plugins = $this->renderer->getHelperPluginManager();
        $this->assertSame($this->renderer->plugin('escapeHtml'), $plugins->get('escapeHtml'));
    }

    /**
     * @group view-model
     */
    public function testCanRenderViewModel()
    {
        $resolver = new TemplateMapResolver(array(
            'empty' => __DIR__ . '/_templates/empty.twig',
        ));
        $this->renderer->resolver()->attach($resolver);

        $model = new ViewModel();
        $model->setTemplate('empty');

        $content = $this->renderer->render($model);
        $this->assertRegexp('/\s*Empty view\s*/s', $content);
    }

    /**
     * @group view-model
     */
    public function testViewModelWithoutTemplateRaisesException()
    {
        $model = new ViewModel();
        $this->setExpectedException('ApptTwig\Exception\DomainException');
        $this->renderer->render($model);
    }

    /**
     * @group view-model
     */
    public function testRendersViewModelWithVariablesSpecified()
    {
        $resolver = new TemplateMapResolver(array(
            'test' => __DIR__ . '/_templates/test.twig',
        ));
        $this->renderer->resolver()->attach($resolver);

        $model = new ViewModel();
        $model->setTemplate('test');
        $model->setVariable('bar', 'bar');

        $content = $this->renderer->render($model);
        $this->assertRegexp('/\s*foo bar baz\s*/s', $content);
    }

    /**
     * @group view-model
     */
    public function testRenderedViewModelIsRegisteredAsCurrentViewModel()
    {
        $resolver = new TemplateMapResolver(array(
            'empty' => __DIR__ . '/_templates/empty.twig',
        ));
        $this->renderer->resolver()->attach($resolver);

        $model = new ViewModel();
        $model->setTemplate('empty');

        $content = $this->renderer->render($model);
        $this->assertRegexp('/\s*Empty view\s*/s', $content);
        $helper  = $this->renderer->getHelperPluginManager()->get('view_model');
        $this->assertTrue($helper->hasCurrent());
        $this->assertSame($model, $helper->getCurrent());
    }

    public function testRendersUnknownTemplate()
    {
        $model = new ViewModel();
        $model->setTemplate('empty');

        $this->setExpectedException('Twig_Error_Loader', "Unable to find template \"{$model->getTemplate()}\"");

        $this->renderer->render($model);
        $this->renderer->render('empty');
    }

    public function testRendersViewByTemplate()
    {
        $resolver = new TemplateMapResolver(array(
            'test' => __DIR__ . '/_templates/test.twig',
        ));
        $this->renderer->resolver()->attach($resolver);

        $content = $this->renderer->render('test', array('bar' => 'bar'));
        $this->assertRegexp('/\s*foo bar baz\s*/s', $content);
    }

    public function testRendersExtendedTemplate()
    {
        $resolver = new TemplateMapResolver(array(
            'child' => __DIR__ . '/_templates/child.twig',
            'parent' => __DIR__ . '/_templates/parent.twig',
        ));
        $this->renderer->resolver()->attach($resolver);

        $model = new ViewModel();
        $model->setTemplate('child');

        $content = $this->renderer->render($model);

        $this->assertRegExp('#parent.twig\s*child.twig block test#s', $content);
    }
}

