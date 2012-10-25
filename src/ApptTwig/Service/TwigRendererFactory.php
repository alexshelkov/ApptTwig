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
     * Returns default resolver for Twig.
     *
     * @param ApptTwig $options
     * @return TwigResolver
     */
    protected function getResolver(ApptTwig $options)
    {
        $resolver = new TwigResolver();

        $resolver->attach(new TemplateMapResolver($options->getTemplateMap()));

        $templatePathStack = new TemplatePathStack();
        $templatePathStack->setDefaultSuffix($options->getDefaultTemplateSuffix());
        $templatePathStack->addPaths($options->getTemplatePathStack());
        $resolver->attach($templatePathStack);

        return $resolver;
    }

    /**
     * Returns an options from config file.
     *
     * @param $config
     * @return ApptTwig
     */
    protected function getOptionsFromArray(array $config)
    {
        if ( isset($config['appt']['twig']) && is_array($config['appt']['twig']) ) {
            $config = $config['appt']['twig'];
        } else {
            $config = array();
        }

        $options = new ApptTwig($config);

        return $options;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return TwigRenderer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $options = $this->getOptionsFromArray($config);

        $twigRenderer = new TwigRenderer;

        $twigRenderer->setEngineOptions($options->getEngineOptions());

        $resolver = $this->getResolver($options);
        $twigRenderer->setResolver($resolver);

        $twigRenderer->setHelperPluginManager($serviceLocator->get('ViewHelperManager'));

        $extensionPluginManager = new ExtensionPluginManager(new Config($options->getExtensionManager()));
        $extensionPluginManager->setServiceLocator($serviceLocator);
        $extensionPluginManager->addExtensions($twigRenderer);

        return $twigRenderer;
    }

}