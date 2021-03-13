<?php
require "header.php";
require_once "DB_storage.php";
$storage = new DB_storage();
$testy = "";
if (isset($_POST['Send1'])) {
    $chyba1 = 0;

    if (!isset($_POST['pcr_pot']) && !isset($_POST['pcr_poc']) && !isset($_POST['pcr_poz']) && !isset($_POST['ag_poz']) && !isset($_POST['ag_poc']) && !isset($_POST['v'])) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nezačiarkli ste žiadnu položku na zobrazenie!");
        </script>
        <?php
    }
    if (!empty($_POST['date']) && $storage->isThere($_POST['date'],"kazdodenne_stat") == '') {
        $chyba1 = 1;
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if (empty($_POST['date'])) {
        $_POST['date'] = "2020-03-06";
    }
    if (!empty($_POST['date2']) && $storage->isThere($_POST['date2'],"kazdodenne_stat") == '') {
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if ($_POST['date2'] == "") {
        $_POST['date2'] = "2021-02-18";
    }

    if ($chyba1 == 0) {
        if (isset($_POST['date2'])) {
            $testy = $storage->getAllKazdodenneStat($_POST['date'], $_POST['date2']);
        }
    }
}
?>
<script>
    var i = 1;

    function oznac_vsetky() {

        if (i == 0) {
            checkboxes = document.getElementById('pcr_pot');
            checkboxes.checked = true;
            checkboxes1 = document.getElementById('pcr_poc');
            checkboxes1.checked = true;
            checkboxes2 = document.getElementById('pcr_poz');
            checkboxes2.checked = true;
            checkboxes3 = document.getElementById('ag_poc');
            checkboxes3.checked = true;
            checkboxes4 = document.getElementById('ag_poz');
            checkboxes4.checked = true;
            i = 1;
        } else {
            checkboxes = document.getElementById('pcr_pot');
            checkboxes.checked = false;
            checkboxes1 = document.getElementById('pcr_poc');
            checkboxes1.checked = false;
            checkboxes2 = document.getElementById('pcr_poz');
            checkboxes2.checked = false;
            checkboxes3 = document.getElementById('ag_poc');
            checkboxes3.checked = false;
            checkboxes4 = document.getElementById('ag_poz');
            checkboxes4.checked = false;
            i = 0;
        }
    }

</script>
<body>
<main class="container">

    <h3 class="pb-4 mb-4 fst-italic border-bottom ">
        Štatistika každodenného testovania:
    </h3>
    <form method="post">
        <div class="row">
            <div class="col-lg-6">
                <label> Zvoľte si dátumy(voliteľné): </label> <br>
            </div>
            <div class="col-lg-6">
                <label > Začiarknite položky, ktoré sa majú zobraziť: </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                &emsp;<label> Od: </label>
                <input type="date" name="date" id="date" max="2021-02-18" min="2020-03-06" value="2020-03-06">
                <label> Do: </label>
                <input type="date" name="date2" id="date" value="2021-02-18" max="2021-02-18" min="2020-03-06"><br>
            </div>
            <div class="col-lg-6">
                &emsp; <input type="checkbox" id="pcr_pot" name="pcr_pot" value="pcr_pot" checked="checked">
                <label> Počet PCR potvrdených prípadov</label><br>
                &emsp; <input type="checkbox" id="pcr_poc" name="pcr_poc" value="pcr_poc" checked="checked">
                <label> Počet vykonaných PCR testov</label><br>
                &emsp; <input type="checkbox" id="pcr_poz" name="pcr_poz" value="pcr_poz" checked="checked">
                <label> Počet pozitívnych z PCR testov</label><br>
                &emsp; <input type="checkbox" id="ag_poc" name="ag_poc" value="ag_poc" checked="checked">
                <label> Počet vykonaných AG testov</label><br>
                &emsp; <input type="checkbox" id="ag_poz" name="ag_poz" value="ag_poz" checked="checked">
                <label> Počet pozitívnych z AG testov</label><br>
                <input onclick="oznac_vsetky()" type="checkbox" id="v" name="v" value="v" checked="checked">
                <label> všetky </label><br>
            </div>
        </div>
        <input type="submit" name="Send1" value="Zobraz">
    </form>

    <p class="pb-4 mb-2 "></p>
    <table>
        <tr>
            <?php if ($testy != '') { ?>
                <th>Dátum</th>
            <?php } ?>
            <?php if (isset($_POST['pcr_pot']) || isset($_POST['v'])) { ?>
                <th>Počet PCR potvrdených prípadov</th>
            <?php }
            if (isset($_POST['pcr_poc']) || isset($_POST['v'])) { ?>
                <th>Počet vykonaných PCR testov</th>
            <?php }
            if (isset($_POST['pcr_poz']) || isset($_POST['v'])) { ?>
                <th>Počet pozitívnych z PCR testov</th>
            <?php }
            if (isset($_POST['ag_poc']) || isset($_POST['v'])) { ?>
                <th>Počet vykonaných AG testov</th>
            <?php }
            if (isset($_POST['ag_poz']) || isset($_POST['v'])) { ?>
                <th>Počet pozitívnych z AG testov</th>
            <?php }
            ?>
        </tr>
        <?php if ($testy != '') {
            for ($i = 0; $i < sizeof($testy); $i++) { ?>
                <tr>
                    <td> <?= $testy[$i]->getDatum() ?></td>
                    <?php if (isset($_POST['pcr_pot']) || isset($_POST['v'])) { ?>
                        <td><?= $testy[$i]->getPcrPotv() ?></td>
                    <?php }
                    if (isset($_POST['pcr_poc']) || isset($_POST['v'])) { ?>

                        <td><?= $testy[$i]->getPcrPoc() ?></td>
                    <?php }
                    if (isset($_POST['pcr_poz']) || isset($_POST['v'])) { ?>
                        <td><?= $testy[$i]->getPcrPoz() ?></td>
                    <?php }
                    if (isset($_POST['ag_poc']) || isset($_POST['v'])) { ?>
                        <td><?= $testy[$i]->getAgPoc() ?></td>
                    <?php }
                    if (isset($_POST['ag_poz']) || isset($_POST['v'])) { ?>
                        <td><?= $testy[$i]->getAgPoz() ?></td>
                    <?php }

                    ?>
                </tr>
            <?php }
        } ?>
    </table>
</main>
</body>
<?php
require "footer.php";
?>
