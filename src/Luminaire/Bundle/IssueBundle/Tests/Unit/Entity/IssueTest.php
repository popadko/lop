<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\ClassUtils;
use Luminaire\Bundle\IssueBundle\Entity\Issue;

/**
 * Class IssueTest
 */
class IssueTest extends EntityTestCase
{
    /**
     * @var Issue
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
     *
     */
    public function testTaggableInterface()
    {
        $this->assertInstanceOf('Oro\Bundle\TagBundle\Entity\Taggable', $this->entity);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->entity->getTags());

        $this->assertNull($this->entity->getTaggableId());

        $ref = new \ReflectionProperty(ClassUtils::getClass($this->entity), 'id');
        $ref->setAccessible(true);
        $ref->setValue($this->entity, 1);

        $this->assertSame(1, $this->entity->getTaggableId());

        $newCollection = new ArrayCollection();
        $this->entity->setTags($newCollection);
        $this->assertSame($newCollection, $this->entity->getTags());
    }

    /**
     * @dataProvider gettersProvider
     *
     * @param string $property
     * @param mixed $value
     */
    public function testCreatedAtGet($property, $value)
    {
        $getter = 'get' . ucfirst($property);

        $this->assertNull($this->entity->$getter());
        $ref = new \ReflectionProperty(ClassUtils::getClass($this->entity), $property);
        $ref->setAccessible(true);
        $ref->setValue($this->entity, $value);

        $this->assertSame($value, $this->entity->$getter());
    }

    /**
     * @return array
     */
    public function gettersProvider()
    {
        return [
            ['createdAt', new \DateTime()],
            ['updatedAt', new \DateTime()],
        ];
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
