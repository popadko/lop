<?php

namespace Luminaire\Bundle\IssueBundle\Provider;

use Luminaire\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\EntityBundle\Provider\EntityNameProviderInterface;

/**
 * Class IssueEntityNameProvider
 */
class IssueEntityNameProvider implements EntityNameProviderInterface
{
    /**
     *
     */
    const CLASS_NAME = 'Luminaire\Bundle\IssueBundle\Entity\Issue';

    /**
     * @inheritDoc
     */
    public function getName($format, $locale, $entity)
    {
        if (!$entity instanceof Issue) {
            return false;
        }

        if ($format === self::SHORT) {
            return $entity->getId();

        }
        return $entity->getId() . ' ' . $entity->getSummary();
    }

    /**
     * @inheritDoc
     */
    public function getNameDQL($format, $locale, $className, $alias)
    {
        if ($className !== self::CLASS_NAME) {
            return false;
        }

        if ($format === self::SHORT) {
            return $alias . '.id';
        }

        return sprintf('CONCAT(%s.id, \' \', %s.summary)', $alias, $alias);
    }
}
