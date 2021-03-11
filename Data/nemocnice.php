<?php

declare(strict_types=1);

class nemocnice
{
    private $id;
    private $id_okres;
    private $nazov;

    public function __construct($id,$id_okres,$nazov)
    {
        $this->id=$id;
        $this->id_okres=$id_okres;
        $this->nazov=$nazov;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getIdOkres()
    {
        return $this->id_okres;
    }

    /**
     * @return mixed
     */
    public function getNazov()
    {
        return $this->nazov;
    }
}