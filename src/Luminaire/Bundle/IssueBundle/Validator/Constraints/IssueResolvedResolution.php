<?php

namespace Luminaire\Bundle\IssueBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IssueResolvedResolution
 */
class IssueResolvedResolution extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Resolution can be set only for Resolved issue.';

    /**
     * @inheritDoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @inheritDoc
     */
    public function validatedBy()
    {
        return 'luminaire_issue.validator.issue_resolved_resolution_validator';
    }
}
