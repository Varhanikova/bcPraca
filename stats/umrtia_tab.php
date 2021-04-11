<?php
require_once "../DB_storage.php";
$storage = new DB_storage();
if($_GET['ktore']=="druha") {

$perc = $storage->mesacneUmrtiaNaKov();

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
" <tr>
            <th>Rok</th>
            <th>Mesiac</th>
            <th>percentá úmrtí na kovid</th>
            <th>percentá úmrtí s kovid</th>
        </tr>";
for ($i = $vypis - 1; $i < $limit; $i++) {
  echo " <tr>" ;
  $i=$i+3;
  echo
        "<td>  $perc[$i]  </td>";
  $i--;
       echo "<td>" .  $storage->getMesiac($perc[$i]) . "</td>";
$i-=2;
    echo   " <td>  $perc[$i]  %</td>";
$i++;
  echo    "  <td> $perc[$i] %</td>" .
   " </tr>";
    $i += 2;
}
}  else if($_GET['ktore'] == "prva" ) {
    $stat = $storage->getDeathsAtDate($_GET['a'], $_GET['b']);
}
if($_GET['ktore'] == "prva"  ) {
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

echo "</tr>";

if ($stat != '' && $pocet >0) {
    for ($i = $vypis - 1; $i < $limit; $i++) {
        echo "<tr>";

            $pam = $stat[$i]->getDatum();
            $pam1 = $stat[$i]->getPocNaKov();
            $pam2 = $stat[$i]->getPocSKov();
            $pam3 = $stat[$i]->getCelk();

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
        echo " </tr>";

    }
}
}