<?php

namespace Ais\JadwalBundle\Model;

Interface JadwalInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set runId
     *
     * @param string $runId
     *
     * @return Jadwal
     */
    public function setRunId($runId);

    /**
     * Get runId
     *
     * @return string
     */
    public function getRunId();

    /**
     * Set ruangId
     *
     * @param integer $ruangId
     *
     * @return Jadwal
     */
    public function setRuangId($ruangId);

    /**
     * Get ruangId
     *
     * @return integer
     */
    public function getRuangId();

    /**
     * Set hari
     *
     * @param string $hari
     *
     * @return Jadwal
     */
    public function setHari($hari);

    /**
     * Get hari
     *
     * @return string
     */
    public function getHari();

    /**
     * Set slotId
     *
     * @param integer $slotId
     *
     * @return Jadwal
     */
    public function setSlotId($slotId);

    /**
     * Get slotId
     *
     * @return integer
     */
    public function getSlotId();

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Jadwal
     */
    public function setIsActive($isActive);

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive();

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Jadwal
     */
    public function setIsDelete($isDelete);

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete();
}
