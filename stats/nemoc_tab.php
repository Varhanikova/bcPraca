<?php
require_once "../DB_storage.php";
$storage = new DB_storage();
$nem = $storage->nemocky($_GET['a'],$_GET['b']);

$vypis = intval($_GET['c']);
$pridane = $vypis + 39;
//if($vypis >1) {
  //  $vypis = $vypis + 36;
//}
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