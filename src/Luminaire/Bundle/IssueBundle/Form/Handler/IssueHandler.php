<?php

namespace Luminaire\Bundle\IssueBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Luminaire\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;
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
}
