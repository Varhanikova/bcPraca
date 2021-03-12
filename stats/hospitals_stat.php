<?php


class hospitals_stat
{
    private $datum;
    private $nemocnica;
    private $obsadene_lozka;
    private $pluc_vent;
    private $hospitalizovani;

    public function __construct($datum,$nemocnica,$obsadene_lozka,$pluc_vent,$hospitalizovani) {
        $this->datum=$datum;
        $this->nemocnica=$nemocnica;
        $this->obsadene_lozka=$obsadene_lozka;
        $this->pluc_vent=$pluc_vent;
        $this->hospitalizovani=$hospitalizovani;
    }

    /**
     * @return mixed
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * @return mixed
     */
    public function getHospitalizovani()
    {
        return $this->hospitalizovani;
    }

    /**
     * @return mixed
     */
    public function getNemocnica()
    {
        return $this->nemocnica;
    }

    /**
     * @return mixed
     */
    public function getObsadeneLozka()
    {
        return $this->obsadene_lozka;
    }

    /**
     * @return mixed
     */
    public function getPlucVent()
    {
        return $this->pluc_vent;
    }

}