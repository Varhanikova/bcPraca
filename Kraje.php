<?php
require "header.php";
require_once "DB_storage.php";
$storage = new DB_storage();
$array = "";
$chcem = "";
$od = "";
$do = "";
$kraje = $storage->getKraje();
if (isset($_POST['Send1'])) {
    $chyba1 = 0;
    if (!isset($_POST['poz_celk']) && !isset($_POST['pcr_poz']) && !isset($_POST['ag_poz']) && !isset($_POST['ag_vyk']) && !isset($_POST['newcases']) && !isset($_POST['v'])) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nezačiarkli ste žiadnu položku na zobrazenie!");
        </script>
        <?php
    }

    if (!empty($_POST['krajelist'])) {
        $chcem = "and kraj = '" . $_POST['krajelist'] . "' ";
    }

    if (!empty($_POST['date2']) && $storage->isThere($_POST['date2'],"kraje_stat") == '') {
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if ($_POST['date2'] == "") {
        $_POST['date2'] = "2021-02-17";
    }

    if (!empty($_POST['date']) && $storage->isThere($_POST['date'],"kraje_stat") == '') {
        $chyba1 = 1;
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if (empty($_POST['date'])) {
        $_POST['date'] = "2020-09-03";
    }
    if ($chyba1 == 1) {
        $od = $_POST['date'];
        $do = $_POST['date2'];

    } else {
        $array = $storage->getKrajeStat($_POST['date'], $_POST['date2'], $chcem);

    }
}

?>
<script>

    var i = 1;

    function oznac_vsetky() {
        if (i == 0) {
            checkboxes = document.getElementById('ag_vyk');
            checkboxes.checked = true;
            checkboxes1 = document.getElementById('ag_poz');
            checkboxes1.checked = true;
            checkboxes2 = document.getElementById('pcr_poz');
            checkboxes2.checked = true;
            checkboxes3 = document.getElementById('poz_celk');
            checkboxes3.checked = true;
            checkboxes4 = document.getElementById('newcases');
            checkboxes4.checked = true;
            i = 1;
        } else {
            checkboxes = document.getElementById('ag_vyk');
            checkboxes.checked = false;
            checkboxes1 = document.getElementById('ag_poz');
            checkboxes1.checked = false;
            checkboxes2 = document.getElementById('pcr_poz');
            checkboxes2.checked = false;
            checkboxes3 = document.getElementById('poz_celk');
            checkboxes3.checked = false;
            checkboxes4 = document.getElementById('newcases');
            checkboxes4.checked = false;
            i = 0;
        }
    }


</script>
<body>
<main class="container">
    <h3 class="pb-4 mb-4 fst-italic border-bottom ">
        Štatistika testovania po krajoch:
    </h3>

    <form method="post" autocomplete="off">
        <div class="row">
            <div class="col-lg-4">
                <label> Zvoľte si dátumy(voliteľné): </label>
            </div>
            <div class="col-lg-4">
                <label for="krajelist">Zvoľte kraj:</label>
            </div>
            <div class="col-lg-4">
                <label> Začiarknite položky, ktoré sa majú zobraziť: </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <label> Od: </label>
                <input type="date" name="date" id="date" value="2020-09-03" min="2020-09-03" max="2021-02-17"> <br>
                <label> Do: </label>
                <input type="date" name="date2" id="date2" value="2021-02-17" min="2020-09-03" max="2021-02-17">
                <br><br>
            </div>
            <div class="col-lg-4">
                <select id="krajelist" name="krajelist">
                    <?php for ($i = 0; $i < sizeof($kraje); $i++) { ?>
                        <option value="<?= $kraje[$i]->getKraj() ?>"><?= $kraje[$i]->getKraj() ?> </option>
                    <?php } ?>
                    <option value="všetky">všetky</option>
                </select>
            </div>
            <div class="col-lg-4">


                &emsp; <input type="checkbox" id="ag_vyk" name="ag_vyk" value="av" checked="checked">
                <label> Počet vykonaných Ag testov</label><br>
                &emsp; <input type="checkbox" id="ag_poz" name="ag_poz" value="ap" checked="checked">
                <label> Počet pozitívnych Ag testov</label><br>
                &emsp; <input type="checkbox" id="pcr_poz" name="pcr_poz" value="pp" checked="checked">
                <label> Počet pozitívnych PCR testov </label><br>
                &emsp; <input type="checkbox" id="newcases" name="newcases" value="nc" checked="checked">
                <label> Počet nových prípadov</label><br>
                &emsp; <input type="checkbox" id="poz_celk" name="poz_celk" value="pc" checked="checked">
                <label> Počet pozitívnych celkom</label><br>
                <input onclick="oznac_vsetky()" type="checkbox" id="v" name="v" checked="checked">
                <label> všetky </label><br>
            </div>

        </div>

        <input type="submit" name="Send1" value="Zobraz">
    </form>
    <p class="pb-4 mb-2 "></p>
    <table>
        <tr>
            <?php if ($array != '') { ?>
                <th>Kraje</th>
                <th>Dátum</th>
            <?php } ?>
            <?php if (isset($_POST['ag_vyk']) || isset($_POST['v'])) { ?>
                <th>Počet vykonaných Ag testov</th>
            <?php }
            if (isset($_POST['ag_poz']) || isset($_POST['v'])) { ?>
                <th>Počet pozitívnych Ag testov</th>
            <?php }
            if (isset($_POST['pcr_poz']) || isset($_POST['v'])) { ?>
                <th>Počet pozitívnych PCR testov</th>
            <?php }
            if (isset($_POST['newcases']) || isset($_POST['v'])) { ?>
                <th>Počet nových prípadov</th>
            <?php }
            if (isset($_POST['poz_celk']) || isset($_POST['v'])) { ?>
                <th>Počet pozitívnych celkom</th>
            <?php } ?>

        </tr>
        <?php if ($array != '') {
            for ($i = 0; $i < sizeof($array); $i++) { ?>
                <tr>
                    <td> <?= $array[$i]->getIdKraj() ?></td>
                    <td><?= $array[$i]->getIdDatum() ?></td>
                    <?php if (isset($_POST['ag_vyk']) || isset($_POST['v'])) { ?>
                        <td><?= $array[$i]->getAgVyk() ?></td>
                    <?php }
                    if (isset($_POST['ag_poz']) || isset($_POST['v'])) { ?>
                        <td><?= $array[$i]->getAgPoz() ?></td>
                    <?php }
                    if (isset($_POST['pcr_poz']) || isset($_POST['v'])) { ?>
                        <td><?= $array[$i]->getPcrPoz() ?></td>
                    <?php }
                    if (isset($_POST['newcases']) || isset($_POST['v'])) { ?>
                        <td><?= $array[$i]->getNewcases() ?></td>
                    <?php }
                    if (isset($_POST['poz_celk']) || isset($_POST['v'])) { ?>
                        <td><?= $array[$i]->getPozCelk() ?></td>
                    <?php } ?>
                </tr>
            <?php }
        } ?>
    </table>


</main>
</body>


<?php
require "footer.php";
?>
