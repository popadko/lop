<?php

namespace Luminaire\Bundle\IssueBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IssueSubtaskParent
 *
 * @Annotation
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
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
