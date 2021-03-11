<?php

declare(strict_types=1);

class okresy
{
    private $id;
    private $okres;
    private $id_kraj;

    public function __construct($id,$id_kraj,$okres)
    {
        $this->id = $id;
        $this->okres=$okres;
        $this->id_kraj=$id_kraj;
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
    public function getIdKraj()
    {
        return $this->id_kraj;
    }

    /**
     * @return mixed
     */
    public function getOkres()
    {
        return $this->okres;
    }

}