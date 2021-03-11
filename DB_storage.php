<?php
require "Datum.php";
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
        $stmt = $this->conn->query("SELECT * FROM datum");
        $datumy= [];
        while ($row = $stmt->fetch()) {
            $datum = new Datum($row['id_datum'],$row['rok'],$row['mesiac'],$row['den']);
            $datumy[] =$datum ;

        }
        return $datumy;
    }

}