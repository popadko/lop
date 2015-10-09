<?php

namespace Luminaire\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Luminaire\Bundle\IssueBundle\Entity\IssuePriority;

/**
 * Class LoadIssuePriorityData
 */
class LoadIssuePriorityData extends AbstractFixture
{
    /**
     * @var array
     */
    protected $priorities = [
        [
            'name'  => IssuePriority::PRIORITY_BLOCKER,
            'order' => 1,
            'label' => 'Blocker'
        ],
        [
            'name'  => IssuePriority::PRIORITY_CRITICAL,
            'order' => 2,
            'label' => 'Critical'
        ],
        [
            'name'  => IssuePriority::PRIORITY_MAJOR,
            'order' => 3,
            'label' => 'Major'
        ],
        [
            'name'  => IssuePriority::PRIORITY_MINOR,
            'order' => 4,
            'label' => 'Minor'
        ],
        [
            'name'  => IssuePriority::PRIORITY_TRIVIAL,
            'order' => 5,
            'label' => 'Trivial'
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->priorities as $data) {
            $entity = new IssuePriority($data['name']);
            $entity->setLabel($data['label']);
            $entity->setOrder($data['order']);
            $manager->persist($entity);
            $this->addReference($data['name'] . '-issue-priority', $entity);
        }
        $manager->flush();
    }
}
