<?php


class kazdodenne_stat
{
    private $datum;
    private $pcr_potv;
    private $pcr_poz;
    private $ag_poc;
    private $ag_poz;
public function __construct($datum,$pcr_poz,$ag_poz,$ag_poc,$pcr_potv)
{
    $this->datum=$datum;
    $this->pcr_potv=$pcr_potv;
    $this->pcr_poz=$pcr_poz;
    $this->ag_poc=$ag_poc;
    $this->ag_poz=$ag_poz;
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
    public function getPcrPoz()
    {
        return $this->pcr_poz;
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
    public function getAgPoc()
    {
        return $this->ag_poc;
    }

    /**
     * @return mixed
     */
    public function getPcrPotv()
    {
        return $this->pcr_potv;
    }

}