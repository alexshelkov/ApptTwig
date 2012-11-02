<?php
namespace ApptTwig\Service\Extension;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ApptTwig\ExtensionPluginManager;
use ApptTwig\Service\Exception\InvalidArgumentException;

use ApptTwig\Extension\ZendViewHelpers;

/**
 * Create ZendViewHelpers extension.
 *
 */
class ZendViewHelpersFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ( ! $serviceLocator instanceof ExtensionPluginManager ) {
            throw new InvalidArgumentException(
                'Expect ApptTwig\ExtensionPluginManager as Service Locator got ' .get_class($serviceLocator)
            );
        }

        $helpers = new ZendViewHelpers();

        $helpers->setHelpers($serviceLocator->getServiceLocator()->get('ViewHelperManager'));

        return $helpers;
    }
}