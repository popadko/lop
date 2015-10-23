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
     * @dataProvider dateFieldsProvider
     *
     * @param string $property
     * @param mixed $value
     */
    public function testDateFieldsGet($property, $value)
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
    public function dateFieldsProvider()
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
            ['assignee', $this->getMock('Oro\Bundle\UserBundle\Entity\User')],
            ['assignee', null],
            ['reporter', $this->getMock('Oro\Bundle\UserBundle\Entity\User')],
            ['type', $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssueType')],
            [
                'resolution',
                $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssueResolution')
            ],
            ['resolution', null],
            ['priority', $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\IssuePriority')],
            ['parent', $this->getMockWithDisabledConstructor('Luminaire\Bundle\IssueBundle\Entity\Issue')],
            ['workflowItem', $this->getMockWithDisabledConstructor('Oro\Bundle\WorkflowBundle\Entity\WorkflowItem')],
            ['workflowStep', $this->getMockWithDisabledConstructor('Oro\Bundle\WorkflowBundle\Entity\WorkflowStep')],
        ];
    }

    /**
     * @dataProvider notNullableSettersProvider
     * @expectedException \PHPUnit_Framework_Error
     *
     * @param $property
     */
    public function testNotNullableSetters($property)
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
            ['type'],
            ['priority'],
        ];
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testNotNullAddCollaborator()
    {
        $this->entity->addCollaborator(null);
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testNotNullRemoveCollaborator()
    {
        $this->entity->removeCollaborator(null);
    }

    /**
     *
     */
    public function testAddChild()
    {
        $this->assertFalse(method_exists($this->entity, 'addChild'));
        $this->assertFalse(method_exists($this->entity, 'removeChild'));
    }

    public function testCollaborators()
    {
        $users = [
            $this->getMock('Oro\Bundle\UserBundle\Entity\User'),
            $this->getMock('Oro\Bundle\UserBundle\Entity\User'),
        ];
        foreach ($users as $user) {
            $this->assertSame($this->entity, $this->entity->addCollaborator($user));
        }
        $this->assertEquals(new ArrayCollection($users), $this->entity->getCollaborators());
        foreach ($users as $user) {
            $this->assertSame($this->entity, $this->entity->addCollaborator($user));
        }
        $this->assertEquals(new ArrayCollection($users), $this->entity->getCollaborators());
        $this->entity->removeCollaborator($users[0]);
        $this->assertCount(1, $this->entity->getCollaborators());
        $this->assertSame($users[1], $this->entity->getCollaborators()->first());
        $this->entity->removeCollaborator($users[1]);
        $this->assertEmpty($this->entity->getCollaborators());
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
