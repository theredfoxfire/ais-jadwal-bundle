<?php

namespace Ais\DosenBundle\Tests\Fixtures\Entity;

use Ais\DosenBundle\Entity\Dosen;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadDosenData implements FixtureInterface
{
    static public $dosens = array();

    public function load(ObjectManager $manager)
    {
        $dosen = new Dosen();
        $dosen->setTitle('title');
        $dosen->setBody('body');

        $manager->persist($dosen);
        $manager->flush();

        self::$dosens[] = $dosen;
    }
}
