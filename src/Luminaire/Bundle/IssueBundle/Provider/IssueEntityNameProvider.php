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
            return $entity->getCode();

        }
        return $entity->getCode() . ' ' . $entity->getSummary();
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
            return $alias . '.code';
        }

        return sprintf('CONCAT(%s.code, \' \', %s.summary)', $alias, $alias);
    }
}
