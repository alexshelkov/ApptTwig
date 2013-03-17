<?php
namespace ApptTwig\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ApptTwig\Service\Option\ApptTwig;

use Zend\ServiceManager\Config;
use ApptTwig\ExtensionPluginManager;

class ExtensionManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = ApptTwig::init($serviceLocator);

        $extensionPluginManager = new ExtensionPluginManager(new Config($options->getExtensionManager()));
        $extensionPluginManager->setServiceLocator($serviceLocator);

        return $extensionPluginManager;
    }
}