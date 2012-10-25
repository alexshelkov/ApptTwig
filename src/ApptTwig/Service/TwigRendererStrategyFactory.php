<?php
namespace ApptTwig\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ApptTwig\TwigRendererStrategy;

class TwigRendererStrategyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $viewRenderer = $serviceLocator->get('appt.twig.renderer');
        $viewStrategy = new TwigRendererStrategy($viewRenderer);

        return $viewStrategy;
    }
}