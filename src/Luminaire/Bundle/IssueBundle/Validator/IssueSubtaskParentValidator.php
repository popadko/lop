<?php

namespace Luminaire\Bundle\IssueBundle\Validator;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class IssueSubtaskParentValidator
 */
class IssueSubtaskParentValidator extends ConstraintValidator
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    /**
     * @param \Luminaire\Bundle\IssueBundle\Entity\Issue $entity
     * @param Constraint $constraint
     */
    public function validate($entity, Constraint $constraint)
    {
        if (!empty($entity->getParent()) && !$entity->getType()->isSubtask()) {
            $this->context->buildViolation($this->translator->trans($constraint->message))
                ->atPath('parent')
                ->addViolation();
        }

        if (empty($entity->getParent()) && $entity->getType()->isSubtask()) {
            $this->context->buildViolation($this->translator->trans($constraint->messageEmptyParent))
                ->atPath('parent')
                ->addViolation();
        }
    }
}
