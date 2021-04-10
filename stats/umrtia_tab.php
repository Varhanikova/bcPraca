<?php
require_once "../DB_storage.php";
$storage = new DB_storage();
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
} ?>
