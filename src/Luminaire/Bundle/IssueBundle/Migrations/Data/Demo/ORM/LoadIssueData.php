<?php

namespace Luminaire\Bundle\IssueBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Luminaire\Bundle\IssueBundle\Entity\Issue;
use Luminaire\Bundle\IssueBundle\Entity\IssuePriority;
use Luminaire\Bundle\IssueBundle\Entity\IssueResolution;
use Luminaire\Bundle\IssueBundle\Entity\IssueStatus;
use Luminaire\Bundle\IssueBundle\Entity\IssueType;

/**
 * Class LoadIssueData
 */
class LoadIssueData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * @var integer
     */
    protected $usersCount;

    /**
     * @var ObjectManager|\Doctrine\ORM\EntityManager
     */
    protected $manager;

    /**
     * @var array
     */
    protected $issues = [
        [
            'summary'     => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit',
            'description' =>
                'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula dolor. Aenean massa.',
            'assignee'    => true,
            'resolution'  => null,
            'type'        => IssueType::TYPE_TASK,
            'status'      => IssueStatus::STATUS_OPEN,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Aenean commodo ligula eget dolor',
            'description' =>
                'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
            'assignee'    => true,
            'resolution'  => null,
            'type'        => IssueType::TYPE_TASK,
            'status'      => IssueStatus::STATUS_IN_PROGRESS,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Aenean massa',
            'description' =>
                'Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.',
            'assignee'    => true,
            'resolution'  => null,
            'type'        => IssueType::TYPE_TASK,
            'status'      => IssueStatus::STATUS_REOPENED,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus',
            'description' =>
                'Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, ut, imperdiet.',
            'assignee'    => false,
            'resolution'  => null,
            'type'        => IssueType::TYPE_TASK,
            'status'      => IssueStatus::STATUS_CLOSED,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem',
            'description' =>
                'Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus semper nisi..',
            'assignee'    => true,
            'resolution'  => IssueResolution::RESOLUTION_DONE,
            'type'        => IssueType::TYPE_TASK,
            'status'      => IssueStatus::STATUS_RESOLVED,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Nulla consequat massa quis enim',
            'description' =>
                'Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, in, viverra,',
            'assignee'    => true,
            'resolution'  => null,
            'type'        => IssueType::TYPE_BUG,
            'status'      => IssueStatus::STATUS_OPEN,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu',
            'description' =>
                'Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam nisi vel.',
            'assignee'    => true,
            'resolution'  => null,
            'type'        => IssueType::TYPE_BUG,
            'status'      => IssueStatus::STATUS_IN_PROGRESS,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo',
            'description' =>
                'Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.',
            'assignee'    => false,
            'resolution'  => null,
            'type'        => IssueType::TYPE_BUG,
            'status'      => IssueStatus::STATUS_CLOSED,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Nullam dictum felis eu pede mollis pretium',
            'description' =>
                'Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet sem neque sed.',
            'assignee'    => true,
            'resolution'  => IssueResolution::RESOLUTION_FIXED,
            'type'        => IssueType::TYPE_BUG,
            'status'      => IssueStatus::STATUS_RESOLVED,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Integer tincidunt',
            'description' =>
                'Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec et tincidunt tempus.',
            'assignee'    => true,
            'resolution'  => IssueResolution::RESOLUTION_CANNOT_REPRODUCE,
            'type'        => IssueType::TYPE_BUG,
            'status'      => IssueStatus::STATUS_RESOLVED,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Cras dapibus',
            'description' =>
                'Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit orci eget eros faucibus.',
            'assignee'    => true,
            'resolution'  => null,
            'type'        => IssueType::TYPE_STORY,
            'status'      => IssueStatus::STATUS_OPEN,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Vivamus elementum semper nisi',
            'description' =>
                'Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget sodales.',
            'assignee'    => true,
            'resolution'  => null,
            'type'        => IssueType::TYPE_STORY,
            'status'      => IssueStatus::STATUS_REOPENED,
            'parent'      => false,
            'tags'        => [],
        ],
        [
            'summary'     => 'Aenean vulputate eleifend tellus',
            'description' =>
                'Fusce vulputate eleifend sapien. Vestibulum purus quam, scelerisque ut, mollis sed, nonummy id.',
            'assignee'    => false,
            'resolution'  => null,
            'type'        => IssueType::TYPE_SUBTASK,
            'status'      => IssueStatus::STATUS_OPEN,
            'parent'      => true,
            'tags'        => [],
        ],
        [
            'summary'     => 'Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim',
            'description' =>
                'Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in orci luctus.',
            'assignee'    => true,
            'resolution'  => null,
            'type'        => IssueType::TYPE_SUBTASK,
            'status'      => IssueStatus::STATUS_IN_PROGRESS,
            'parent'      => true,
            'tags'        => [],
        ],
        [
            'summary'     => 'Aliquam lorem ante, dapibus in, viverra quis, feugiat',
            'description' =>
                'Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum.',
            'assignee'    => true,
            'resolution'  => null,
            'type'        => IssueType::TYPE_SUBTASK,
            'status'      => IssueStatus::STATUS_REOPENED,
            'parent'      => true,
            'tags'        => [],
        ],
        [
            'summary'     => 'Aenean imperdiet. Etiam ultricies nisi vel',
            'description' =>
                'Integer ante arcu, accumsan a, consectetuer eget, posuere ut, mauris. Praesent adipiscing.',
            'assignee'    => false,
            'resolution'  => null,
            'type'        => IssueType::TYPE_SUBTASK,
            'status'      => IssueStatus::STATUS_CLOSED,
            'parent'      => true,
            'tags'        => [],
        ],
        [
            'summary'     => 'Praesent adipiscing',
            'description' =>
                'Vestibulum volutpat pretium libero. Cras id dui. Aenean ut eros et nisl sagittis vestibulum.',
            'assignee'    => true,
            'resolution'  => IssueResolution::RESOLUTION_INCOMPLETE,
            'type'        => IssueType::TYPE_SUBTASK,
            'status'      => IssueStatus::STATUS_RESOLVED,
            'parent'      => true,
            'tags'        => [],
        ],
        [
            'summary'     => 'Integer ante arcu',
            'description' =>
                'Sed lectus. Donec mollis hendrerit risus. Phasellus nec sem in justo pellentesque. Etiam imperdiet.',
            'assignee'    => true,
            'resolution'  => IssueResolution::RESOLUTION_WONT_DO,
            'type'        => IssueType::TYPE_SUBTASK,
            'status'      => IssueStatus::STATUS_RESOLVED,
            'parent'      => true,
            'tags'        => [],
        ],
        [
            'summary'     => 'Curabitur ligula sapien',
            'description' =>
                'Phasellus leo dolor, tempus non, auctor et, hendrerit quis, nisi. Curabitur sapien, tincidunt non.',
            'assignee'    => true,
            'resolution'  => null,
            'type'        => IssueType::TYPE_SUBTASK,
            'status'      => IssueStatus::STATUS_IN_PROGRESS,
            'parent'      => true,
            'tags'        => [],
        ],
        [
            'summary'     => 'Donec posuere vulputate',
            'description' =>
                'Praesent congue erat at massa. Sed cursus turpis vitae tortor. Donec posuere vulputate arcu.',
            'assignee'    => false,
            'resolution'  => null,
            'type'        => IssueType::TYPE_SUBTASK,
            'status'      => IssueStatus::STATUS_IN_PROGRESS,
            'parent'      => true,
            'tags'        => [],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'Luminaire\Bundle\IssueBundle\Migrations\Data\Demo\ORM\LoadUsersData',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->manager->getRepository('LuminaireIssueBundle:IssuePriority')->findAll();
        $this->manager->getRepository('LuminaireIssueBundle:IssueStatus')->findAll();
        $this->manager->getRepository('LuminaireIssueBundle:IssueResolution')->findAll();
        $this->manager->getRepository('LuminaireIssueBundle:IssueType')->findAll();

        foreach ($this->issues as $index => $data) {
            $this->setReference($this->getIssueReference($index), $this->createIssue($data));
        }

        $this->manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param array $data
     * @return Issue
     */
    protected function createIssue(array $data)
    {
        $entity = new Issue();
        $entity->setSummary($data['summary']);
        $entity->setDescription($data['description']);

        if ($data['assignee']) {
            $entity->setAssignee($this->getRandomUser());
        }
        $entity->setReporter($this->getRandomUser());

        $entity->setPriority($this->getPriority());
        if ($data['resolution']) {
            $entity->setResolution($this->getResolution($data['resolution']));
        }
        if ($data['parent']) {
            $entity->setParent($this->getRandomParentIssue());
        }
        $entity->setType($this->getType($data['type']));
        $entity->setStatus($this->getStatus($data['status']));

        foreach ($data['tags'] as $tags) {
            $entity->setTags($tags);
        }

        $this->manager->persist($entity);

        return $entity;
    }

    /**
     * @param $name
     * @return IssueResolution
     */
    protected function getResolution($name)
    {
        return $this->manager->getRepository('LuminaireIssueBundle:IssueResolution')->find($name);
    }

    /**
     * @param $name
     * @return IssueType
     */
    protected function getType($name)
    {
        return $this->manager->getRepository('LuminaireIssueBundle:IssueType')->find($name);
    }

    /**
     * @param $name
     * @return IssueStatus
     */
    protected function getStatus($name)
    {
        return $this->manager->getRepository('LuminaireIssueBundle:IssueStatus')->find($name);
    }

    /**
     * @return object
     */
    protected function getRandomParentIssue()
    {
        $stories = array_keys(array_filter($this->issues, function ($issue) {
            return $issue['type'] === IssueType::TYPE_STORY;
        }));
        shuffle($stories);

        return $this->getReference($this->getIssueReference(reset($stories)));
    }

    /**
     * @param $index
     * @return string
     */
    protected function getIssueReference($index)
    {
        return $index . '-issue';
    }

    /**
     * @return IssuePriority
     */
    protected function getPriority()
    {
        $priorities = [
            IssuePriority::PRIORITY_BLOCKER,
            IssuePriority::PRIORITY_CRITICAL,
            IssuePriority::PRIORITY_MAJOR,
            IssuePriority::PRIORITY_MINOR,
            IssuePriority::PRIORITY_TRIVIAL,
        ];
        shuffle($priorities);
        return $this->manager->getRepository('LuminaireIssueBundle:IssuePriority')->find($priorities[0]);
    }

    /**
     * @return \Oro\Bundle\UserBundle\Entity\User
     */
    protected function getRandomUser()
    {
        if (!$count = $this->getUserCount()) {
            return null;
        }

        return $this->manager->createQueryBuilder()
            ->select('u')
            ->from('OroUserBundle:User', 'u')
            ->setFirstResult(rand(0, $count - 1))
            ->setMaxResults(1)
            ->orderBy('u.id')
            ->getQuery()
            ->getSingleResult();

    }

    /**
     * @return int
     */
    protected function getUserCount()
    {
        if (is_null($this->usersCount)) {
            $this->usersCount = (int)$this->manager->createQueryBuilder()
                ->select('COUNT(u)')
                ->from('OroUserBundle:User', 'u')
                ->getQuery()
                ->getSingleScalarResult();
        }

        return $this->usersCount;
    }
}
