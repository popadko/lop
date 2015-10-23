<?php

namespace Luminaire\Bundle\IssueBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class IssueResolvedResolutionValidator
 */
class IssueResolvedResolutionValidator extends ConstraintValidator
{
    /**
     * @param \Luminaire\Bundle\IssueBundle\Entity\Issue $entity
     * @param Constraint $constraint
     */
    public function validate($entity, Constraint $constraint)
    {
        if (!empty($entity->getResolution()) && !$entity->isResolvedWorkflowIssue()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('resolution')
                ->addViolation();
        }
    }
}
