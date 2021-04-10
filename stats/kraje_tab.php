<?php
require_once "../DB_storage.php";
$storage = new DB_storage();

if($_GET['a']){
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
         "   <th>Rok</th> ".
         "   <th>Mesiac</th>  ".
          "  <th>Priemer z PCR pozitívnych</th>  ".
           " <th>Priemer z AG pozitívnych</th> ".
       " </tr>";

          for ($i = $vypis - 1; $i < $limit; $i++) {
          echo " <tr>".
                "<td>$krajemes[$i]</td>";
                    $i+=4;
             echo  " <td>$krajemes[$i]</td>";
$i--;
             echo  " <td>" .$storage->getMesiac( $krajemes[$i]) . "</td>";
$i-=2;
             echo  " <td> $krajemes[$i]</td>";
$i++;
           echo   "  <td> $krajemes[$i]</td>".
            "</tr> ";
            $i+=2;
        }