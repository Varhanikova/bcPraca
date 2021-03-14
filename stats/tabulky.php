<?php
require "../DB_storage.php";
$storagee = new DB_storage();
if ($_GET['ktore'] == "kazdodenne") {
    $p = $_GET['p'];
    $r = $_GET['r'];
    $stat = $storagee->getAllKazdodenneStat($_GET['a'], $_GET['b']);
} else if($_GET['ktore'] == "umrtia") {
    $stat = $storagee->getDeathsAtDate($_GET['a'], $_GET['b']);
} else if($_GET['ktore']=="kraje") {
    $t = $_GET['t'];
    $p = $_GET['p'];
    $r = $_GET['r'];
    $chcem = "and kraj = '" . $t . "' ";
    $stat = $storagee->getKrajeStat($_GET['a'], $_GET['b'], $chcem);
} else if($_GET['ktore']=="nemocnice"){
    $t = $_GET['t'];
    $chcem = "and okres = '" . $t . "' ";
    $stat = $storagee->getHospitalStat($_GET['a'], $_GET['b'], $chcem);
}

$m = $_GET['m'];
$n = $_GET['n'];
$o = $_GET['o'];
$s = $_GET['s'];
$vypis = intval($_GET['c']);
$pridane = $vypis + 14;

$pocet = sizeof($stat);

$limit = 0;
if ($pocet - $pridane > 0) {
    $limit = $vypis + 14;
} else {
    $kolko = $pocet - $vypis + 1;
    $limit = $pocet;
}
echo

" <tr>";

if ($stat != '') {
    echo "<th>Dátum</th>";
}
if($_GET['ktore']=="kraje") {
    if ($stat != '') {
        echo "<th>Kraj</th>";
    }
}
if($_GET['ktore']=="nemocnice") {
    if ($stat != '') {
        echo "<th>Názov nemocnice</th>";
    }
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

if ($_GET['ktore'] == "kazdodenne" || $_GET['ktore'] == "kraje") {
    if ($p != "" || $s != "") {
        echo "<th>$p</th>";
    }
    if (($r != "" || $s != "")) {
        echo "<th>$r</th>";
    }
}
echo "</tr>";
if ($stat != '') {
    for ($i = $vypis - 1; $i < $limit; $i++) {
        echo "<tr>";
        $pam = $stat[$i]->getDatum();
        if ($_GET['ktore'] == "umrtia") {
            $pam1 = $stat[$i]->getPocNaKov();
            $pam2 = $stat[$i]->getPocSKov();
            $pam3 = $stat[$i]->getCelk();
        } else if($_GET['ktore'] == "kazdodenne" ) {
            $pam1 = $stat[$i]->getPcrPotv();
            $pam2 = $stat[$i]->getPcrPoc();
            $pam3 = $stat[$i]->getPcrPoz();
            $pam4 = $stat[$i]->getAgPoc();
            $pam5 = $stat[$i]->getAgPoz();
        } else if( $_GET['ktore'] == "kraje") {
            $pam0 = $stat[$i]->getIdKraj();
            $pam1 = $stat[$i]->getAgVyk();
            $pam2 = $stat[$i]->getAgPoz();
            $pam3 = $stat[$i]->getPcrPoz();
            $pam4 = $stat[$i]->getNewcases();
            $pam5 = $stat[$i]->getPozCelk();
        } else if( $_GET['ktore'] == "nemocnice"){
            $pam1 = $stat[$i]->getObsadeneLozka();
            $pam2 = $stat[$i]->getPlucVent();
            $pam3 = $stat[$i]->getHospitalizovani();
            $pam0=$stat[$i]->getNemocnica();
        }


        echo "<td> $pam </td>";
        if($_GET['ktore']=="kraje" || $_GET['ktore'] == "nemocnice")  {
            echo "<td> $pam0</td>";
        }
        if ($m != "" || $s != "") {
            echo "<td> $pam1</td>";
        }
        if (($n != "" || $s != "")) {
            echo "<td> $pam2</td>";
        }
        if (($o != "" || $s != "")) {
            echo "<td> $pam3 </td>";
        }

        if ($_GET['ktore'] == "kazdodenne" ||$_GET['ktore'] == "kraje" ) {
            if (($p != "" || $s != "")) {
                echo "<td> $pam4 </td>";
            }
            if (($r != "" || $s != "")) {
                echo "<td> $pam5 </td>";
            }
        }
        echo " </tr>";
    }
}


