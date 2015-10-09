<?php

namespace Luminaire\Bundle\IssueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
}
