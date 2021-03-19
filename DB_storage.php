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
    public function getDeathsAtDate($datum, $datum2)
    {
        $stmt = $this->conn->query("SELECT  dat.id_datum,den,mesiac,rok,poc_umrti_kov,poc_s_kov,celk_poc_umrti FROM deaths_stat  
                join dat on(dat.id_datum=deaths_stat.id_datum) where deaths_stat.id_datum between '$datum' and '$datum2' ");
        $stat = [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $umrtie = new Umrtia_stat($dat, $row['poc_umrti_kov'], $row['poc_s_kov'], $row['celk_poc_umrti']);

            $stat[] = $umrtie;
        }
        return $stat;
    }

    public function isThere( $datumik,$datab)
    {
        $date = '';
        if($datab=="deaths_stat") {
            $stmt = $this->conn->query("SELECT id_datum FROM deaths_stat WHERE id_datum='$datumik'");
        } else if($datab=="hospitals_stat") {
            $stmt = $this->conn->query("SELECT id_datum FROM hospitals_stat WHERE id_datum='$datumik'");
        } else if($datab =="kazdodenne_stat") {
            $stmt = $this->conn->query("SELECT id_datum FROM kazdodenne_stat WHERE id_datum='$datumik'");
        } else {
            $stmt = $this->conn->query("SELECT id_datum FROM kraje_stat WHERE id_datum='$datumik'");
        }
        while ($row = $stmt->fetch()) {
            $date = $row['id_datum'];
        }
        return $date;
    }
    public function exportDeaths() {
        $stmt = $this->conn->query("SELECT * from deaths_stat");
        $fp = fopen('deaths_stat.csv', 'w');
        while ($row = $stmt->fetch()) {

            fputcsv($fp, $row);
        }
        fclose($fp);

    }
public function importDeaths($id_dat,$pockov,$pocskov,$celk){
    $death = new Umrtia_stat($id_dat,$pockov,$pocskov,$celk);
    $stmt = $this->pdo->prepare("INSERT INTO deaths_stat(id_datum, poc_umrti_kov, poc_s_kov, celk_poc_umrti) VALUES(?,?,?,?)");
    $stmt->execute([$death->getDatum(), $death->getPocNaKov(),$death->getPocSKov(),$death->getCelk()]);
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
    public function exportKraje() {
        $stmt = $this->conn->query("SELECT * from kraje_stat");
        $fp = fopen('kraje_stat.csv', 'w');
        while ($row = $stmt->fetch()) {

            fputcsv($fp, $row);
        }
        fclose($fp);

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
    public function exportHosp() {
        $stmt = $this->conn->query("SELECT * from hospitals_stat");

        $fp = fopen('hospitals_stat.csv', 'w');
        while ($row = $stmt->fetch()) {

            fputcsv($fp, $row);
        }
        fclose($fp);

    }
    public function exportPdfHosp() {

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
    public function exportDenne() {
        $stmt = $this->conn->query("SELECT * from kazdodenne_stat");
        $fp = fopen('kazdodenne_stat.csv', 'w');
        while ($row = $stmt->fetch()) {

            fputcsv($fp, $row);
        }
        fclose($fp);

    }

}