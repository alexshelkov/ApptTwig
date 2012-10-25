<?php
namespace ApptTwig;

use Twig_LoaderInterface;
use Twig_Error_Loader;

use Zend\View\Resolver\AggregateResolver;

/**
 * Resolve template and used as loader for Twig.
 *
 */
class TwigResolver extends AggregateResolver implements Twig_LoaderInterface
{
    /**
     * Gets the source code of a template, given its name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The template source code
     */
    public function getSource($name)
    {
        return file_get_contents($this->findTemplate($name));
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        return $this->findTemplate($name);
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     *
     * @return bool
     */
    public function isFresh($name, $time)
    {
        return filemtime($this->findTemplate($name)) <= $time;
    }

    /**
     * Use resolver to find template.
     *
     * @param $name
     *
     * @return string
     * @throws Twig_Error_Loader
     */
    protected function findTemplate($name)
    {
        if ( $template = $this->resolve($name) ) {
            return $template;
        }

        throw new Twig_Error_Loader(sprintf('Unable to find template "%s".', $name));
    }
}

