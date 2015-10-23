<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Unit\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Luminaire\Bundle\IssueBundle\DependencyInjection\LuminaireIssueExtension;

class LuminaireIssueExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LuminaireIssueExtension
     */
    private $extension;

    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new LuminaireIssueExtension();
    }

    public function testLoad()
    {
        $this->extension->load([], $this->container);
    }
}
