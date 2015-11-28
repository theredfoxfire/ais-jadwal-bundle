<?php

namespace Ais\DosenBundle\Model;

Interface DosenInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();
    
    /**
     * Set kode
     *
     * @param string $kode
     *
     * @return Dosen
     */
    public function setKode($kode);

    /**
     * Get kode
     *
     * @return string
     */
    public function getKode();

    /**
     * Set nama
     *
     * @param string $nama
     *
     * @return Dosen
     */
    public function setNama($nama);

    /**
     * Get nama
     *
     * @return string
     */
    public function getNama();
    
    /**
     * Set namaSingkat
     *
     * @param string $namaSingkat
     *
     * @return Dosen
     */
    public function setNamaSingkat($namaSingkat);
    
    /**
     * Get namaSingkat
     *
     * @return string
     */
    public function getNamaSingkat();
    
    /**
     * Set userId
     *
     * @param string $userId
     *
     * @return Dosen
     */
    public function setUserId($userId);
    
    /**
     * Get userId
     *
     * @return string
     */
    public function getUserId();
    
    /**
     * Set email
     *
     * @param string $email
     *
     * @return Dosen
     */
    public function setEmail($email);
    
    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();
    
    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Dosen
     */
    public function setPhone($phone);
    
    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone();
    
    /**
     * Set foto
     *
     * @param string $foto
     *
     * @return Dosen
     */
    public function setFoto($foto);
    
    /**
     * Get foto
     *
     * @return string
     */
    public function getFoto();
    
    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Dosen
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
     * @return Dosen
     */
    public function setIsDelete($isDelete);
    
    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete();
}
