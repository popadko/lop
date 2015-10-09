<?php

namespace Luminaire\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Luminaire\Bundle\IssueBundle\Entity\IssueStatus;

/**
 * Class LoadIssueStatusData
 */
class LoadIssueStatusData extends AbstractFixture
{
    /**
     * @var array
     */
    protected $priorities = [
        [
            'name'  => IssueStatus::STATUS_OPEN,
            'order' => 1,
            'label' => 'Open'
        ],
        [
            'name'  => IssueStatus::STATUS_REOPENED,
            'order' => 2,
            'label' => 'Reopened'
        ],
        [
            'name'  => IssueStatus::STATUS_IN_PROGRESS,
            'order' => 3,
            'label' => 'In progress'
        ],
        [
            'name'  => IssueStatus::STATUS_CLOSED,
            'order' => 4,
            'label' => 'Closed'
        ],
        [
            'name'  => IssueStatus::STATUS_RESOLVED,
            'order' => 5,
            'label' => 'Resolved'
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->priorities as $data) {
            $entity = new IssueStatus($data['name']);
            $entity->setLabel($data['label']);
            $entity->setOrder($data['order']);
            $manager->persist($entity);
            $this->addReference($data['name'] . '-issue-status', $entity);
        }
        $manager->flush();
    }
}
