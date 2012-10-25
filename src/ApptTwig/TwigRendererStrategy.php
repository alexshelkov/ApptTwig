<?php
namespace ApptTwig;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\View\ViewEvent;
use ApptTwig\TwigRenderer;

/**
 * Select Twig view renderer.
 *
 */
class TwigRendererStrategy implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @var TwigRenderer
     */
    protected $renderer;

    /**
     * Constructor
     *
     * @param  TwigRenderer $renderer
     */
    public function __construct(TwigRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Retrieve the composed renderer
     *
     * @return TwigRenderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, array($this, 'selectRenderer'), $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, array($this, 'injectResponse'), $priority);
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Select renderer.
     *
     * @param  ViewEvent $e
     * @return TwigRenderer|null
     */
    public function selectRenderer(ViewEvent $e)
    {
        if ( $this->getRenderer()->resolver($e->getModel()->getTemplate()) ) {
            return $this->getRenderer();
        }
        return null;
    }

    /**
     * Populate the response object from the View
     *
     * Populates the content of the response object from the view rendering
     * results.
     *
     * @param ViewEvent $e
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function injectResponse(ViewEvent $e)
    {
        $renderer = $e->getRenderer();
        if ( $renderer !== $this->renderer ) {
            return;
        }

        $result   = $e->getResult();
        $response = $e->getResponse();

        $response->setContent($result);
    }
}