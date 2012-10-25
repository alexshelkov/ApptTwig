<?php
namespace ApptTwig\Extension;

use Twig_Function_Method;
use Twig_Extension;

use Zend\View\HelperPluginManager;

/**
 * Used to register Zend view helpers as Twig functions.
 *
 */
class ZendViewHelpers extends Twig_Extension
{
    /**
     * @var HelperPluginManager
     */
    protected $helpers;

    /**
     * @param HelperPluginManager $helpers
     */
    public function setHelpers($helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * @return HelperPluginManager
     */
    public function getHelpers()
    {
        return $this->helpers;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        $services = $this->getHelpers()->getRegisteredServices();

        $services = array_unique(array_merge(
            $services['instances'],
            $services['factories'],
            $services['invokableClasses'],
            $services['aliases']
        ));

        $functions = array();
        foreach ( $services  as $serviceName ) {
            // register ApptTwig function method as $serviceName
            $functions[$serviceName] =  new Twig_Function_Method($this, $serviceName);
        }

        return $functions;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ZendViewHelpers';
    }

    /**
     * When calling ZendViewHelpers (where * - is zend helper name) from complied Twig
     * template this function is executed in order to call Zend view helper.
     *
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        $service = $this->getHelpers()->get($name);

        return call_user_func_array(array($service, '__invoke'), $args);
    }
}