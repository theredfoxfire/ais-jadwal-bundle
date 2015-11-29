<?php

namespace Ais\JadwalBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Ais\JadwalBundle\Model\JadwalInterface;
/**
 * Jadwal
 */
class Jadwal implements JadwalInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $run_id;

    /**
     * @var integer
     */
    private $ruang_id;

    /**
     * @var string
     */
    private $hari;

    /**
     * @var integer
     */
    private $slot_id;

    /**
     * @var boolean
     */
    private $is_active;

    /**
     * @var boolean
     */
    private $is_delete;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set runId
     *
     * @param string $runId
     *
     * @return Jadwal
     */
    public function setRunId($runId)
    {
        $this->run_id = $runId;

        return $this;
    }

    /**
     * Get runId
     *
     * @return string
     */
    public function getRunId()
    {
        return $this->run_id;
    }

    /**
     * Set ruangId
     *
     * @param integer $ruangId
     *
     * @return Jadwal
     */
    public function setRuangId($ruangId)
    {
        $this->ruang_id = $ruangId;

        return $this;
    }

    /**
     * Get ruangId
     *
     * @return integer
     */
    public function getRuangId()
    {
        return $this->ruang_id;
    }

    /**
     * Set hari
     *
     * @param string $hari
     *
     * @return Jadwal
     */
    public function setHari($hari)
    {
        $this->hari = $hari;

        return $this;
    }

    /**
     * Get hari
     *
     * @return string
     */
    public function getHari()
    {
        return $this->hari;
    }

    /**
     * Set slotId
     *
     * @param integer $slotId
     *
     * @return Jadwal
     */
    public function setSlotId($slotId)
    {
        $this->slot_id = $slotId;

        return $this;
    }

    /**
     * Get slotId
     *
     * @return integer
     */
    public function getSlotId()
    {
        return $this->slot_id;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Jadwal
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Jadwal
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }
}

