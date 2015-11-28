<?php

namespace Ais\DosenBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Ais\DosenBundle\Model\DosenInterface;
use Ais\DosenBundle\Form\DosenType;
use Ais\DosenBundle\Exception\InvalidFormException;

class DosenHandler implements DosenHandlerInterface
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
     * Get a Dosen.
     *
     * @param mixed $id
     *
     * @return DosenInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Dosens.
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
     * Create a new Dosen.
     *
     * @param array $parameters
     *
     * @return DosenInterface
     */
    public function post(array $parameters)
    {
        $dosen = $this->createDosen();

        return $this->processForm($dosen, $parameters, 'POST');
    }

    /**
     * Edit a Dosen.
     *
     * @param DosenInterface $dosen
     * @param array         $parameters
     *
     * @return DosenInterface
     */
    public function put(DosenInterface $dosen, array $parameters)
    {
        return $this->processForm($dosen, $parameters, 'PUT');
    }

    /**
     * Partially update a Dosen.
     *
     * @param DosenInterface $dosen
     * @param array         $parameters
     *
     * @return DosenInterface
     */
    public function patch(DosenInterface $dosen, array $parameters)
    {
        return $this->processForm($dosen, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param DosenInterface $dosen
     * @param array         $parameters
     * @param String        $method
     *
     * @return DosenInterface
     *
     * @throws \Ais\DosenBundle\Exception\InvalidFormException
     */
    private function processForm(DosenInterface $dosen, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new DosenType(), $dosen, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $dosen = $form->getData();
            $this->om->persist($dosen);
            $this->om->flush($dosen);

            return $dosen;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createDosen()
    {
        return new $this->entityClass();
    }

}
