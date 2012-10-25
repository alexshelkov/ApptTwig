<?php
namespace ApptTwig\Service\Option;

use Zend\Stdlib\AbstractOptions;

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
}