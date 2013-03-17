<?php
namespace ApptTwig;

use Zend\View\HelperPluginManager;
use Zend\View\Model\ModelInterface;
use Zend\View\Renderer\RendererInterface;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\Variables;
use Zend\View\Helper\AbstractHelper;

use Twig_Environment;
use Twig_LoaderInterface;

use ApptTwig\TwigResolver;

use ApptTwig\Exception\InvalidArgumentException;
use ApptTwig\Exception\DomainException;

/**
 * Used as render for Twig.
 *
 */
class TwigRenderer implements RendererInterface
{
    /**
     * @var Twig_Environment
     */
    protected $engine;

    /**
     * Options used when creating Twig environment.
     *
     * @var array
     */
    protected $engineOptions = array();

    /**
     * Template resolver.
     *
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * Helper plugin manager.
     *
     * @var HelperPluginManager
     */
    protected $helpers;

    /**
     * Loader used when creating Twig environment.
     *
     * @var Twig_LoaderInterface
     */
    protected $loader;

    /**
     * Return Twig engine.
     *
     * @return Twig_Environment
     */
    public function getEngine()
    {
        if ( ! $this->engine ) {
            $this->engine = new Twig_Environment($this->getLoader(), $this->getEngineOptions());
        }

        return $this->engine;
    }

    /**
     * Set options for twig engine (Twig environment).
     *
     * @param array $engineOptions
     */
    public function setEngineOptions($engineOptions)
    {
        $this->engineOptions = $engineOptions;
    }

    /**
     * Get engine options.
     *
     * @return array
     */
    public function getEngineOptions()
    {
        return $this->engineOptions;
    }

    /**
     * Set Twig loader.
     *
     * @param Twig_LoaderInterface $loader
     */
    public function setLoader(Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Returns loader for twig.
     *
     * Use resolver as default loader (if no loader was provided).
     *
     * @throws InvalidArgumentException
     * @return Twig_LoaderInterface
     */
    public function getLoader()
    {
        if ( is_null($this->loader) ) {
            if ( ($resolver = $this->resolver()) instanceof Twig_LoaderInterface ) {
                $this->setLoader($resolver);
            }
        }

        if ( ! $this->loader instanceof Twig_LoaderInterface ) {
            throw new InvalidArgumentException('Twig loader must be an instance of Twig_LoaderInterface');
        }

        return $this->loader;
    }

    /**
     * Set resolver.
     *
     * @param ResolverInterface $resolver
     * @return RendererInterface
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * Return resolver (use ApptTwig\TwigResolver as default resolver).
     *
     * If name provided resolve it to template.
     *
     * @param null|string $name
     * @return ResolverInterface|mixed
     */
    public function resolver($name = null)
    {
        if ( is_null($this->resolver) ) {
            $this->setResolver(new TwigResolver());
        }

        if ( ! is_null($name) ) {
            return $this->resolver->resolve($name, $this);
        }

        return $this->resolver;
    }

    /**
     * Set view helpers.
     *
     * @param $helpers
     * @throws InvalidArgumentException
     */
    public function setHelperPluginManager($helpers)
    {
        if ( is_string($helpers) ) {
            if ( ! class_exists($helpers) ) {
                throw new InvalidArgumentException(sprintf(
                    'Invalid helper helpers class provided (%s)',
                    $helpers
                ));
            }
            $helpers = new $helpers();
        }

        if ( ! $helpers instanceof HelperPluginManager ) {
            throw new InvalidArgumentException(sprintf(
                'Helper helpers must extend Zend\View\HelperPluginManager; got type "%s" instead',
                (is_object($helpers) ? get_class($helpers) : gettype($helpers))
            ));
        }

        $helpers->setRenderer($this);
        $this->helpers = $helpers;
    }

    /**
     * Get plugin instance
     *
     * @param  string     $name Name of plugin to return
     * @param  null|array $options Options to pass to plugin constructor (if not already instantiated)
     * @return AbstractHelper
     */
    public function plugin($name, array $options = null)
    {
        return $this->getHelperPluginManager()->get($name, $options);
    }

    /**
     * Overloading: proxy to helpers
     *
     * Proxies to the attached plugin manager to retrieve, return, and potentially
     * execute helpers.
     *
     * * If the helper does not define __invoke, it will be returned
     * * If the helper does define __invoke, it will be called as a functor
     *
     * @param  string $method
     * @param  array $argv
     *
     * @return mixed
     */
    public function __call($method, $argv)
    {
        $helper = $this->plugin($method);
        if ( is_callable($helper) ) {
            return call_user_func_array($helper, $argv);
        }

        return $helper;
    }

    /**
     * Return helper plugin manager.
     *
     * @return HelperPluginManager
     */
    public function getHelperPluginManager()
    {
        if ( is_null($this->helpers) ) {
            $this->setHelperPluginManager(new HelperPluginManager());
        }

        return $this->helpers;
    }

    /**
     * {@inheritDoc}
     */
    public function render($nameOrModel, $values = array())
    {
        if ( $nameOrModel instanceof ModelInterface ) {
            $template = $nameOrModel->getTemplate();
            if ( empty($template) ) {
                throw new DomainException(sprintf(
                    '%s: received View Model argument, but template is empty',
                    __METHOD__
                ));
            }

            // Give view model awareness via ViewModel helper
            $helper = $this->getHelperPluginManager()->get('view_model');
            $helper->setCurrent($nameOrModel);

            $values = $nameOrModel->getVariables();
        }

        if ( ! isset($template) ) {
            $template = $nameOrModel;
        }

        if ( ! $values instanceof Variables ) {
            $values = new Variables($values);
        }

        $content = $this->getEngine()->render($template, $values->getArrayCopy());

        return $content;
    }
}
