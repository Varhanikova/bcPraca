<?php
require "header.php";
$storage = new DB_storage();
$hosp = "";
$nemocnice = $storage->getAllHospitals();
$okresy = $storage->getOkresy();
$chcem = "";

if (isset($_POST['Send1'])) {
    $chyba1 = 0;

    if (!isset($_POST['obs']) && !isset($_POST['pluc']) && !isset($_POST['hosp']) && !isset($_POST['v'])) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nezačiarkli ste žiadnu položku na zobrazenie!");
        </script>
        <?php
    }
    if (!empty($_POST['tags'])) {
        $chcem = "and okres = '" . $_POST['tags'] . "' ";
    }
    if (!empty($_POST['date']) && $storage->isThere($_POST['date'],"hospitals_stat") == '') {
        $chyba1 = 1;
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if (empty($_POST['date'])) {
        $_POST['date'] = "2020-10-01";
    }
    if (($_POST['date2'] != "") && $storage->isThere($_POST['date2'],"hospitals_stat") == '') {
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if ($_POST['date2'] == "") {
        $_POST['date2'] = "2021-02-18";
    }

    if ($chyba1 == 0) {
        if (isset($_POST['date2'])) {
            $hosp = $storage->getHospitalStat($_POST['date'], $_POST['date2'], $chcem);
        }
    }
}

?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function () {
        var availableTags = [
            <?php for($i = 0;$i < sizeof($okresy);$i++) { $str = $okresy[$i]->getOkres();?>
            "<?=$str?> ",
            <?php  } ?>

        ];
        $("#tags").autocomplete({
            source: availableTags
        });
    });

    var i = 1;

    function oznac_vsetky() {
        if (i == 0) {
            checkboxes = document.getElementById('pluc');
            checkboxes.checked = true;
            checkboxes1 = document.getElementById('hosp');
            checkboxes1.checked = true;
            checkboxes2 = document.getElementById('obs');
            checkboxes2.checked = true;
            i = 1;
        } else {
            checkboxes = document.getElementById('pluc');
            checkboxes.checked = false;
            checkboxes1 = document.getElementById('hosp');
            checkboxes1.checked = false;
            checkboxes2 = document.getElementById('obs');
            checkboxes2.checked = false;
            i = 0;
        }
    }
</script>

<body>
<main class="container">

    <h3 class="pb-4 mb-4 fst-italic border-bottom  ">
        Štatistika nemocníc:
    </h3>
    <form method="post">
        <div class="row">
            <div class="col-lg-4">
                <label> Zvoľte si dátumy(voliteľné): </label> <br>
            </div>
            <div class="col-lg-4">
                <label for="umrtia_na_kov"> Začiarknite položky, ktoré sa majú zobraziť: </label>
            </div>
            <div class="col-lg-4">
                <label for="krajelist" >Zvoľte okres:</label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <label> Od: </label>
                <input type="date" name="date" id="date" value="2020-10-01" max="2021-02-18" min="2020-10-01"><br>
                <label> Do: </label>
                <input type="date" name="date2" id="date" value="2021-02-18" max="2021-02-18" min="2020-10-01"><br>
            </div>
            <div class="col-lg-4">
                &emsp; <input type="checkbox" id="obs" name="obs" value="obs" checked="checked">
                <label> Počet obsadených lôžok</label><br>
                &emsp; <input type="checkbox" id="pluc" name="pluc" value="pluc" checked="checked">
                <label> Počet osôb na pľúcnej ventilácii</label><br>
                &emsp; <input type="checkbox" id="hosp" name="hosp" value="hos" checked="checked">
                <label> Celkový počet hospitalizovaných </label><br>
                <input onclick="oznac_vsetky()" type="checkbox" id="v" name="v" value="v" checked="checked">
                <label> všetky </label><br>
            </div>
            <div class="col-lg-4">
                <div class="ui-widget">
                    <label for="tags"> </label>
                    <input id="tags" name="tags" value="Okres Bratislava I">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2">
                <input type="submit" name="Send1" value="Zobraz">
            </div>
        </div>
    </form>
    <p class="pb-4 mb-2 "></p>
    <table>
        <tr>
            <?php if ($hosp != '') { ?>
                <th>Dátum</th>
                <th>Názov nemocnice</th>

            <?php }
            if (isset($_POST['obs']) || isset($_POST['v'])) { ?>
                <th>Počet obsadených lôžok</th>
            <?php }
            if (isset($_POST['pluc']) || isset($_POST['v'])) { ?>
                <th>Počet osôb na pľúcnej ventilácii</th>
            <?php }
            if (isset($_POST['hosp']) || isset($_POST['v'])) { ?>
                <th>Celkový počet hospitalizovaných</th>
            <?php } ?>
        </tr>
        <?php
        if ($hosp != '') {
            for ($i = 0; $i < sizeof($hosp); $i++) { ?>
                <tr>
                    <td> <?= $hosp[$i]->getDatum() ?></td>
                    <td><?= $hosp[$i]->getNemocnica() ?></td>
                    <?php if (isset($_POST['obs']) || isset($_POST['v'])) { ?>
                        <td><?= $hosp[$i]->getObsadeneLozka() ?></td>
                    <?php }
                    if (isset($_POST['pluc']) || isset($_POST['v'])) { ?>
                        <td><?= $hosp[$i]->getPlucVent() ?></td>
                    <?php }
                    if (isset($_POST['hosp']) || isset($_POST['v'])) { ?>
                        <td><?= $hosp[$i]->getHospitalizovani() ?></td>
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
