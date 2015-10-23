<?php

namespace Luminaire\Bundle\IssueBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IssueSubtaskParent
 */
class IssueSubtaskParent extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Parent issue can be set only for Subtask issue.';

    /**
     * @var string
     */
    public $messageEmptyParent = 'Parent issue must be set for Subtask issue.';

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
        return 'luminaire_issue.validator.issue_subtask_parent_validator';
    }
}
