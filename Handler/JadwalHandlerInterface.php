<?php

namespace Ais\DosenBundle\Handler;

use Ais\DosenBundle\Model\DosenInterface;

interface DosenHandlerInterface
{
    /**
     * Get a Dosen given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return DosenInterface
     */
    public function get($id);

    /**
     * Get a list of Dosens.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Dosen, creates a new Dosen.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return DosenInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Dosen.
     *
     * @api
     *
     * @param DosenInterface   $dosen
     * @param array           $parameters
     *
     * @return DosenInterface
     */
    public function put(DosenInterface $dosen, array $parameters);

    /**
     * Partially update a Dosen.
     *
     * @api
     *
     * @param DosenInterface   $dosen
     * @param array           $parameters
     *
     * @return DosenInterface
     */
    public function patch(DosenInterface $dosen, array $parameters);
}
