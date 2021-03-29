<?php
require_once "../DB_storage.php";
$storage = new DB_storage();

if($_GET['a']){
    $krajemes = $storage->mesacneKraje($_GET['a']);
} else {
    $krajemes = $storage->mesacneKraje("Bratislavský kraj");
}
 echo "<tr>" .
           " <th>Kraj</th>" .
         "   <th>Rok</th> ".
         "   <th>Mesiac</th>  ".
          "  <th>Priemer z PCR pozitívnych</th>  ".
           " <th>Priemer z AG pozitívnych</th> ".
       " </tr>";

          for ($i = 0; $i < sizeof($krajemes); $i++) {
          echo " <tr>".
                "<td>$krajemes[$i]</td>";
                    $i+=4;
             echo  " <td>$krajemes[$i]</td>";
$i--;
             echo  " <td>" .$storage->getMesiac( $krajemes[$i]) . "</td>";
$i--;
             echo  " <td> $krajemes[$i]</td>";
$i--;
           echo   "  <td> $krajemes[$i]</td>".
            "</tr> ";
            $i+=3;
        }