<?php
namespace ApptTwig\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Config;

use ApptTwig\TwigRenderer;

use ApptTwig\TwigResolver;

use Zend\View\Resolver\TemplatePathStack;
use Zend\View\Resolver\TemplateMapResolver;

use ApptTwig\Service\Option\ApptTwig;

use ApptTwig\ExtensionPluginManager;

/**
 * Create renderer.
 *
 */
class TwigRendererFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return TwigRenderer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = ApptTwig::init($serviceLocator);

        $twigRenderer = new TwigRenderer;

        $twigRenderer->setEngineOptions($options->getEngineOptions());

        $resolver = $serviceLocator->get('appt.twig.resolver');
        $twigRenderer->setResolver($resolver);

        $twigRenderer->setHelperPluginManager($serviceLocator->get('ViewHelperManager'));

        $extensionPluginManager = new ExtensionPluginManager(new Config($options->getExtensionManager()));
        $extensionPluginManager->setServiceLocator($serviceLocator);
        $extensionPluginManager->addExtensions($twigRenderer);

        return $twigRenderer;
    }

}