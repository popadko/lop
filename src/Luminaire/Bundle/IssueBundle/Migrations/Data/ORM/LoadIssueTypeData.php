<?php

namespace Luminaire\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Luminaire\Bundle\IssueBundle\Entity\IssueType;

/**
 * Class LoadIssueData
 */
class LoadIssueTypeData extends AbstractFixture
{
    /**
     * @var array
     */
    protected $priorities = [
        [
            'name'  => IssueType::TYPE_TASK,
            'order' => 1,
            'label' => 'Task'
        ],
        [
            'name'  => IssueType::TYPE_BUG,
            'order' => 2,
            'label' => 'Bug'
        ],
        [
            'name'  => IssueType::TYPE_STORY,
            'order' => 3,
            'label' => 'Story'
        ],
        [
            'name'  => IssueType::TYPE_SUBTASK,
            'order' => 4,
            'label' => 'Subtask'
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->priorities as $data) {
            $entity = new IssueType($data['name']);
            $entity->setLabel($data['label']);
            $entity->setOrder($data['order']);
            $manager->persist($entity);
            $this->addReference($data['name'] . '-issue-type', $entity);
        }
        $manager->flush();
    }
}
