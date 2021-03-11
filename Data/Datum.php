<?php

declare(strict_types=1);
class Datum
{
    private  $id_datum;
    private  $rok;
    private  $mesiac;
    private  $den;

    public function __construct($id,$rok, $mesiac, $den)
    {
        $this->id_datum = $id;
        $this->rok= $rok;
        $this->mesiac = $mesiac;
        $this->den  = $den;
    }

    /**
     * @return int
     */
    public function getDen()
    {
        return $this->den;
    }

    /**
     * @return string
     */
    public function getIdDatum()
    {
        return $this->id_datum;
    }

    /**
     * @return int
     */
    public function getMesiac()
    {
        return $this->mesiac;
    }

    /**
     * @return int
     */
    public function getRok()
    {
        return $this->rok;
    }


}