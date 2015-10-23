<?php

namespace Luminaire\Bundle\IssueBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EmailBundle\Model\EmailHolderInterface;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Luminaire\Bundle\IssueBundle\Model\ExtendIssue;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;

/**
 * Issue
 *
 * @ORM\Table(name="luminaire_issue")
 * @ORM\Entity(repositoryClass="Luminaire\Bundle\IssueBundle\Entity\Repository\IssueRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Config(
 *  defaultValues={
 *      "workflow"={
 *          "active_workflow"="issue_flow"
 *      }
 *  }
 * )
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Issue extends ExtendIssue implements Taggable, EmailHolderInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var IssueType
     *
     * @ORM\ManyToOne(targetEntity="IssueType")
     * @ORM\JoinColumn(name="type_name", referencedColumnName="name", nullable=false)
     */
    private $type;

    /**
     * @var IssueStatus
     *
     * @ORM\ManyToOne(targetEntity="IssueStatus")
     * @ORM\JoinColumn(name="status_name", referencedColumnName="name", nullable=false)
     */
    private $status;

    /**
     * @var IssuePriority
     *
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumn(name="priority_name", referencedColumnName="name", nullable=false)
     */
    private $priority;

    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution")
     * @ORM\JoinColumn(name="resolution_name", referencedColumnName="name")
     */
    private $resolution;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", nullable=false)
     */
    private $reporter;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id")
     */
    private $assignee;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var Collection $tags
     */
    private $tags;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="luminaire_user_to_issue")
     */
    protected $collaborators;

    /**
     * @var \Luminaire\Bundle\IssueBundle\Entity\Issue
     *
     * @ORM\ManyToOne(targetEntity="Luminaire\Bundle\IssueBundle\Entity\Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Luminaire\Bundle\IssueBundle\Entity\Issue", mappedBy="parent")
     **/
    private $children;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $workflowStep;

    /**
     * @ORM\PrePersist
     */
    public function initDateTimeFieldOnPrePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = clone $this->createdAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateUpdateAtOnPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->collaborators = new ArrayCollection();
        $this->children      = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId() . ' ' . $this->getSummary();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set type
     *
     * @param IssueType $type
     *
     * @return Issue
     */
    public function setType(IssueType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return IssueType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set status
     *
     * @param IssueStatus $status
     *
     * @return Issue
     */
    public function setStatus(IssueStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return IssueStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set priority
     *
     * @param IssuePriority $priority
     *
     * @return Issue
     */
    public function setPriority(IssuePriority $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return IssuePriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set resolution
     *
     * @param IssueResolution $resolution
     *
     * @return Issue
     */
    public function setResolution(IssueResolution $resolution = null)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return IssueResolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set reporter
     *
     * @param User $reporter
     *
     * @return Issue
     */
    public function setReporter(User $reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set assignee
     *
     * @param User $assignee
     *
     * @return Issue
     */
    public function setAssignee(User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Issue
     */
    public function setCreatedAt()
    {
        throw new \LogicException('This method should not be called');
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Issue
     */
    public function setUpdatedAt()
    {
        throw new \LogicException('This method should not be called');
    }

    /**
     * Add collaborator
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $collaborator
     *
     * @return Issue
     */
    public function addCollaborator(\Oro\Bundle\UserBundle\Entity\User $collaborator)
    {
        if (!$this->collaborators->contains($collaborator)) {
            $this->collaborators[] = $collaborator;
        }

        return $this;
    }

    /**
     * Remove collaborator
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $collaborator
     */
    public function removeCollaborator(\Oro\Bundle\UserBundle\Entity\User $collaborator)
    {
        $this->collaborators->removeElement($collaborator);
    }

    /**
     * Get collaborators
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * Set parent
     *
     * @param \Luminaire\Bundle\IssueBundle\Entity\Issue $parent
     *
     * @return Issue
     */
    public function setParent(\Luminaire\Bundle\IssueBundle\Entity\Issue $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Luminaire\Bundle\IssueBundle\Entity\Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Luminaire\Bundle\IssueBundle\Entity\Issue $child
     *
     * @return Issue
     */
    public function addChild(\Luminaire\Bundle\IssueBundle\Entity\Issue $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Luminaire\Bundle\IssueBundle\Entity\Issue $child
     */
    public function removeChild(\Luminaire\Bundle\IssueBundle\Entity\Issue $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @inheritDoc
     */
    public function getEmail()
    {
        return $this->getReporter()->getEmail();
    }

    /**
     * @param WorkflowItem $workflowItem
     * @return Issue
     */
    public function setWorkflowItem($workflowItem)
    {
        $this->workflowItem = $workflowItem;

        return $this;
    }

    /**
     * @return WorkflowItem
     */
    public function getWorkflowItem()
    {
        return $this->workflowItem;
    }

    /**
     * @param WorkflowItem $workflowStep
     * @return Issue
     */
    public function setWorkflowStep($workflowStep)
    {
        $this->workflowStep = $workflowStep;

        return $this;
    }

    /**
     * @return WorkflowStep
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
    }
}
