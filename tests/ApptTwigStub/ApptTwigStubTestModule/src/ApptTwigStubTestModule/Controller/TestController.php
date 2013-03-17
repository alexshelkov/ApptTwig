<?php
namespace ApptTwigStubTestModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class TestController extends AbstractActionController
{
    public function testLayoutWithTemplateAction()
    {
        return array('bar' => 'baz');
    }

    public function testTemplateAction()
    {
        return array('baz' => 'bar');
    }

    public function testLayoutAction()
    {
    }

    public function testSuffixAction()
    {
    }

    public function testZendHelpersAction()
    {
    }
}