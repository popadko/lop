<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Luminaire\Bundle\IssueBundle\Entity\Issue;
use Luminaire\Bundle\IssueBundle\Entity\IssuePriority;
use Luminaire\Bundle\IssueBundle\Entity\IssueType;

class LoadIssueData extends AbstractFixture implements DependentFixtureInterface
{
    const ISSUE_STORY = 'issue_story';
    const ISSUE_BUG = 'issue_bug';

    /**
     * @var array
     */
    protected $issues = [
        self::ISSUE_BUG   => [
            'summary'     => 'Issue bug',
            'description' => 'Issue bug description',
            'assignee'    => LoadIssueUsersData::ISSUE_USER_2,
            'reporter'    => LoadIssueUsersData::ISSUE_USER_2,
            'priority'    => IssuePriority::PRIORITY_MAJOR,
            'resolution'  => null,
            'type'        => IssueType::TYPE_BUG,
            'tags'        => [],
        ],
        self::ISSUE_STORY => [
            'summary'     => 'Issue story',
            'description' => 'Issue story description',
            'assignee'    => LoadIssueUsersData::ISSUE_USER_2,
            'reporter'    => LoadIssueUsersData::ISSUE_USER_2,
            'priority'    => IssuePriority::PRIORITY_MAJOR,
            'resolution'  => null,
            'type'        => IssueType::TYPE_STORY,
            'tags'        => [],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'Luminaire\Bundle\IssueBundle\Tests\Functional\DataFixtures\LoadIssueUsersData',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->issues as $name => $data) {
            $entity = $this->createIssue($manager, $data);
            $this->setReference($name, $entity);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param array $data
     * @return Issue
     */
    protected function createIssue(ObjectManager $manager, array $data)
    {
        $entity = new Issue();
        $entity->setSummary($data['summary']);
        $entity->setDescription($data['description']);
        if ($data['assignee']) {
            $entity->setAssignee($this->getReference($data['assignee']));
        }
        /** @var \Oro\Bundle\UserBundle\Entity\User $user */
        $user = $this->getReference($data['reporter']);
        $entity->setReporter($user);
        $entity->setPriority($manager->getRepository('LuminaireIssueBundle:IssuePriority')->find($data['priority']));
        if ($data['resolution']) {
            $resolution = $manager->getRepository('LuminaireIssueBundle:IssueResolution')->find($data['resolution']);
            $entity->setResolution($resolution);
        }
        $entity->setType($manager->getRepository('LuminaireIssueBundle:IssueType')->find($data['type']));
        foreach ($data['tags'] as $tags) {
            $entity->setTags($tags);
        }

        $manager->persist($entity);

        return $entity;
    }
}
