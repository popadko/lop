<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Unit\Entity\Repository;

use Luminaire\Bundle\IssueBundle\Entity\Repository\IssueRepository;
use Luminaire\Bundle\IssueBundle\Entity\IssueType;
use Luminaire\Bundle\IssueBundle\Tests\Functional\TestCase;

/**
 * Class IssueRepositoryTest
 * @dbIsolation
 */
class IssueRepositoryTest extends TestCase
{
    /**
     * @var IssueRepository
     */
    protected $repository;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->repository = $this->getContainer()->get('doctrine')
            ->getManager()->getRepository('LuminaireIssueBundle:Issue');

        $this->loadFixtures([
            'Luminaire\Bundle\IssueBundle\Tests\Functional\DataFixtures\LoadIssueData',
            'Luminaire\Bundle\IssueBundle\Tests\Functional\DataFixtures\LoadIssueUsersData',
        ]);
    }

    /**
     *
     */
    public function testCountIssuesByWorkflowStep()
    {
        $this->assertEquals([
            [
                'issue_count' => 1,
                'name'        => 'In progress',
                'label'       => 'In progress',
            ],
            [
                'issue_count' => 1,
                'name'        => 'Reopened',
                'label'       => 'Reopened',
            ]
        ], $this->repository->countIssuesByWorkflowStep());
    }

    /**
     * @dataProvider queryBuilderByTypeProvider
     */
    public function testGetQueryBuilderByType($dql, IssueType $type = null)
    {
        $queryBuilder = $this->repository->getQueryBuilderByType($type);
        $this->assertEquals($dql, $queryBuilder->getDQL());
        if (!is_null($type)) {
            $this->assertEquals($type, $queryBuilder->getParameter('type')->getValue());
        }
    }

    public function queryBuilderByTypeProvider()
    {
        return [
            [
                'dql'        => 'SELECT i FROM Luminaire\Bundle\IssueBundle\Entity\Issue i',
                'issue_type' => null,
            ],
            [
                'dql'        => 'SELECT i FROM Luminaire\Bundle\IssueBundle\Entity\Issue i WHERE i.type = :type',
                'issue_type' => new IssueType(IssueType::TYPE_STORY),
            ]
        ];
    }
}
