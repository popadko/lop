<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Unit\Entity;

use Luminaire\Bundle\IssueBundle\Entity\Issue;

/**
 * Class IssueTest
 */
class IssueTest extends EntityTestCase
{
    /**
     * @var
     */
    private $entity;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->entity = new Issue();
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
            ['summary', 'summary'],
            ['description', 'description'],
            ['code', 'code'],
            ['assignee', $this->getMock('Oro\Bundle\UserBundle\Entity\User')],
            ['assignee', null],
            ['reporter', $this->getMock('Oro\Bundle\UserBundle\Entity\User')],
            ['status', $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssueStatus')],
            ['type', $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssueType')],
            [
                'resolution',
                $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssueResolution')
            ],
            ['resolution', null],
            ['priority', $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssuePriority')],
            ['createdAt', new \DateTime()],
            ['updatedAt', new \DateTime()],
        ];
    }

    /**
     * @dataProvider notNullableSettersProvider
     * @expectedException \PHPUnit_Framework_Error
     *
     * @param $property
     */
    public function testNullableSetters($property)
    {
        $setter = 'set' . ucfirst($property);
        $this->entity->$setter(null);
    }

    /**
     * @return array
     */
    public function notNullableSettersProvider()
    {
        return [
            ['reporter'],
            ['status'],
            ['type'],
            ['priority'],
        ];
    }

    /**
     * @param $originalClassName
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockWithDisabledConstructor($originalClassName)
    {
        return $this->getMockBuilder($originalClassName)->disableOriginalConstructor()->getMock();
    }
}
