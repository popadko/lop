<?php

namespace Luminaire\Bundle\IssueBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIssueUsersData extends AbstractFixture implements ContainerAwareInterface
{
    const ISSUE_USER_1 = 'issue.user_1';
    const ISSUE_USER_2 = 'issue.user_2';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->createIssueUser($manager, self::ISSUE_USER_1);
        $this->createIssueUser($manager, self::ISSUE_USER_2);

        $manager->flush();
    }

    /**
     * @param ObjectManager|\Doctrine\ORM\EntityManager $manager
     * @param $name
     */
    public function createIssueUser(ObjectManager $manager, $name)
    {
        $userManager = $this->container->get('oro_user.manager');

        /** @var \Oro\Bundle\UserBundle\Entity\User $user */
        $user = $userManager->createUser();
        $user->setUsername($name)
            ->setPlainPassword('secret')
            ->setFirstName($name . 'first_name')
            ->setLastName($name . 'last_name')
            ->setEmail($name . '@example.com')
            ->setBusinessUnits(new ArrayCollection([$manager->getReference('OroOrganizationBundle:BusinessUnit', 1)]))
            ->setOrganization($manager->getReference('OroOrganizationBundle:Organization', 1))
            ->setEnabled(true);

        $userManager->updateUser($user);

        $this->setReference($user->getUsername(), $user);
    }
}
