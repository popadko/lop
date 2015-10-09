<?php

namespace Luminaire\Bundle\IssueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * IssuePriority
 *
 * @ORM\Table(name="luminaire_issue_priority")
 * @ORM\Entity
 * @Config
 */
class IssuePriority
{
    const PRIORITY_BLOCKER = 'blocker';
    const PRIORITY_CRITICAL = 'critical';
    const PRIORITY_MAJOR = 'major';
    const PRIORITY_MINOR = 'minor';
    const PRIORITY_TRIVIAL = 'trivial';

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
