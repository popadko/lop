<?php

namespace Luminaire\Bundle\IssueBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Luminaire\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class IssueHandler
 */
class IssueHandler implements TagHandlerInterface
{
    /**
     * @var TagManager
     */
    private $tagManager;

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param ObjectManager $manager
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        ObjectManager $manager
    ) {
        $this->form    = $form;
        $this->request = $request;
        $this->manager = $manager;
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
