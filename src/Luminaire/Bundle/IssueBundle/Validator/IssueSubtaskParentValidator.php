<?php

namespace Luminaire\Bundle\IssueBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Luminaire\Bundle\IssueBundle\Validator\Constraints\IssueSubtaskParent;

/**
 * Class IssueSubtaskParentValidator
 */
class IssueSubtaskParentValidator extends ConstraintValidator
{
    /**
     * @param \Luminaire\Bundle\IssueBundle\Entity\Issue $entity
     * @param Constraint|IssueSubtaskParent $constraint
     */
    public function validate($entity, Constraint $constraint)
    {
        if (empty($entity->getType())) {
            return;
        }

        if (!empty($entity->getParent()) && !$entity->getType()->isSubtask()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('parent')
                ->addViolation();
        }

        if (empty($entity->getParent()) && $entity->getType()->isSubtask()) {
            $this->context->buildViolation($constraint->messageEmptyParent)
                ->atPath('parent')
                ->addViolation();
        }
    }
}
