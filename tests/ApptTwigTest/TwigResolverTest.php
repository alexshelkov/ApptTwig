<?php
namespace ApptTwigTest;

use PHPUnit_Framework_TestCase;

use ApptTwig\TwigResolver;
use Zend\View\Resolver\TemplatePathStack;

class TwigResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TwigResolver
     */
    protected $resolver;

    public function setUp()
    {
        $resolver = new TwigResolver();

        $stack = new TemplatePathStack();;
        $stack->setDefaultSuffix('twig');
        $stack->addPath(__DIR__ . '/_templates');

        $resolver->attach($stack);

        $this->resolver = $resolver;
    }

    public function testResolverUnknownTemplate()
    {
        $this->setExpectedException('Twig_Error_Loader', "Unable to find template \"unknown\"");
        $this->resolver->getSource('unknown');
    }

    public function testResolverGetSource()
    {
        $this->assertRegExp('#\s*Empty view\s*#s', $this->resolver->getSource('empty'));
    }

    public function testResolverGetCacheKey()
    {
        $key = __DIR__ . '/_templates/empty.twig';
        $this->assertSame($key, $this->resolver->getCacheKey('empty'));
    }

    public function testResolverIsFresh()
    {
        $this->assertTrue($this->resolver->isFresh('empty', time()));
    }
}