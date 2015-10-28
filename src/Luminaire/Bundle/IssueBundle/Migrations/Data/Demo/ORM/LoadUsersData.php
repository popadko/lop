<?php

namespace Luminaire\Bundle\IssueBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadUsersData
 */
class LoadUsersData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var array
     */
    protected $data = [
        [
            'username'   => 'john',
            'password'   => 'secret',
            'first_name' => 'John',
            'last_name'  => 'Smith',
            'email'      => 'john@smith.com',
        ],
        [
            'username'   => 'james',
            'password'   => 'secret',
            'first_name' => 'James',
            'last_name'  => 'Simpson',
            'email'      => 'james@simpson.com',
        ]
    ];

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
        $organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();
        $businessUnit = $manager->getRepository('OroOrganizationBundle:BusinessUnit')->getFirst();

        foreach ($this->data as $userData) {
            $userData['organization']  = $organization;
            $userData['business_unit'] = $businessUnit;
            $this->createUser($userData);
        }

        $manager->flush();
    }

    /**
     * @param $data
     */
    public function createUser($data)
    {
        $userManager = $this->container->get('oro_user.manager');

        /** @var \Oro\Bundle\UserBundle\Entity\User $user */
        $user = $userManager->createUser();
        $user->setUsername($data['username'])
            ->setPlainPassword($data['password'])
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setEmail($data['email'])
            ->setBusinessUnits(new ArrayCollection([$data['business_unit']]))
            ->setOrganization($data['organization'])
            ->setEnabled(true);

        $userManager->updateUser($user);

        $this->setReference($user->getUsername(), $user);
    }
}
