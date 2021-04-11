<?php
require_once "../DB_storage.php";
$storage = new DB_storage();
if($_GET['ktore']=="druha"){
$nem = $storage->nemocky($_GET['a'],$_GET['b']);

$vypis = intval($_GET['c']);
$pridane = $vypis + 39;
$pocet = sizeof($nem);

$limit = 0;
if ($pocet - $pridane > 0) {
    $limit = $vypis + 39;
} else {
    $kolko = $pocet - $vypis + 1;
    $limit = $pocet;
}


echo
 "<tr>" .
           " <th>Datum</th>" .
           " <th>obsadené lôžka</th>".
          "  <th>pľúcna ventilácia</th>".
           " <th>hospitalizovaní</th>".
       " </tr>";


        for ($i = $vypis - 1; $i < $limit; $i++) {
        echo   " <tr>" .
               " <td> $nem[$i] </td>" ;
        $i++;
        echo
               " <td>$nem[$i] </td>";
         $i++;
         echo
               " <td> $nem[$i]</td>";
         $i++;
           echo   "  <td> $nem[$i] </td>";

           echo "</tr>";

       }
} else if($_GET['ktore']=="prva"){
    $t = $_GET['t'];
    $chcem =  $t;
    $stat = $storage->getAllHospital_stat1($_GET['a'], $_GET['b'], $chcem);
}
if($_GET['ktore']=="prva" ){

    $m = $_GET['m'];
    $n = $_GET['n'];
    $o = $_GET['o'];
    $s = $_GET['s'];
    $vypis = intval($_GET['c']);
    $pridane = $vypis + 9;
        $pridane +=5*8;

    $pocet = sizeof($stat);

    $limit = 0;
    if ($pocet - $pridane > 0) {
        $limit = $vypis + 9;
            $limit +=5*8;
    } else {
        $limit = $pocet;
    }


    echo

    " <tr>";

    if ($stat != '') {
        echo "<th>Dátum</th>";
    }
        if ($stat != '') {
            echo "<th>Názov okresu</th>";
        }
    if ($m != "" || $s != "") {
        echo "<th>$m</th>";
    }
    if ($n != "" || $s != "") {
        echo "<th>$n</th>";
    }
    if (($o != "" || $s != "")) {
        echo "<th>$o</th>";
    }

    echo "</tr>";

    if ($stat != '' && $pocet >0) {
        for ($i = $vypis - 1; $i < $limit; $i++) {
            echo "<tr>";
            $pam = $stat[$i];
            $pam1 = $stat[$i + 2];
            $pam2 = $stat[$i + 3];
            $pam3 = $stat[$i + 4];
            $pam0 = $stat[$i + 1];
            $i += 4;
            echo "<td> $pam </td>";
                echo "<td> $pam0</td>";

            if ($m != "" || $s != "") {
                echo "<td> $pam1</td>";
            }
            if (($n != "" || $s != "")) {
                echo "<td> $pam2</td>";
            }
            if (($o != "" || $s != "")) {
                echo "<td> $pam3 </td>";
            }
            echo " </tr>";
        }
    }
}