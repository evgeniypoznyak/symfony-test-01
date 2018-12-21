<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
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
}
