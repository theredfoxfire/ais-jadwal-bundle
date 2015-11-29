<?php

namespace Ais\JadwalBundle\Handler;

use Ais\JadwalBundle\Model\JadwalInterface;

interface JadwalHandlerInterface
{
    /**
     * Get a Jadwal given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return JadwalInterface
     */
    public function get($id);

    /**
     * Get a list of Jadwals.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Jadwal, creates a new Jadwal.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return JadwalInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Jadwal.
     *
     * @api
     *
     * @param JadwalInterface   $jadwal
     * @param array           $parameters
     *
     * @return JadwalInterface
     */
    public function put(JadwalInterface $jadwal, array $parameters);

    /**
     * Partially update a Jadwal.
     *
     * @api
     *
     * @param JadwalInterface   $jadwal
     * @param array           $parameters
     *
     * @return JadwalInterface
     */
    public function patch(JadwalInterface $jadwal, array $parameters);
}
