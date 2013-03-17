<?php
namespace ApptTwig\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ApptTwig\TwigResolver;

use Zend\View\Resolver\TemplatePathStack;
use Zend\View\Resolver\TemplateMapResolver;

use ApptTwig\Service\Option\ApptTwig;

class TwigResolverFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = ApptTwig::init($serviceLocator);

        $resolver = new TwigResolver();

        if ( $templateMapOptions = $options->getTemplateMap() ) {
            $resolver->attach(new TemplateMapResolver($templateMapOptions));
        }

        if ( $templatePathStackOptions = $options->getTemplatePathStack() ) {
            $templatePathStack = new TemplatePathStack();
            $templatePathStack->setDefaultSuffix($options->getDefaultTemplateSuffix());
            $templatePathStack->addPaths($templatePathStackOptions);
            $resolver->attach($templatePathStack);
        }

        return $resolver;
    }
}