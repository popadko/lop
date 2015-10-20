<?php

namespace Luminaire\Bundle\IssueBundle\EventListener;

use Luminaire\Bundle\IssueBundle\Handler\RequestCollaboratorHandler;
use Oro\Bundle\DataGridBundle\Datagrid\ParameterBag;
use Oro\Bundle\DataGridBundle\Event\PreBuild;
use Oro\Bundle\FilterBundle\Grid\Extension\OrmFilterExtension;

/**
 * Class DatagridListener
 */
class DatagridListener
{
    /**
     * @var RequestCollaboratorHandler
     */
    protected $collaboratorHandler;

    /**
     * @param RequestCollaboratorHandler $collaboratorHandler
     */
    public function __construct(RequestCollaboratorHandler $collaboratorHandler)
    {
        $this->collaboratorHandler = $collaboratorHandler;
    }

    /**
     * @param PreBuild $event
     */
    public function onPreBuildIssues(PreBuild $event)
    {
        if (!$collaborator = $this->collaboratorHandler->getCollaborator()) {
            return;
        }

        $parameters = $event->getParameters();
        $minifiedParameters = [];
        if ($parameters->has(ParameterBag::MINIFIED_PARAMETERS)) {
            $minifiedParameters = $parameters->get(ParameterBag::MINIFIED_PARAMETERS);
        }
        if (!isset($minifiedParameters[OrmFilterExtension::MINIFIED_FILTER_PARAM])) {
            $minifiedParameters[OrmFilterExtension::MINIFIED_FILTER_PARAM] = [];
        }
        $minifiedParameters[OrmFilterExtension::MINIFIED_FILTER_PARAM]['collaborators'] = [
            'value' => $collaborator->getId(),
        ];
        $parameters->set(ParameterBag::MINIFIED_PARAMETERS, $minifiedParameters);
    }
}
