<?php

namespace Luminaire\Bundle\IssueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Luminaire\Bundle\IssueBundle\Entity\Issue;
use Luminaire\Bundle\IssueBundle\Entity\IssueType as IssueTypeEntity;
use Luminaire\Bundle\IssueBundle\Form\Type\IssueType;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="luminaire_issue_index")
     * @Template
     */
    public function indexAction()
    {
        return ['entity_class' => 'Luminaire\Bundle\IssueBundle\Entity\Issue'];
    }

    /**
     * @Route("/create", name="luminaire_issue_create")
     * @Template("LuminaireIssueBundle:Issue:update.html.twig")
     */
    public function createAction(Request $request)
    {
        $issue = new Issue();
        if ($parentId = $request->get('parent_id')) {
            $issueTypeClass = 'LuminaireIssueBundle:IssueType';
            $issueTypeRepository = $this->getDoctrine()->getManagerForClass($issueTypeClass)
                ->getRepository($issueTypeClass);
            $type           = $issueTypeRepository->findOneBy(['name' => IssueTypeEntity::TYPE_STORY]);
            $issueClass = 'LuminaireIssueBundle:Issue';
            $parent         = $this->getDoctrine()->getManagerForClass($issueClass)
                ->getRepository($issueClass)->findOneBy([
                    'id'   => $parentId,
                    'type' => $type,
                ]);
            if ($parent) {
                $issue->setParent($parent);
                $issue->setType($issueTypeRepository->findOneBy(['name' => IssueTypeEntity::TYPE_SUBTASK]));
            }
        }
        return $this->update($issue);
    }

    /**
     * @Route("/update/{id}", name="luminaire_issue_update", requirements={"id":"\d+"})
     * @Template()
     */
    public function updateAction(Issue $entity)
    {
        return $this->update($entity);
    }

    /**
     * @param Issue $entity
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    protected function update(Issue $entity)
    {
        $form = $this->createForm(IssueType::NAME, $entity);

        return $this->get('oro_form.model.update_handler')->handleUpdate(
            $entity,
            $form,
            function (Issue $entity) {
                return [
                    'route'      => 'luminaire_issue_update',
                    'parameters' => ['id' => $entity->getId()],
                ];
            },
            function (Issue $entity) {
                return [
                    'route'      => 'luminaire_issue_show',
                    'parameters' => ['id' => $entity->getId()],
                ];
            },
            $this->get('translator')->trans('luminaire.issue.controller.issue.saved.message'),
            $this->get('luminaire_issue.form.handler.entity'),
            function (Issue $entity, FormInterface $form) {
                return [
                    'entity' => $entity,
                    'form'   => $form->createView(),
                ];
            }
        );
    }

    /**
     * @Route("/{id}", name="luminaire_issue_show", requirements={"id"="\d+"})
     * @Template
     */
    public function showAction(Issue $entity)
    {
        return ['entity' => $entity];
    }
}
