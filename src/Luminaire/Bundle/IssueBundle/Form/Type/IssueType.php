<?php

namespace Luminaire\Bundle\IssueBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Luminaire\Bundle\IssueBundle\Entity\Repository\IssueRepository;
use Luminaire\Bundle\IssueBundle\Entity\IssueType as IssueTypeEntity;

/**
 * Class IssueType
 */
class IssueType extends AbstractType
{
    /**
     *
     */
    const NAME = 'luminaire_issue';

    /**
     * @var
     */
    private $doctrine;

    /**
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $orderByOrderField = function (EntityRepository $repository) {
            return $repository->createQueryBuilder('e')->orderBy('e.order');
        };

        $builder
            ->add('summary')
            ->add('description')
            ->add('type', null, [
                'query_builder' => $orderByOrderField,
            ])
            ->add('priority', null, [
                'query_builder' => $orderByOrderField,
            ])
            ->add('status', null, [
                'query_builder' => $orderByOrderField,
            ])
            ->add('resolution', null, [
                'query_builder' => $orderByOrderField,
            ])
            ->add('reporter', null, [
                'label' => 'luminaire.issue.reporter.label'
            ])
            ->add('assignee', null, [
                'label' => 'luminaire.issue.assignee.label'
            ])
            ->add('parent', null, [
                'query_builder' => function (IssueRepository $issueRepository) {
                    $issueTypeClass = 'LuminaireIssueBundle:IssueType';
                    $type           = $this->doctrine->getManagerForClass($issueTypeClass)
                        ->getRepository($issueTypeClass)->findOneBy(['name' => IssueTypeEntity::TYPE_STORY]);
                    return $issueRepository->getQueryBuilderByType($type);
                },
            ])
            ->add(
                'tags',
                'oro_tag_select',
                [
                    'label' => 'oro.tag.entity_plural_label'
                ]
            );

        $builder->add(
            'appendCollaborators',
            'oro_entity_identifier',
            [
                'class'    => 'OroUserBundle:User',
                'required' => false,
                'mapped'   => false,
                'multiple' => true,
            ]
        );
        $builder->add(
            'removeCollaborators',
            'oro_entity_identifier',
            [
                'class'    => 'OroUserBundle:User',
                'required' => false,
                'mapped'   => false,
                'multiple' => true,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Luminaire\Bundle\IssueBundle\Entity\Issue',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::NAME;
    }
}
