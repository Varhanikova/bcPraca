<?php
require "../DB_storage.php";
$storagee = new DB_storage();
if ($_GET['ktore'] == "kazdodenne") {
    $p = $_GET['p'];
    $r = $_GET['r'];
    $stat = $storagee->getAllKazdodenneStat($_GET['a'], $_GET['b']);
} else if($_GET['ktore'] == "umrtia" ) {
    $stat = $storagee->getDeathsAtDate($_GET['a'], $_GET['b']);

} else if($_GET['ktore'] == "nemocnice1" ) {
    $stat = $storagee->getAllHospital_stat();

} else if($_GET['ktore'] == "denne1" ) {
    $stat = $storagee->getAllDenne();
    $p = $_GET['p'];
    $r = $_GET['r'];

} else if($_GET['ktore']=="kraje1") {
    $stat = $storagee->getAllKraje();
    $t = $_GET['t'];
    $p = $_GET['p'];
    $r = $_GET['r'];
} else if( $_GET['ktore']=="umrtia1") {
    $stat = $storagee->getDeathsAll();
} else if($_GET['ktore']=="kraje") {
    $t = $_GET['t'];
    $p = $_GET['p'];
    $r = $_GET['r'];
    $chcem = "and kraj = '" . $t . "' ";
    $stat = $storagee->getKrajeStat($_GET['a'], $_GET['b'], $chcem);
} else if($_GET['ktore']=="nemocnice"){
    $t = $_GET['t'];
    $chcem =  $t;
    $stat = $storagee->getAllHospital_stat1($_GET['a'], $_GET['b'], $chcem);
}

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
    $kolko = $pocet - $vypis + 1;
    $limit = $pocet;
}
if($_GET['ktore']=="nemocnice" || $_GET['ktore']=="nemocnice1" ){
    $limit +=5*8;
}
echo

" <tr>";

if ($stat != '') {
    echo "<th>Dátum</th>";
}
if($_GET['ktore']=="kraje" || $_GET['ktore']=="kraje1") {
    if ($stat != '') {
        echo "<th>Kraj</th>";
    }
}
if($_GET['ktore']=="nemocnice" || $_GET['ktore']=="nemocnice1") {
    if ($stat != '') {
        echo "<th>Názov okresu</th>";
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

if ($_GET['ktore'] == "kazdodenne" || $_GET['ktore'] == "kraje" || $_GET['ktore'] == "denne1" || $_GET['ktore']=="kraje1") {
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
        echo "<tr >";

        if ($_GET['ktore'] == "umrtia" || $_GET['ktore'] == "umrtia1") {
            $pam = $stat[$i]->getDatum();
            $pam1 = $stat[$i]->getPocNaKov();
            $pam2 = $stat[$i]->getPocSKov();
            $pam3 = $stat[$i]->getCelk();
        } else if($_GET['ktore'] == "kazdodenne" || $_GET['ktore'] == "denne1" ) {
            $pam = $stat[$i]->getDatum();
            $pam1 = $stat[$i]->getPcrPotv();
            $pam2 = $stat[$i]->getPcrPoc();
            $pam3 = $stat[$i]->getPcrPoz();
            $pam4 = $stat[$i]->getAgPoc();
            $pam5 = $stat[$i]->getAgPoz();
        } else if( $_GET['ktore'] == "kraje" || $_GET['ktore']=="kraje1") {
            $pam = $stat[$i]->getDatum();
            $pam0 = $stat[$i]->getIdKraj();
            $pam1 = $stat[$i]->getAgVyk();
            $pam2 = $stat[$i]->getAgPoz();
            $pam3 = $stat[$i]->getPcrPoz();
            $pam4 = $stat[$i]->getNewcases();
            $pam5 = $stat[$i]->getPozCelk();
        } else if( $_GET['ktore'] == "nemocnice" || $_GET['ktore']=="nemocnice1"){
            $pam = $stat[$i];
            $pam1 = $stat[$i+2];
            $pam2 = $stat[$i+3];
            $pam3 = $stat[$i+4];
            $pam0=$stat[$i+1];
            $i+=4;
        }


        echo "<td> $pam </td>";
        if($_GET['ktore']=="kraje" || $_GET['ktore'] == "nemocnice" || $_GET['ktore']=="nemocnice1")  {
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

        if ($_GET['ktore'] == "kazdodenne" ||$_GET['ktore'] == "kraje" || $_GET['ktore'] == "denne1" || $_GET['ktore']=="kraje1" ) {
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


