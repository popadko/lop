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
    public $message = 'luminaire.issue.validation.subtask';

    /**
     * @var string
     */
    public $messageEmptyParent = 'luminaire.issue.validation.subtask.empty_parent';

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
