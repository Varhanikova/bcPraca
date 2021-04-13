<?php

/**
 * súbor slúži na načítanie údajov z databázy do tabuliek pre štatistiku denného testovania
 * na filtrovanie tabuliek sa používa premenná $_GET
 */
require_once "../DB_storage.php";
$storage = new DB_storage();
/**
 * naplnenie spodnej tabuľky dátami
 */
if($_GET['ktore']=="druha"){
$perc = $storage->mesacnepozitivne();

$vypis = intval($_GET['c']);
$pridane = $vypis + 39;

$pocet = sizeof($perc);

$limit = 0;
if ($pocet - $pridane > 0) {
    $limit = $vypis + 39;
} else {
   // $kolko = $pocet - $vypis + 1;
    $limit = $pocet;
}
echo
"<tr>" .
            "<th>Rok</th>".
           " <th>Mesiac</th>".
          "  <th>percentá pozitívnych PCR z testovania</th>".
            "<th>percentá pozitívnych AG z testovania</th>".
        "</tr>";
for ($i = $vypis - 1; $i < $limit; $i++) {
  echo  "<tr>" ;
$i+=3;
    echo   " <td> $perc[$i] </td>" ;
    $i--;
     echo  " <td>" .$storage->getMesiac( $perc[$i]). "</td>";
$i-=2;
echo      "  <td>  $perc[$i] %</td>";
$i++;
   echo    " <td> $perc[$i]  %</td>".
   " </tr>";
  $i += 2;
}
    /**
     * naplnenie vrchnej tabuľky dátami
     */

} else if ($_GET['ktore'] == "prva") {
    $p = $_GET['p'];
    $r = $_GET['r'];
    $stat = $storage->getAllKazdodenneStat($_GET['a'], $_GET['b']);



    $m = $_GET['m'];
    $n = $_GET['n'];
    $o = $_GET['o'];
    $s = $_GET['s'];
    $vypis = intval($_GET['c']);
    $pridane = $vypis + 9;
    $pocet = sizeof($stat);

    $limit = 0;
    if ($pocet - $pridane > 0) {
        $limit = $vypis + 9;
    } else {
        $limit = $pocet;
    }

    echo

    " <tr>";

    if ($stat != '') {
        echo "<th>Dátum</th>";
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
        if ($p != "" || $s != "") {
            echo "<th>$p</th>";
        }
        if (($r != "" || $s != "")) {
            echo "<th>$r</th>";
        }
    echo "</tr>";
    if ($stat != '' && $pocet >0) {
        for ($i = $vypis - 1; $i < $limit; $i++) {
            echo "<tr>";

            $pam = $stat[$i]->getDatum();
            $pam1 = $stat[$i]->getPcrPotv();
            $pam2 = $stat[$i]->getPcrPoc();
            $pam3 = $stat[$i]->getPcrPoz();
            $pam4 = $stat[$i]->getAgPoc();
            $pam5 = $stat[$i]->getAgPoz();
            echo "<td> $pam </td>";
            if ($m != "" || $s != "") {
                echo "<td> $pam1</td>";
            }
            if (($n != "" || $s != "")) {
                echo "<td> $pam2</td>";
            }
            if (($o != "" || $s != "")) {
                echo "<td> $pam3 </td>";
            }

            if (($p != "" || $s != "")) {
                echo "<td> $pam4 </td>";
            }
            if (($r != "" || $s != "")) {
                echo "<td> $pam5 </td>";
            }

        echo " </tr>";
    }
    }

}
