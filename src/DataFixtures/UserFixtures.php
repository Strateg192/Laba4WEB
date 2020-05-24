<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $dataUser=[
            'name'=>'alex',
            'email'=>'admin@gmail.com',
            'roles'=>['ROLE_ADMIN'],
            'password'=>'123456'
        ];
        $user = $this->createUser($dataUser);
        $manager->persist($user);

        $dataUser=[
            'name'=>'sasha',
            'email'=>'user@gmail.com',
            'roles'=>[],
            'password'=>'123456'
        ];
        $user = $this->createUser($dataUser);
        $manager->persist($user);

        $manager->flush();
    }

    private function createUser(array $dataUser): User
    {
        $user = new User();

        $user->setName($dataUser['name']);
        $user->setEmail($dataUser['email']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $dataUser['password']
        ));
        $user->setRoles($dataUser['roles']);

        return $user;
    }
}