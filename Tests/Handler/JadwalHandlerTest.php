<?php

namespace Ais\DosenBundle\Tests\Handler;

use Ais\DosenBundle\Handler\DosenHandler;
use Ais\DosenBundle\Model\DosenInterface;
use Ais\DosenBundle\Entity\Dosen;

class DosenHandlerTest extends \PHPUnit_Framework_TestCase
{
    const DOSEN_CLASS = 'Ais\DosenBundle\Tests\Handler\DummyDosen';

    /** @var DosenHandler */
    protected $dosenHandler;
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
        $dosen = $this->getDosen();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($dosen));

        $this->dosenHandler = $this->createDosenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $this->dosenHandler->get($id);
    }

    public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $dosens = $this->getDosens(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($dosens));

        $this->dosenHandler = $this->createDosenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $all = $this->dosenHandler->all($limit, $offset);

        $this->assertEquals($dosens, $all);
    }

    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $dosen = $this->getDosen();
        $dosen->setTitle($title);
        $dosen->setBody($body);

        $form = $this->getMock('Ais\DosenBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($dosen));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->dosenHandler = $this->createDosenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $dosenObject = $this->dosenHandler->post($parameters);

        $this->assertEquals($dosenObject, $dosen);
    }

    /**
     * @expectedException Ais\DosenBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $dosen = $this->getDosen();
        $dosen->setTitle($title);
        $dosen->setBody($body);

        $form = $this->getMock('Ais\DosenBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->dosenHandler = $this->createDosenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $this->dosenHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $dosen = $this->getDosen();
        $dosen->setTitle($title);
        $dosen->setBody($body);

        $form = $this->getMock('Ais\DosenBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($dosen));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->dosenHandler = $this->createDosenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $dosenObject = $this->dosenHandler->put($dosen, $parameters);

        $this->assertEquals($dosenObject, $dosen);
    }

    public function testPatch()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('body' => $body);

        $dosen = $this->getDosen();
        $dosen->setTitle($title);
        $dosen->setBody($body);

        $form = $this->getMock('Ais\DosenBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($dosen));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->dosenHandler = $this->createDosenHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $dosenObject = $this->dosenHandler->patch($dosen, $parameters);

        $this->assertEquals($dosenObject, $dosen);
    }


    protected function createDosenHandler($objectManager, $dosenClass, $formFactory)
    {
        return new DosenHandler($objectManager, $dosenClass, $formFactory);
    }

    protected function getDosen()
    {
        $dosenClass = static::DOSEN_CLASS;

        return new $dosenClass();
    }

    protected function getDosens($maxDosens = 5)
    {
        $dosens = array();
        for($i = 0; $i < $maxDosens; $i++) {
            $dosens[] = $this->getDosen();
        }

        return $dosens;
    }
}

class DummyDosen extends Dosen
{
}
