<?php

class DB_storage
{
 private  $username= 'root';
 private  $password = 'pass123';
 private  $dbName = 'statistics';
private  $host = "35.198.148.237";

    protected function  connect()
    {
        try {
            $dsn = sprintf('mysql:unix_socket=/cloudsql/hello-php:bcpraca;charset=utf8');
            $conn = new PDO($dsn,$this->username,$this->password);

        } catch (PDOException $E) {
            ECHO "connection failed: " . $E->getMessage();
        }
        return $conn;
    }
    public function getAll()
    {
        $stmt = $this->connect()->query("SELECT * FROM datumy");
        $datumy = [];
        while ($row = $stmt->fetch()) {
            $datumy[] = $row['id_datum'] .  $row['rok'] . $row['mesiac'] . $row['den'];

        }
        return $datumy;
    }

}