<?php

namespace Luminaire\Bundle\IssueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * IssueResolution
 *
 * @ORM\Table(name="luminaire_issue_resolution")
 * @ORM\Entity
 * @Config
 */
class IssueResolution
{
    const RESOLUTION_FIXED = 'fixed';
    const RESOLUTION_WONT_FIX = 'wont_fix';
    const RESOLUTION_DUPLICATE = 'duplicate';
    const RESOLUTION_INCOMPLETE = 'incomplete';
    const RESOLUTION_CANNOT_REPRODUCE = 'cannot_reproduce';
    const RESOLUTION_DONE = 'done';
    const RESOLUTION_WONT_DO = 'wont_do';

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
