<?php
namespace ApptTwig\Service\Option;

use Zend\Stdlib\AbstractOptions;

use Zend\ServiceManager\ServiceLocatorInterface;

class ApptTwig extends AbstractOptions
{
    /**
     * @var array
     */
    protected $engineOptions = array();

    /**
     * @var array
     */
    protected $templateMap = array();

    /**
     * @var array
     */
    protected $templatePathStack = array();

    /**
     * @var string
     */
    protected $defaultTemplateSuffix = 'twig';

    /**
     * @var array
     */
    protected $extensionManager = array();

    /**
     * @param array $engineOptions
     */
    public function setEngineOptions($engineOptions)
    {
        $this->engineOptions = $engineOptions;
    }

    /**
     * @return array
     */
    public function getEngineOptions()
    {
        return $this->engineOptions;
    }

    /**
     * @param array $templateMap
     */
    public function setTemplateMap($templateMap)
    {
        $this->templateMap = $templateMap;
    }

    /**
     * @return array
     */
    public function getTemplateMap()
    {
        return $this->templateMap;
    }

    /**
     * @param array $templateStack
     */
    public function setTemplatePathStack($templateStack)
    {
        $this->templatePathStack = $templateStack;
    }

    /**
     * @return array
     */
    public function getTemplatePathStack()
    {
        return $this->templatePathStack;
    }

    /**
     * @param string $defaultTemplateSuffix
     */
    public function setDefaultTemplateSuffix($defaultTemplateSuffix)
    {
        $this->defaultTemplateSuffix = $defaultTemplateSuffix;
    }

    /**
     * @return string
     */
    public function getDefaultTemplateSuffix()
    {
        return $this->defaultTemplateSuffix;
    }

    /**
     * @param array $extensionManager
     */
    public function setExtensionManager($extensionManager)
    {
        $this->extensionManager = $extensionManager;
    }

    /**
     * @return array
     */
    public function getExtensionManager()
    {
        return $this->extensionManager;
    }

    /**
     * Create options using service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ApptTwig
     */
    static public function init(ServiceLocatorInterface $serviceLocator)
    {
        if ( ! $serviceLocator->has('Config') ) {
            return new ApptTwig(array());
        }

        $config = $serviceLocator->get('Config');

        if ( isset($config['appt']['twig']) && is_array($config['appt']['twig']) ) {
            $config = $config['appt']['twig'];
        } else {
            $config = array();
        }

        $options = new ApptTwig($config);

        return $options;
    }
}