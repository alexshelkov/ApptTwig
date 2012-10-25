<?php
namespace ApptTwig\Service\Extension;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ApptTwig\Extension\ZendViewHelpers;

/**
 * Create ZendViewHelpers extension.
 *
 */
class ZendViewHelpersFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $helpers = new ZendViewHelpers();

        $helpers->setHelpers($serviceLocator->getServiceLocator()->get('ViewHelperManager'));

        return $helpers;
    }
}