<?php

namespace Luminaire\Bundle\IssueBundle\Controller\Dashboard;

use Luminaire\Bundle\IssueBundle\Entity\Issue;
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
     *      "/issue/status_chart/{widget}",
     *      name="luminaire_issue_issues_by_workflow_step_chart",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("LuminaireIssueBundle:Dashboard:issueByWorkflowStep.html.twig")
     */
    public function issueByWorkflowStepAction($widget)
    {
        $items = $this->getDoctrine()
            ->getRepository('LuminaireIssueBundle:Issue')
            ->countIssuesByWorkflowStep();

        $steps = [
            Issue::WORKFLOW_STEP_NAME_OPEN,
            Issue::WORKFLOW_STEP_NAME_REOPENED,
            Issue::WORKFLOW_STEP_NAME_IN_PROGRESS,
            Issue::WORKFLOW_STEP_NAME_CLOSED,
            Issue::WORKFLOW_STEP_NAME_RESOLVED,
        ];

        $steps = array_reduce($steps, function ($result, $step) {
            $result[$step] = [
                'issue_count' => 0,
                'label'       => $step,
            ];
            return $result;
        }, []);

        $items = array_reduce($items, function ($result, $item) {
            $result[$item['name']]['issue_count'] += $item['issue_count'];
            return $result;
        }, $steps);

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
                    'settings'    => ['xNoTicks' => count($steps)],
                ]
            )
            ->getView();

        return $widgetAttr;
    }
}
