<?php

namespace Luminaire\Bundle\IssueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Luminaire\Bundle\IssueBundle\Entity\Issue;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="luminaire_issue")
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
        return $this->update(new Issue(), $request);
    }

    /**
     * @Route("/update/{id}", name="luminaire_issue_update", requirements={"id":"\d+"})
     * @Template()
     */
    public function updateAction(Issue $entity, Request $request)
    {
        return $this->update($entity, $request);
    }

    /**
     * @param Issue $entity
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function update(Issue $entity, Request $request)
    {
        $form = $this->get('form.factory')->create('luminaire_issue', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->get('oro_ui.router')->redirectAfterSave(
                [
                    'route'      => 'luminaire_issue_update',
                    'parameters' => ['id' => $entity->getId()],
                ],
                [
                    'route'      => 'luminaire_issue_show',
                    'parameters' => ['id' => $entity->getId()],
                ],
                $entity
            );
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
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
