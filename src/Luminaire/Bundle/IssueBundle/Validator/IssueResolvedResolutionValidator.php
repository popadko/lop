<?php

namespace Luminaire\Bundle\IssueBundle\Validator;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class IssueResolvedResolutionValidator
 */
class IssueResolvedResolutionValidator extends ConstraintValidator
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
        if (!empty($entity->getResolution()) && !$entity->getStatus()->isResolved()) {
            $this->context->buildViolation($this->translator->trans($constraint->message))
                ->atPath('resolution')
                ->addViolation();
        }

        if (empty($entity->getResolution()) && $entity->getStatus()->isResolved()) {
            $this->context->buildViolation($this->translator->trans($constraint->messageResolution))
                ->atPath('resolution')
                ->addViolation();
        }
    }
}
