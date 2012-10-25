<?php
namespace ApptTwigTest;

use PHPUnit_Framework_TestCase;

use ApptTwig\TwigRendererStrategy;
use ApptTwig\TwigRenderer;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\ViewEvent;
use Zend\View\Model\ViewModel;

class TwigRendererStrategyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TwigRendererStrategy
     */
    protected $strategy;

    public function setUp()
    {
        $stack = new TemplatePathStack();;
        $stack->setDefaultSuffix('twig');
        $stack->addPath(__DIR__ . '/_templates');

        $renderer = new TwigRenderer();
        $renderer->resolver()->attach($stack);

        $strategy = new TwigRendererStrategy($renderer);

        $this->strategy = $strategy;
    }

    public function testStrategyReturnNullIfCantFindTemplate()
    {
        $event = new ViewEvent;

        $model = new ViewModel();
        $model->setTemplate('unknown');

        $event->setModel($model);

        $this->assertNull($this->strategy->selectRenderer($event));
    }

    public function testStrategyReturnRendererIfCanFindTemplate()
    {
        $event = new ViewEvent;

        $model = new ViewModel();
        $model->setTemplate('empty');

        $event->setModel($model);

        $this->assertSame($this->strategy->getRenderer(), $this->strategy->selectRenderer($event));
    }
}