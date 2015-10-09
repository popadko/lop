<?php

namespace Luminaire\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Luminaire\Bundle\IssueBundle\Entity\IssueResolution;

/**
 * Class LoadIssueResolutionData
 */
class LoadIssueResolutionData extends AbstractFixture
{
    /**
     * @var array
     */
    protected $priorities = [
        [
            'name'  => IssueResolution::RESOLUTION_FIXED,
            'order' => 1,
            'label' => 'Fixed'
        ],
        [
            'name'  => IssueResolution::RESOLUTION_WONT_FIX,
            'order' => 2,
            'label' => 'Won\'t Fix'
        ],
        [
            'name'  => IssueResolution::RESOLUTION_DUPLICATE,
            'order' => 3,
            'label' => 'Duplicate'
        ],
        [
            'name'  => IssueResolution::RESOLUTION_INCOMPLETE,
            'order' => 4,
            'label' => 'Incomplete'
        ],
        [
            'name'  => IssueResolution::RESOLUTION_CANNOT_REPRODUCE,
            'order' => 5,
            'label' => 'Cannot Reproduce'
        ],
        [
            'name'  => IssueResolution::RESOLUTION_DONE,
            'order' => 6,
            'label' => 'Done'
        ],
        [
            'name'  => IssueResolution::RESOLUTION_WONT_DO,
            'order' => 7,
            'label' => 'Won\'t Do'
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->priorities as $data) {
            $entity = new IssueResolution($data['name']);
            $entity->setLabel($data['label']);
            $entity->setOrder($data['order']);
            $manager->persist($entity);
            $this->addReference($data['name'] . '-issue-resolution', $entity);
        }
        $manager->flush();
    }
}
