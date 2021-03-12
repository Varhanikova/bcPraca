<?php
require "header.php";
require_once "DB_storage.php";
$storage = new DB_storage();
$array = $storage->getAllKrajeStat();

?>
<body>
<main class="container">
    <div class="col-md-12">
        <h3 class="pb-2 mb-2 fst-italic ">
            Štatistika testovania po krajoch:
        </h3>
        <p class="pb-4 mb-2 ">Začína 3.9.2020 a posledný záznam je z 13.2.2021 </p>
        <table >
            <tr>
                <th>Kraj</th>
                <th>Dátum</th>
                <th>Počet vykonaných Ag testov</th>
                <th>Počet pozitívnych Ag testov</th>
                <th>Počet pozitívnych PCR testov</th>
                <th>Počet nových prípadov</th>
                <th>Počet pozitívnych celkom</th>
            </tr>
            <?php for($i=0;$i<500;$i++) {?>
                <tr>
                    <td> <?=$array[$i]->getIdKraj()?></td>
                    <td ><?=$array[$i]->getIdDatum() ?></td>
                    <td><?=$array[$i]->getAgVyk() ?></td>
                    <td><?=$array[$i]->getAgPoz() ?></td>
                    <td><?=$array[$i]->getPcrPoz() ?></td>
                    <td><?=$array[$i]->getNewcases() ?></td>
                    <td><?=$array[$i]->getPozCelk() ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</main>
</body>





<?php
require "footer.php";
?>
