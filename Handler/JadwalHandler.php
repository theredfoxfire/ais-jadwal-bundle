<?php

namespace Ais\JadwalBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Ais\JadwalBundle\Model\JadwalInterface;
use Ais\JadwalBundle\Form\JadwalType;
use Ais\JadwalBundle\Exception\InvalidFormException;

class JadwalHandler implements JadwalHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Jadwal.
     *
     * @param mixed $id
     *
     * @return JadwalInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Jadwals.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new Jadwal.
     *
     * @param array $parameters
     *
     * @return JadwalInterface
     */
    public function post(array $parameters)
    {
        $jadwal = $this->createJadwal();

        return $this->processForm($jadwal, $parameters, 'POST');
    }

    /**
     * Edit a Jadwal.
     *
     * @param JadwalInterface $jadwal
     * @param array         $parameters
     *
     * @return JadwalInterface
     */
    public function put(JadwalInterface $jadwal, array $parameters)
    {
        return $this->processForm($jadwal, $parameters, 'PUT');
    }

    /**
     * Partially update a Jadwal.
     *
     * @param JadwalInterface $jadwal
     * @param array         $parameters
     *
     * @return JadwalInterface
     */
    public function patch(JadwalInterface $jadwal, array $parameters)
    {
        return $this->processForm($jadwal, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param JadwalInterface $jadwal
     * @param array         $parameters
     * @param String        $method
     *
     * @return JadwalInterface
     *
     * @throws \Ais\JadwalBundle\Exception\InvalidFormException
     */
    private function processForm(JadwalInterface $jadwal, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new JadwalType(), $jadwal, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $jadwal = $form->getData();
            $this->om->persist($jadwal);
            $this->om->flush($jadwal);

            return $jadwal;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createJadwal()
    {
        return new $this->entityClass();
    }

}
