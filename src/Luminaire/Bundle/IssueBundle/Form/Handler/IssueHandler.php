<?php

namespace Luminaire\Bundle\IssueBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Luminaire\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;
use Oro\Bundle\FormBundle\Utils\FormUtils;
use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class IssueHandler
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class IssueHandler implements TagHandlerInterface
{
    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param ObjectManager $manager
     */
    public function __construct(
        Request $request,
        ObjectManager $manager,
        EntityRoutingHelper $entityRoutingHelper
    ) {
        $this->request = $request;
        $this->manager = $manager;
        $this->entityRoutingHelper = $entityRoutingHelper;
    }

    /**
     * @param FormInterface $form
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTagManager(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    /**
     * @param Issue $entity
     * @return bool
     */
    public function process(Issue $entity)
    {
        if (!$this->form) {
            throw new \LogicException('Form must be set for IssueHandler via setForm method.');
        }

        $this->handleEntityRouting($entity);

        $this->form->setData($entity);

        if ($this->request->isMethod('POST')) {
            $this->form->submit($this->request);
            if ($this->form->isValid()) {
                $this->appendCollaborators($entity, $this->form->get('appendCollaborators')->getData());
                $this->removeCollaborators($entity, $this->form->get('removeCollaborators')->getData());
                $this->manager->persist($entity);
                $this->manager->flush();

                if ($this->tagManager) {
                    $this->tagManager->saveTagging($entity);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @param Issue $entity
     */
    protected function handleEntityRouting(Issue $entity)
    {
        $action            = $this->entityRoutingHelper->getAction($this->request);
        $targetEntityClass = $this->entityRoutingHelper->getEntityClassName($this->request);
        $targetEntityId    = $this->entityRoutingHelper->getEntityId($this->request);

        if ($targetEntityClass
            && !$entity->getId()
            && $this->request->getMethod() === 'GET'
            && $action === 'assign'
            && is_a($targetEntityClass, 'Oro\Bundle\UserBundle\Entity\User', true)
        ) {
            $entity->setAssignee($this->entityRoutingHelper->getEntity($targetEntityClass, $targetEntityId));
            FormUtils::replaceField($this->form, 'assignee', ['read_only' => true]);
        }
    }

    /**
     * Append collaborators to issue
     *
     * @param Issue $entity
     * @param User[] $users
     */
    protected function appendCollaborators(Issue $entity, array $users)
    {
        foreach ($users as $user) {
            $entity->addCollaborator($user);
        }
    }

    /**
     * Remove collaborators from issue
     *
     * @param Issue $entity
     * @param User[] $users
     */
    protected function removeCollaborators(Issue $entity, array $users)
    {
        foreach ($users as $user) {
            $entity->removeCollaborator($user);
        }
    }
}
