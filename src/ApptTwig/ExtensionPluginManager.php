<?php
namespace ApptTwig;

use Zend\ServiceManager\AbstractPluginManager;
use ApptTwig\TwigRenderer;
use Twig_ExtensionInterface;
use ApptTwig\Extension\Exception\InvalidHelperException;

/**
 * Allow manage Twig extensions.
 *
 */
class ExtensionPluginManager extends AbstractPluginManager
{
    /**
     * Add registered extensions to twig view render.
     *
     */
    public function addExtensions(TwigRenderer $renderer)
    {
        $extensions = $this->getRegisteredServices();

        $extensions = array_unique(array_merge(
            $extensions['instances'],
            $extensions['factories'],
            $extensions['invokableClasses'],
            $extensions['aliases']
        ));

        $twig = $renderer->getEngine();
        foreach ( $extensions as $extension ) {
            $twig->addExtension($this->get($extension));
        }
    }

    /**
     * Checks is registered service allowed as Twig extension.
     *
     * @param mixed $plugin
     * @throws Extension\Exception\InvalidHelperException
     */
    public function validatePlugin($plugin)
    {
        if ( $plugin instanceof Twig_ExtensionInterface ) {
            return;
        }

        throw new InvalidHelperException(sprintf(
            'Plugin of type %s is invalid; must implement Twig_ExtensionInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
