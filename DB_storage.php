<?php
require "Data/Datum.php";
require "login/Login.php";
require "stats/kraje_stat.php";
require "stats/Umrtia_stat.php";
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
        $stmt = $this->conn->query("SELECT * FROM dat");
        $datumy= [];
        while ($row = $stmt->fetch()) {
            $datum = new Datum($row['id_datum'],$row['rok'],$row['mesiac'],$row['den']);
            $datumy[] =$datum ;

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
    public function getAllDeaths() {
        $stmt = $this->conn->query("SELECT * FROM deaths_stat order by id_datum");
        $stat= [];
        while ($row = $stmt->fetch()) {
            $umrtie = new Umrtia_stat($row['id_datum'],$row['poc_umrti_kov'],$row['poc_s_kov'],$row['celk_poc_umrti']);
            $stat[] =$umrtie;
        }
        return $stat;
    }
    public function getAllKrajeStat()
    {
        $stmt = $this->conn->query("SELECT kraj,id_datum,ag_vykonanych,ag_poz,pcr_poz,newcases,poz_celk from kraje right join kraje_stat on(kraje.id_kraj=kraje_stat.id_kraj)");
        $stat= [];
        while ($row = $stmt->fetch()) {

            $kraj = new kraje_stat($row['kraj'],$row['id_datum'],$row['ag_vykonanych'],$row['ag_poz'],$row['pcr_poz'],$row['newcases'],$row['poz_celk']);
            $stat[] =$kraj;
        }
        return $stat;
    }
    public function addDates($dat) {
        $stmt = $this->conn->prepare("INSERT INTO dat VALUES(?)");
        $stmt->execute([$dat]);
    }
}