<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Unit\Entity;

use Luminaire\Bundle\IssueBundle\Entity\IssueStatus;

class IssueStatusTest extends EntityTestCase
{
    /**
     * @var IssueStatus
     */
    private $entity;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->entity = new IssueStatus('test');
    }

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

    public function settersAndGettersDataProvider()
    {
        return [
            ['order', 10],
            ['label', 'Test'],
        ];
    }
}
