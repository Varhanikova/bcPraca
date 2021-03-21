<?php
require "Data/Datum.php";
require "login/Login.php";
require "stats/kraje_stat.php";
require "stats/Umrtia_stat.php";
require "stats/hospitals_stat.php";
require "Data/nemocnice.php";
require "data/kraje.php";
require "data/okresy.php";
require "stats/kazdodenne_stat.php";

class DB_storage
{

    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("sqlsrv:server = tcp:bcpraca.database.windows.net,1433; Database = statistics", "bcpraca", "Bfmv1458");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print("Error connecting to SQL Server.");
            printf("%s", $e->getMessage());
            //die(print_r($e));
        }
    }

    public function controlPass($name, $pass)
    {
        $heslo = '';
        $stmt = $this->conn->query("SELECT password FROM logins WHERE username='$name'");
        while ($row = $stmt->fetch()) {
            $heslo = $row['password'];
        }
        $hash = md5($pass);
        if ($heslo == $hash) {
            return true;
        } else {
            return false;
        }
    }

    public function control($name, $pass)
    {
        $meno = '';
        $stmt = $this->conn->query("SELECT * FROM logins WHERE username='$name'");
        while ($row = $stmt->fetch()) {
            $meno = $row['username'];
        }
        if ($meno == $name) {
            if ($this->controlPass($name, $pass)) {
                return 0;
            } else {
                return 2;
            }
        } else {
            return 1;
        }
    }

    public function saveLogin($name, $heslo)
    {
        $log = new Login($name, $heslo);
        if ($this->control($name, $heslo) == 1) {
            $stmt = $this->conn->prepare("INSERT INTO logins(username,password) VALUES(?,?)");
            $stmt->execute([$log->getMeno(), $log->getHeslo()]);
            return true;
        } else {
            return false;
        }

    }

    // ---------------------Deaths---------
    public function getDeaths($dat, $dat2)
    {
        $stmt = $this->conn->query("select * from deaths_stat join dat on deaths_stat.id_datum = dat.id_datum where deaths_stat.id_datum between '$dat' and '$dat2'");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $umrtie = new Umrtia_stat($dat, $row['poc_umrti_kov'], $row['poc_s_kov'], $row['celk_poc_umrti']);

            $stat[] = $umrtie;
        }
        return $stat;
    }

    public function getDeathsAll()
    {
        $stmt = $this->conn->query("select * from deaths_stat join dat on deaths_stat.id_datum = dat.id_datum ");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $umrtie = new Umrtia_stat($dat, $row['poc_umrti_kov'], $row['poc_s_kov'], $row['celk_poc_umrti']);

            $stat[] = $umrtie;
        }
        return $stat;
    }

    public function getDeathsAtDate($datum, $datum2)
    {
        $stmt = $this->conn->query("SELECT dat.id_datum,den,mesiac,rok,poc_umrti_kov,poc_s_kov,celk_poc_umrti FROM deaths_stat  
                join dat on(dat.id_datum=deaths_stat.id_datum) where deaths_stat.id_datum between '$datum' and '$datum2' ");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $umrtie = new Umrtia_stat($dat, $row['poc_umrti_kov'], $row['poc_s_kov'], $row['celk_poc_umrti']);

            $stat[] = $umrtie;
        }
        return $stat;
    }

    public function isThere($datumik, $datab)
    {
        $date = '';
        if ($datab == "deaths_stat") {
            $stmt = $this->conn->query("SELECT id_datum FROM deaths_stat WHERE id_datum='$datumik'");
        } else if ($datab == "hospitals_stat") {
            $stmt = $this->conn->query("SELECT id_datum FROM hospitals_stat WHERE id_datum='$datumik'");
        } else if ($datab == "kazdodenne_stat") {
            $stmt = $this->conn->query("SELECT id_datum FROM kazdodenne_stat WHERE id_datum='$datumik'");
        } else {
            $stmt = $this->conn->query("SELECT id_datum FROM kraje_stat WHERE id_datum='$datumik'");
        }
        while ($row = $stmt->fetch()) {
            $date = $row['id_datum'];
        }
        return $date;
    }

    public function exportDeaths()
    {
        $stmt = $this->conn->query("SELECT * from deaths_stat");
        $fp = fopen('export/deaths_stat.csv', 'w');
        while ($row = $stmt->fetch()) {

            fputcsv($fp, $row);
        }
        fclose($fp);

    }

    public function importDeaths($id_dat, $rok, $mesiac, $den, $pockov, $pocskov, $celk)
    {
        $death = new Umrtia_stat($id_dat, $pockov, $pocskov, $celk);
        $this->checkDat($id_dat, $rok, $mesiac, $den);
        if ($row = ($this->conn->query("Select id_datum from deaths_stat where id_datum = '$id_dat'"))->fetch()) {
            return -1;
        } else {
            $stmt = $this->conn->prepare("INSERT INTO deaths_stat(id_datum, poc_umrti_kov, poc_s_kov, celk_poc_umrti) VALUES(?,?,?,?)");
            $stmt->execute([$death->getDatum(), $death->getPocNaKov(), $death->getPocSKov(), $death->getCelk()]);
            return 0;
        }
    }

