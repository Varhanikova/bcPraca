<?php

declare(strict_types=1);

class kraje
{
    private $id;
    private $kraj;
     public function __construct($id,$kraj)
     {
         $this->id = $id;
         $this->kraj = $kraj;

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
    public function getKraj()
    {
        return $this->kraj;
    }

}