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
    public $message = 'luminaire.issue.validation.resolved_resolution';

    /**
     * @var string
     */
    public $messageResolution = 'luminaire.issue.validation.resolved_resolution.require';

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
