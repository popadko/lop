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
            ['assignee', $this->getMock('Oro\Bundle\UserBundle\Entity\User')],
            ['reporter', $this->getMock('Oro\Bundle\UserBundle\Entity\User')],
            ['status', $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssueStatus')],
            ['type', $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssueType')],
            [
                'resolution',
                $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssueResolution')
            ],
            ['priority', $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssuePriority')],
            ['createdAt', new \DateTime()],
            ['updatedAt', new \DateTime()],
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
