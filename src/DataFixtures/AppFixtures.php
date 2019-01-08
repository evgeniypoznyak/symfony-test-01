<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadMicropost($manager);
        $this->loadUsers($manager);
    }

    private function loadMicropost(ObjectManager $manager)
    {

        for ($i = 0; $i < 10; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(
                'Lorem Ipsum is simply. [' . rand(0, 100) . ']'
            );
            $microPost->setTime(new \DateTime('2018-12-18'));
            $manager->persist($microPost);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();

    }

    private function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('john_doe');
        $user->setFullName('John Doe');
        $user->setEmail('jdoe@mail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'poop123'));

        $manager->persist($user);
        $manager->flush();
    }
}
