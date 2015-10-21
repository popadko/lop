<?php

namespace Luminaire\Bundle\IssueBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IssueResolvedResolution
 *
 * @Annotation
 */
class IssueResolvedResolution extends Constraint
{
    /**
     * @var string
     */
    public $message = 'luminaire.issue.validation.resolved_resolution';

    /**
     * @var string
     */
    public $messageResolution = 'luminaire.issue.validation.resolved_resolution.require';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
