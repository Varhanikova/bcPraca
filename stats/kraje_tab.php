<?php
require_once "../DB_storage.php";
$storage = new DB_storage();
if($_GET['ktore']=="druha") {
    if ($_GET['a']) {
        $krajemes = $storage->mesacneKraje($_GET['a']);
    } else {
        $krajemes = $storage->mesacneKraje("Bratislavský kraj");
    }


    $vypis = intval($_GET['c']);
    $pridane = $vypis + 39;

    $pocet = sizeof($krajemes);

    $limit = 0;
    if ($pocet - $pridane > 0) {
        $limit = $vypis + 39;
    } else {
        $limit = $pocet;
    }
    echo "<tr>" .
        " <th>Kraj</th>" .
        "   <th>Rok</th> " .
        "   <th>Mesiac</th>  " .
        "  <th>Priemer z PCR pozitívnych</th>  " .
        " <th>Priemer z AG pozitívnych</th> " .
        " </tr>";

    for ($i = $vypis - 1; $i < $limit; $i++) {
        echo " <tr>" .
            "<td>$krajemes[$i]</td>";
        $i += 4;
        echo " <td>$krajemes[$i]</td>";
        $i--;
        echo " <td>" . $storage->getMesiac($krajemes[$i]) . "</td>";
        $i -= 2;
        echo " <td> $krajemes[$i]</td>";
        $i++;
        echo "  <td> $krajemes[$i]</td>" .
            "</tr> ";
        $i += 2;
    }
}   else if($_GET['ktore']=="prva") {
    $t = $_GET['t'];
    $p = $_GET['p'];
    $r = $_GET['r'];
    $chcem = "and kraj = '" . $t . "' ";
    $stat = $storage->getKrajeStat($_GET['a'], $_GET['b'], $chcem);
}
if($_GET['ktore']=="prva") {
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

    if ($stat != '') {
        echo "<th class='kraj'>Kraj</th>";
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
    if ($stat != '' && $pocet > 0) {
        for ($i = $vypis - 1; $i < $limit; $i++) {
            echo "<tr>";

            $pam = $stat[$i]->getDatum();
            $pam0 = $stat[$i]->getIdKraj();
            $pam1 = $stat[$i]->getAgVyk();
            $pam2 = $stat[$i]->getAgPoz();
            $pam3 = $stat[$i]->getPcrPoz();
            $pam4 = $stat[$i]->getNewcases();
            $pam5 = $stat[$i]->getPozCelk();


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