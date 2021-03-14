<?php


class kraje_stat
{
    private $id_kraj;
    private $id_datum;
    private $ag_vyk;
    private $ag_poz;
    private $pcr_poz;
    private $newcases;
    private $poz_celk;

    public function __construct($id_kraj, $id_datum, $ag_vyk, $ag_poz, $pcr_poz, $newcases, $poz_celk)
    {
        $this->id_kraj = $id_kraj;
        $this->id_datum = $id_datum;
        $this->ag_vyk = $ag_vyk;
        $this->ag_poz = $ag_poz;
        $this->pcr_poz = $pcr_poz;
        $this->newcases = $newcases;
        $this->poz_celk = $poz_celk;
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
    public function getDatum()
    {
        return $this->id_datum;
    }

    /**
     * @return mixed
     */
    public function getAgPoz()
    {
        return $this->ag_poz;
    }

    /**
     * @return mixed
     */
    public function getAgVyk()
    {
        return $this->ag_vyk;
    }

    /**
     * @return mixed
     */
    public function getNewcases()
    {
        return $this->newcases;
    }

    /**
     * @return mixed
     */
    public function getPcrPoz()
    {
        return $this->pcr_poz;
    }

    /**
     * @return mixed
     */
    public function getPozCelk()
    {
        return $this->poz_celk;
    }
}