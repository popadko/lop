<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Unit\Entity;

use Luminaire\Bundle\IssueBundle\Entity\IssueResolution;

/**
 * Class IssueResolutionTest
 */
class IssueResolutionTest extends EntityTestCase
{
    /**
     * @var IssueResolution
     */
    private $entity;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->entity = new IssueResolution('test');
    }

    /**
     *
     */
    public function testGetName()
    {
        $this->assertEquals('test', $this->entity->getName());
    }

    /**
     * @dataProvider settersAndGettersDataProvider
     *
     * @param $property
     * @param $value
     */
    public function testSettersAndGetters($property, $value)
    {
        $this->abstractTestGettersAndSetters($this->entity, $property, $value);
    }

    /**
     * @return array
     */
    public function settersAndGettersDataProvider()
    {
        return [
            ['order', 10],
            ['label', 'Test'],
        ];
    }
}
