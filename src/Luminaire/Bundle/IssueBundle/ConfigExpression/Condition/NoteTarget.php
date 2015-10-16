<?php

namespace Luminaire\Bundle\IssueBundle\ConfigExpression\Condition;

use Oro\Bundle\NoteBundle\Entity\Note;
use Oro\Component\ConfigExpression\Condition\AbstractCondition;
use Oro\Component\ConfigExpression\ContextAccessorAwareInterface;
use Oro\Component\ConfigExpression\ContextAccessorAwareTrait;
use Oro\Component\ConfigExpression\Exception;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

class NoteTarget extends AbstractCondition implements ContextAccessorAwareInterface
{
    use ContextAccessorAwareTrait;

    /**
     * @var PropertyPathInterface
     */
    protected $note;

    /**
     * @var string
     */
    protected $targetClass;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'note_target';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->convertToArray([$this->note, $this->targetClass]);
    }

    /**
     * {@inheritdoc}
     */
    public function compile($factoryAccessor)
    {
        return $this->convertToPhpCode([$this->note, $this->targetClass], $factoryAccessor);
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $options)
    {
        if (2 !== count($options)) {
            throw new Exception\InvalidArgumentException(
                sprintf('Options must have 2 elements, but %d given.', count($options))
            );
        }

        if (isset($options['note'])) {
            $this->note = $options['note'];
        } elseif (isset($options[0])) {
            $this->note = $options[0];
        } else {
            throw new Exception\InvalidArgumentException('Option "note" is required.');
        }

        if (isset($options['targetClass'])) {
            $this->targetClass = $options['targetClass'];
        } elseif (isset($options[1])) {
            $this->targetClass = $options[1];
        } else {
            throw new Exception\InvalidArgumentException('Option "targetClass" is required.');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function isConditionAllowed($context)
    {
        if ($this->note instanceof PropertyPathInterface) {
            /** @var Note $note */
            $note = $this->resolveValue($context, $this->note, false);
            return $note instanceof Note && $note->supportTarget($this->targetClass);
        }

        return false;
    }
}