//----------------kraje--------------------
    public function getKrajeStat($dat1, $dat2, $chcem)
    {
        $stmt = $this->conn->query("SELECT kraj,kraje_stat.id_datum,den,mesiac,rok,ag_vykonanych,ag_poz,pcr_poz,newcases,poz_celk from kraje
                join kraje_stat on(kraje.id_kraj=kraje_stat.id_kraj) 
                join dat on kraje_stat.id_datum = dat.id_datum where kraje_stat.id_datum between '$dat1' and '$dat2'
    $chcem ");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $kraj = new kraje_stat($row['kraj'], $dat, $row['ag_vykonanych'], $row['ag_poz'], $row['pcr_poz'], $row['newcases'], $row['poz_celk']);
            $stat[] = $kraj;
        }
        return $stat;
    }

    public function getAllKraje()
    {
        $stmt = $this->conn->query("SELECT * from kraje_stat join dat on kraje_stat.id_datum = dat.id_datum
                                            join kraje on kraje_stat.id_kraj = kraje.id_kraj");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $kraj = new kraje_stat($row['kraj'], $dat, $row['ag_vykonanych'], $row['ag_poz'], $row['pcr_poz'], $row['newcases'], $row['poz_celk']);
            $stat[] = $kraj;
        }
        return $stat;
    }

    public function getKraje()
    {
        $stmt = $this->conn->query("Select id_kraj,kraj from kraje ");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $kraj = new kraje($row['id_kraj'], $row['kraj']);
            $stat [] = $kraj;
        }
        return $stat;
    }

    public function exportKraje()
    {
        $stmt = $this->conn->query("SELECT * from kraje_stat");
        $fp = fopen('export/kraje_stat.csv', 'w');
        while ($row = $stmt->fetch()) {

            fputcsv($fp, $row);
        }
        fclose($fp);

    }

    public function checkDat($id_dat, $rok, $mes, $den)
    {
        if (($this->conn->query("select id_datum from dat where id_datum = '$id_dat'"))->fetch() == false) {
            $this->conn->query("insert into dat values('$id_dat','$rok','$mes','$den')");
        }
    }

    public function importKraje($idkraj, $id_dat, $rok, $mes, $den, $agvyk, $agpoz, $pcrpoz, $new, $celk)
    {
        $kraje = new kraje_stat($idkraj, $id_dat, $agvyk, $agpoz, $pcrpoz, $new, $celk);
        $this->checkDat($id_dat, $rok, $mes, $den);
        if ($row = ($this->conn->query("Select id_datum from kraje_stat where id_datum = '$id_dat'"))->fetch()) {
            return -1;
        } else {
            $stmt = $this->conn->prepare("INSERT INTO kraje_stat(id_kraj,id_datum,ag_vykonanych,ag_poz,pcr_poz,newcases,poz_celk) VALUES(?,?,?,?,?,?,?)");
            $stmt->execute([$kraje->getIdKraj(), $kraje->getDatum(), $kraje->getAgVyk(), $kraje->getAgPoz(), $kraje->getPcrPoz(), $kraje->getNewcases(), $kraje->getPozCelk()]);
            return 0;
        }
    }

    //------------hospitals---------
    public function getHospitalStat($datum, $dat2, $chcem)
    {
        $stmt = $this->conn->query("SELECT nazov,obsadene_lozka,pluc_ventilacia,hospitalizovani,
                dat.id_datum,den,mesiac, rok FROM hospitals_stat  join dat on(dat.id_datum=hospitals_stat.id_datum ) 
                join nemocnice n on hospitals_stat.id_nemocnica = n.id_nemocnica 
                join okresy o on n.id_okres = o.id_okres
                where hospitals_stat.id_datum between '$datum' and '$dat2' $chcem order by dat.id_datum");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $hospital = new hospitals_stat($dat, $row['nazov'], $row['obsadene_lozka'], $row['pluc_ventilacia'], $row['hospitalizovani']);
            $stat[] = $hospital;
        }
        return $stat;
    }

    public function getAllHospital_stat()
    {

        $stmt = $this->conn->query("SELECT * FROM hospitals_stat  join dat on(dat.id_datum=hospitals_stat.id_datum ) 
                join nemocnice n on hospitals_stat.id_nemocnica = n.id_nemocnica 
                join okresy o on n.id_okres = o.id_okres ");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $hospital = new hospitals_stat($dat, $row['nazov'], $row['obsadene_lozka'], $row['pluc_ventilacia'], $row['hospitalizovani']);
            $stat[] = $hospital;
        }
        return $stat;
    }

    public function getAllHospitals()
    {
        $stmt = $this->conn->query("SELECT *  FROM nemocnice");
        $nemoc = [];
        while ($row = $stmt->fetch()) {
            $nem = new nemocnice($row['id_nemocnica'], $row['id_okres'], $row['nazov']);
            $nemoc[] = $nem;
        }
        return $nemoc;
    }

    public function getOkresy()
    {
        $stmt = $this->conn->query("SELECT * from okresy ");
        $okresy = [];
        while ($row = $stmt->fetch()) {
            $okr = new okresy($row['id_okres'], $row['okres'], $row['id_kraj']);
            $okresy[] = $okr;
        }
        return $okresy;
    }

    public function exportHosp()
    {
        $stmt = $this->conn->query("SELECT * from hospitals_stat");

        $fp = fopen('export/hospitals_stat.csv', 'w');
        while ($row = $stmt->fetch()) {

            fputcsv($fp, $row);
        }
        fclose($fp);

    }

    public function importHosp($id_dat, $rok, $mes, $den, $idnem, $obs, $pluc, $hosp)
    {
        $hospitals = new hospitals_stat($id_dat, $idnem, $obs, $pluc, $hosp);
        $this->checkDat($id_dat, $rok, $mes, $den);
        if ($row = ($this->conn->query("Select id_datum from hospitals_stat where id_datum = '$id_dat'"))->fetch()) {
            return -1;
        } else {
            $stmt = $this->conn->prepare("INSERT INTO hospitals_stat(id_datum, id_nemocnica, obsadene_lozka, pluc_ventilacia, hospitalizovani) VALUES(?,?,?,?,?)");
            $stmt->execute([$hospitals->getDatum(), $hospitals->getNemocnica(), $hospitals->getObsadeneLozka(), $hospitals->getPlucVent(), $hospitals->getHospitalizovani()]);
            return 0;
        }
    }

    public function exportPdfHosp()
    {

    }

    //------------kazdodenne testovanie---------
    public function getAllKazdodenneStat($datum, $dat2)
    {
        $stmt = $this->conn->query("SELECT * from kazdodenne_stat join dat d on kazdodenne_stat.id_datum = d.id_datum
                where kazdodenne_stat.id_datum between '$datum' and '$dat2'");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $test = new kazdodenne_stat($dat, $row['pcr_potvrdene'], $row['pcr_poc'], $row['pcr_poz'], $row['ag_poc'], $row['ag_poz']);
            $stat[] = $test;
        }
        return $stat;

    }

    public function getAllDenne()
    {
        $stmt = $this->conn->query("select * from kazdodenne_stat join dat on kazdodenne_stat.id_datum = dat.id_datum");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $test = new kazdodenne_stat($dat, $row['pcr_potvrdene'], $row['pcr_poc'], $row['pcr_poz'], $row['ag_poc'], $row['ag_poz']);
            $stat[] = $test;
        }
        return $stat;
    }

    public function exportDenne()
    {
        $stmt = $this->conn->query("SELECT * from kazdodenne_stat");
        $fp = fopen('export/kazdodenne_stat.csv', 'w');
        while ($row = $stmt->fetch()) {

            fputcsv($fp, $row);
        }
        fclose($fp);

    }

    public function importDenne($id_dat, $rok, $mes, $den, $pcrpot, $pcrpoc, $pcrpoz, $agpoc, $agpoz)
    {
        $denne = new kazdodenne_stat($id_dat, $pcrpot, $pcrpoc, $pcrpoz, $agpoc, $agpoz);
        $this->checkDat($id_dat, $rok, $mes, $den);
        if ($row = ($this->conn->query("Select id_datum from kazdodenne_stat where id_datum = '$id_dat'"))->fetch()) {
            return -1;
        } else {
            $stmt = $this->conn->prepare("INSERT INTO kazdodenne_stat(id_datum, pcr_potvrdene, pcr_poc, pcr_poz, ag_poc, ag_poz) VALUES(?,?,?,?,?,?)");
            $stmt->execute([$denne->getDatum(), $denne->getPcrPotv(), $denne->getPcrPoc(), $denne->getPcrPoz(), $denne->getAgPoc(), $denne->getAgPoz()]);
            return 0;
        }
    }

    public function getDate($ktory, $db)
    {
        if ($ktory == "min") {
            if ($db == "deaths_stat") {
                $stmt = $this->conn->query("SELECT min(id_datum) as dat FROM deaths_stat ");
            } else if ($db == "hospitals_stat") {
                $stmt = $this->conn->query("SELECT min(id_datum) as dat FROM hospitals_stat  ");
            } else if ($db == "kazdodenne_stat") {
                $stmt = $this->conn->query("SELECT MIN(id_datum) as dat FROM kazdodenne_stat ");
            } else {
                $stmt = $this->conn->query("SELECT min(id_datum) as dat FROM kraje_stat ");
            }
        } else if ($ktory == "max") {
            if ($db == "deaths_stat") {
                $stmt = $this->conn->query("SELECT max(id_datum) as dat FROM deaths_stat ");
            } else if ($db == "hospitals_stat") {
                $stmt = $this->conn->query("SELECT max(id_datum) as dat FROM hospitals_stat  ");
            } else if ($db == "kazdodenne_stat") {
                $stmt = $this->conn->query("SELECT max(id_datum) as dat FROM kazdodenne_stat ");
            } else {
                $stmt = $this->conn->query("SELECT max(id_datum) as dat FROM kraje_stat ");
            }
        }
        while ($row = $stmt->fetch()) {
            $dat = $row['dat'];
        }
        return $dat;
    }
}