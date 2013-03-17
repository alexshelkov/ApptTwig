<?php
namespace ApptTwig;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;

use Zend\EventManager\EventInterface;

/**
 * ApptTwig module for ZF2.
 *
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface,
    BootstrapListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getTarget();

        $app->getEventManager()->attach('render', array($this, 'registerTwigStrategy'), 100);
    }

    /**
     * Register ApptTwig strategy.
     *
     * @param EventInterface $e
     */
    public function registerTwigStrategy(EventInterface $e)
    {
        $app = $e->getTarget();
        $locator = $app->getServiceManager();

        if ( $locator->has('View') ) {
            $view = $locator->get('View');
            $twigStrategy = $locator->get('appt.twig.renderer_strategy');

            $view->getEventManager()->attach($twigStrategy, 100);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'appt.twig.renderer' => 'ApptTwig\Service\TwigRendererFactory',
                'appt.twig.renderer_strategy' => 'ApptTwig\Service\TwigRendererStrategyFactory',
                'appt.twig.resolver' => 'ApptTwig\Service\TwigResolverFactory',
                'appt.twig.extension_manager' => 'ApptTwig\Service\ExtensionManagerFactory',
            )
        );
    }
}
