<?php

namespace Ais\JadwalBundle\Tests\Handler;

use Ais\JadwalBundle\Handler\JadwalHandler;
use Ais\JadwalBundle\Model\JadwalInterface;
use Ais\JadwalBundle\Entity\Jadwal;

class JadwalHandlerTest extends \PHPUnit_Framework_TestCase
{
    const DOSEN_CLASS = 'Ais\JadwalBundle\Tests\Handler\DummyJadwal';

    /** @var JadwalHandler */
    protected $jadwalHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::DOSEN_CLASS));
    }


    public function testGet()
    {
        $id = 1;
        $jadwal = $this->getJadwal();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($jadwal));

        $this->jadwalHandler = $this->createJadwalHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $this->jadwalHandler->get($id);
    }

    public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $jadwals = $this->getJadwals(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($jadwals));

        $this->jadwalHandler = $this->createJadwalHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $all = $this->jadwalHandler->all($limit, $offset);

        $this->assertEquals($jadwals, $all);
    }

    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $jadwal = $this->getJadwal();
        $jadwal->setTitle($title);
        $jadwal->setBody($body);

        $form = $this->getMock('Ais\JadwalBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($jadwal));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->jadwalHandler = $this->createJadwalHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $jadwalObject = $this->jadwalHandler->post($parameters);

        $this->assertEquals($jadwalObject, $jadwal);
    }

    /**
     * @expectedException Ais\JadwalBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $jadwal = $this->getJadwal();
        $jadwal->setTitle($title);
        $jadwal->setBody($body);

        $form = $this->getMock('Ais\JadwalBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->jadwalHandler = $this->createJadwalHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $this->jadwalHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $jadwal = $this->getJadwal();
        $jadwal->setTitle($title);
        $jadwal->setBody($body);

        $form = $this->getMock('Ais\JadwalBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($jadwal));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->jadwalHandler = $this->createJadwalHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $jadwalObject = $this->jadwalHandler->put($jadwal, $parameters);

        $this->assertEquals($jadwalObject, $jadwal);
    }

    public function testPatch()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('body' => $body);

        $jadwal = $this->getJadwal();
        $jadwal->setTitle($title);
        $jadwal->setBody($body);

        $form = $this->getMock('Ais\JadwalBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($jadwal));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->jadwalHandler = $this->createJadwalHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $jadwalObject = $this->jadwalHandler->patch($jadwal, $parameters);

        $this->assertEquals($jadwalObject, $jadwal);
    }


    protected function createJadwalHandler($objectManager, $jadwalClass, $formFactory)
    {
        return new JadwalHandler($objectManager, $jadwalClass, $formFactory);
    }

    protected function getJadwal()
    {
        $jadwalClass = static::DOSEN_CLASS;

        return new $jadwalClass();
    }

    protected function getJadwals($maxJadwals = 5)
    {
        $jadwals = array();
        for($i = 0; $i < $maxJadwals; $i++) {
            $jadwals[] = $this->getJadwal();
        }

        return $jadwals;
    }
}

class DummyJadwal extends Jadwal
{
}
