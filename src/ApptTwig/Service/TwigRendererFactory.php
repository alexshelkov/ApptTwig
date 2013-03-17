<?php
namespace ApptTwig\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ApptTwig\TwigRenderer;
use ApptTwig\Service\Option\ApptTwig;

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

        $extensionPluginManager = $serviceLocator->get('appt.twig.extension_manager');
        $extensionPluginManager->addExtensions($twigRenderer);

        return $twigRenderer;
    }

}