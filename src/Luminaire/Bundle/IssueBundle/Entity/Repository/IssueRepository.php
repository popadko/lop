<?php

namespace Luminaire\Bundle\IssueBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class IssueRepository
 */
class IssueRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getIssuesByStatus()
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->select(
                'count(i) as issue_count',
                's.name',
                's.label'
            )
            ->innerJoin('i.status', 's')
            ->groupBy('s');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
