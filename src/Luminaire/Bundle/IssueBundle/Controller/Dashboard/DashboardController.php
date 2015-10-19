<?php

namespace Luminaire\Bundle\IssueBundle\Controller\Dashboard;

use Luminaire\Bundle\IssueBundle\Entity\IssueStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DashboardController
 */
class DashboardController extends Controller
{
    /**
     * @Route(
     *      "/opportunity_state/chart/{widget}",
     *      name="luminaire_issue_issues_by_status_chart",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("LuminaireIssueBundle:Dashboard:issuesByStatus.html.twig")
     */
    public function opportunityByStatusAction($widget)
    {
        $items = $this->getDoctrine()
            ->getRepository('LuminaireIssueBundle:Issue')
            ->getIssuesByStatus();

        $statuses = $this->getDoctrine()
            ->getRepository('LuminaireIssueBundle:IssueStatus')
            ->findAll();

        $statuses = array_reduce($statuses, function ($result, IssueStatus $status) {
            $result[$status->getName()] = [
                'issue_count' => 0,
                'label'       => $status->getLabel(),
            ];
            return $result;
        }, []);

        $items = array_reduce($items, function ($result, $item) {
            $result[$item['name']]['issue_count'] += $item['issue_count'];
            return $result;
        }, $statuses);

        $widgetAttr              = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
            ->setArrayData($items)
            ->setOptions(
                [
                    'name'        => 'bar_chart',
                    'data_schema' => [
                        'label' => ['field_name' => 'label'],
                        'value' => [
                            'field_name' => 'issue_count',
                            'type'       => 'number',
                        ]
                    ],
                    'settings'    => ['xNoTicks' => count($statuses)],
                ]
            )
            ->getView();

        return $widgetAttr;
    }
}
