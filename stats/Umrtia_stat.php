<?php


class Umrtia_stat
{
    private $datum;
    private $poc_s_kov;
    private $poc_na_kov;
    private $celk;
    public function __construct($datum,$poc_na_kov,$poc_s_kov,$celk){
        $this->datum=$datum;
        $this->poc_na_kov=$poc_na_kov;
        $this->poc_s_kov=$poc_s_kov;
        $this->celk=$celk;
    }

    /**
     * @return mixed
     */
    public function getCelk()
    {
        return $this->celk;
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
    public function getPocNaKov()
    {
        return $this->poc_na_kov;
    }

    /**
     * @return mixed
     */
    public function getPocSKov()
    {
        return $this->poc_s_kov;
    }

}