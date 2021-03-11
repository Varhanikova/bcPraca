<?php

declare(strict_types=1);
class Login
{
    private string $meno;
    private string $heslo;
    public function __construct($meno,$heslo)
    {
        $this->meno=$meno;
        $hash = md5($heslo);
        $this->heslo=$hash;
    }

    /**
     * @return string
     */
    public function getHeslo(): string
    {
        return $this->heslo;
    }

    /**
     * @return string
     */
    public function getMeno(): string
    {
        return $this->meno;
    }

    /**
     * @param string $heslo
     */
    public function setHeslo(string $heslo): void
    {
        $this->heslo = $heslo;
    }

    /**
     * @param string $meno
     */
    public function setMeno(string $meno): void
    {
        $this->meno = $meno;
    }
}