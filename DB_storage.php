<?php
require "Data/Datum.php";
require "login/Login.php";
require "stats/kraje_stat.php";
require "stats/Umrtia_stat.php";
require "stats/hospitals_stat.php";
require "Data/nemocnice.php";
class DB_storage
{

private  $conn;

public function __construct(){
    try {
      $this->conn = new PDO("sqlsrv:server = tcp:bcpraca.database.windows.net,1433; Database = statistics", "bcpraca", "Bfmv1458");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        printf("%s",$e->getMessage());
        //die(print_r($e));
    }
}

    public function getDatumy()
    {
        $eh = "rok,mesiac,den";
        $stmt = $this->conn->query("SELECT $eh , id_datum FROM dat");
        $datumy= [];
        while ($row = $stmt->fetch()) {
           // $datum = new Datum($row['id_datum'],$row['rok'],$row['mesiac'],$row['den']);
            $dat = $row['den'] . "." . $row['mesiac'] ."." . $row['rok'];
            $datumy[] =$dat ;

        }
        return $datumy;
    }
    public function controlPass($name,$pass) {
        $heslo = '';
        $stmt = $this->conn->query("SELECT password FROM logins WHERE username='$name'");
        while ($row = $stmt->fetch()) {
            $heslo = $row['password'];
        }
        $hash = md5($pass);
        if($heslo == $hash) {
            return true;
        } else {
            return false;
        }
    }
    public function control($name,$pass) {
        $meno = '';
        $stmt = $this->conn->query("SELECT * FROM logins WHERE username='$name'");
        while ($row = $stmt->fetch()) {
            $meno = $row['username'];
        }
        if($meno==$name) {
            if($this->controlPass($name,$pass)) {
                return 0;
            } else {
                return 2;
            }
        } else {
            return 1;
        }
    }
    public function saveLogin($name, $heslo){
        $log = new Login($name,$heslo);
        if($this->control($name,$heslo)==1) {
            $stmt = $this->conn->prepare("INSERT INTO logins(username,password) VALUES(?,?)");
            $stmt->execute([$log->getMeno(), $log->getHeslo()]);
            return true;
        } else {
            return false;
        }

    }
    // ---------------------Deaths---------
    public function getDeathsAtDate($datum,$datum2) {
        $stmt = $this->conn->query("SELECT  dat.id_datum,den,mesiac,rok,poc_umrti_kov,poc_s_kov,celk_poc_umrti FROM deaths_stat  
    join dat on(dat.id_datum=deaths_stat.id_datum) where deaths_stat.id_datum between '$datum' and '$datum2' ");
        $stat= [];
        while ($row = $stmt->fetch()) {
           $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $umrtie = new Umrtia_stat($dat,$row['poc_umrti_kov'],$row['poc_s_kov'],$row['celk_poc_umrti']);

            $stat[] =$umrtie;
        }
        return $stat;
    }
    public function isThere(string $datumik) {
        $date ='';
        $stmt = $this->conn->query("SELECT id_datum FROM deaths_stat WHERE id_datum='$datumik'");
        while ($row = $stmt->fetch()) {
            $date = $row['id_datum'];
        }
        return $date;
    }

//----------------kraje--------------------
    public function getAllKrajeStat($dat1,$dat2,$chcem)
    {
        $stmt = $this->conn->query("SELECT kraj,kraje_stat.id_datum,den,mesiac,rok,ag_vykonanych,ag_poz,pcr_poz,newcases,poz_celk from kraje
     join kraje_stat on(kraje.id_kraj=kraje_stat.id_kraj) 
    join dat on kraje_stat.id_datum = dat.id_datum where kraje_stat.id_datum between '$dat1' and '$dat2'
    $chcem ");
        $stat= [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $kraj = new kraje_stat($row['kraj'],$dat,$row['ag_vykonanych'],$row['ag_poz'],$row['pcr_poz'],$row['newcases'],$row['poz_celk']);
            $stat[] =$kraj;
        }
        return $stat;
    }//------------hospitals---------
    public function getAllHospitalStat($datum,$dat2,$chcem) {

        $stmt = $this->conn->query("SELECT nazov,obsadene_lozka,pluc_ventilacia,hospitalizovani,
       dat.id_datum,den,mesiac, rok FROM hospitals_stat  join dat on(dat.id_datum=hospitals_stat.id_datum ) 
    join nemocnice n on hospitals_stat.id_nemocnica = n.id_nemocnica 
    where hospitals_stat.id_datum between '$datum' and '$dat2' $chcem order by dat.id_datum");
        $stat= [];
        while ($row = $stmt->fetch()) {
            $dat = $row['den'] . "." . $row['mesiac'] . "." . $row['rok'];
            $hospital = new hospitals_stat($dat,$row['nazov'],$row['obsadene_lozka'],$row['pluc_ventilacia'],$row['hospitalizovani']);
            $stat[] =$hospital;
        }
        return $stat;
    }
    public function getAllKazdodenneStat() {


    }
public function getHospitals() {
    $stmt = $this->conn->query("SELECT *  FROM nemocnice");
    $nemoc= [];
    while ($row = $stmt->fetch()) {
        $nem = new nemocnice( $row['id_nemocnica'] , $row['id_okres'], $row['nazov']);
        $nemoc[] =$nem ;
    }
    return $nemoc;
}

}