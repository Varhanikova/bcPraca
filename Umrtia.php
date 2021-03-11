<?php
require "header.php";
$storage = new DB_storage();
$umrtia = $storage->getAllDeaths();

?>

<body>
<main class="container">
<div class="col-md-10">
<h3 class="pb-2 mb-2 fst-italic ">
    Štatistika úmrtí:
</h3>
<p class="pb-4 mb-2 ">Začína 24.09.2020 a posledný záznam je z 13.2.2021</p>
<table>
    <tr>
        <th>Dátum</th>
        <th>Počet úmrtí na kovid</th>
        <th>Počet úmrtí s kovid</th>
        <th>Celkový počet úmrtí</th>
    </tr>
    <?php for($i=0;$i<120;$i++) {?>
    <tr>
        <td> <?= $umrtia[$i]->getDatum() ?></td>
        <td><?=$umrtia[$i]->getPocNaKov() ?></td>
        <td><?=$umrtia[$i]->getPocSKov() ?></td>
        <td><?=$umrtia[$i]->getCelk() ?></td>
    </tr>
    <?php } ?>
    </table>
</div>

</main>
</body>




<?php
require "footer.php";
?>
