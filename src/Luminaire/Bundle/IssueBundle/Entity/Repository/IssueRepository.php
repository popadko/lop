<?php

namespace Luminaire\Bundle\IssueBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Luminaire\Bundle\IssueBundle\Entity\IssueType;

/**
 * Class IssueRepository
 */
class IssueRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function countIssuesByWorkflowStep()
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->select(
                'count(i) as issue_count',
                's.label as name',
                's.label'
            )
            ->leftJoin('i.workflowStep', 's')
            ->groupBy('s');

        return $queryBuilder->getQuery()->getArrayResult();
    }

    /**
     * @param IssueType $type
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderByType(IssueType $type = null)
    {
        $queryBuilder = $this->createQueryBuilder('i');
        if (!is_null($type)) {
            $queryBuilder->andWhere('i.type = :type')
                ->setParameter('type', $type);
        }

        return $queryBuilder;
    }
}
