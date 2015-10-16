<?php

namespace Luminaire\Bundle\IssueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * IssueType
 *
 * @ORM\Table(name="luminaire_issue_type")
 * @ORM\Entity
 * @Config
 */
class IssueType
{
    const TYPE_TASK = 'task';
    const TYPE_BUG = 'bug';
    const TYPE_STORY = 'story';
    const TYPE_SUBTASK = 'subtask';

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="name", type="string", length=16)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="`order`", type="integer")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="`label`", type="string", length=255)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "identity"=true
     *          }
     *      }
     * )
     */
    private $label;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set order
     *
     * @param integer $order
     *
     * @return IssuePriority
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return IssuePriority
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
