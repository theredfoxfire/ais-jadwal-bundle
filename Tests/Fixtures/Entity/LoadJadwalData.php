<?php

namespace Ais\JadwalBundle\Tests\Fixtures\Entity;

use Ais\JadwalBundle\Entity\Jadwal;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadJadwalData implements FixtureInterface
{
    static public $jadwals = array();

    public function load(ObjectManager $manager)
    {
        $jadwal = new Jadwal();
        $jadwal->setTitle('title');
        $jadwal->setBody('body');

        $manager->persist($jadwal);
        $manager->flush();

        self::$jadwals[] = $jadwal;
    }
}
