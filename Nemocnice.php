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
    if (!empty($_POST['date']) && $storage->isThere($_POST['date'], "hospitals_stat") == '') {
        $chyba1 = 1;
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if (empty($_POST['date'])) {
        $_POST['date'] = "2020-10-01";
    }
    if (($_POST['date2'] != "") && $storage->isThere($_POST['date2'], "hospitals_stat") == '') {
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

function odznac() {
    var obs = "<?= isset($_POST['obs']) ?>";
    var pluc = "<?= isset($_POST['pluc']) ?>";
    var hosp = "<?= isset($_POST['hosp']) ?>";
    checkboxes = document.getElementById('v');

    if (obs === "" || pluc === "" || hosp === "") {
        checkboxes.checked = false;
    }
}

    function oznac_vsetky(source) {
          checkboxes = document.getElementById('pluc');
            checkboxes.checked = source.checked;
            checkboxes1 = document.getElementById('hosp');
            checkboxes1.checked = source.checked;
            checkboxes2 = document.getElementById('obs');
            checkboxes2.checked = source.checked;
    }
</script>
<script>
    var j = 1;
    var size = parseInt('<?= sizeof($hosp) ?>');
    displayResults(j);

    function displayResults(j) {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tu").innerHTML = this.responseText;
            }
        };
        var c = j.toString();
        var m = "<?= isset($_POST['obs']) ?>";

        if (m !== "") {
            m = "Počet obsadených lôžok";
        }

        var n = "<?= isset($_POST['pluc']) ?>";

        if (n !== "") {
            n = "Počet osôb na pľúcnej ventilácii";
        }

        var o = "<?= isset($_POST['hosp']) ?>";

        if (o !== "") {
            o = "Celkový počet hospitalizovaných";
        }

        var s = "<?= isset($_POST['v']) ?>";

        if (s !== "") {
            s = "všetko";
        }

        var a = "<?=$_POST['date'] ?>";
        var b = '<?=$_POST['date2'] ?>';
        var t = "<?= ($_POST['tags']) ?>";
        var ktore = "nemocnice";

        xhttp.open("GET", "stats/tabulky.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&s=" + s + "&ktore=" + ktore + "&t=" + t, true);
        xhttp.send();
    }

    function next() {
        if (j < size - 14) {
            j += 15;
            displayResults(j);
        }
    }

    function previous() {
        if (j > 1) {
            j -= 15;
            displayResults(j);
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
                <label> Zvoľte si dátumy: </label> <br>
            </div>
            <div class="col-lg-4">
                <label for="umrtia_na_kov"> Začiarknite položky, ktoré sa majú zobraziť: </label>
            </div>
            <div class="col-lg-4">
                <label for="krajelist">Zvoľte okres:</label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <label> Od: </label>
                <input type="date" name="date" id="date" value="2020-10-01" max="2021-02-18" min="2020-10-01"><br>
                <label> Do: </label>
                <input type="date" name="date2" id="date2" value="2021-02-18" max="2021-02-18" min="2020-10-01"><br>
            </div>
            <div class="col-lg-4">
                &emsp; <input onclick="odznac()"  type="checkbox" id="obs" name="obs" value="obs" checked="checked">
                <label> Počet obsadených lôžok</label><br>
                &emsp; <input onclick="odznac()"  type="checkbox" id="pluc" name="pluc" value="pluc" checked="checked">
                <label> Počet osôb na pľúcnej ventilácii</label><br>
                &emsp; <input onclick="odznac()" type="checkbox" id="hosp" name="hosp" value="hos" checked="checked">
                <label> Celkový počet hospitalizovaných </label><br>
                <input onclick="oznac_vsetky(this)" type="checkbox" id="v" name="v" value="v" checked="checked">
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

    <p class='pb-4 mb-2 '></p>
    <table id="tu">

    </table>
    <p class='pb-4 mb-2 '></p>
    <?php if (isset($_POST['Send1'])) { ?>

        <div class="col-lg-11 text-center">
            <input id="prev" onclick="previous()" type="button" value="< späť"/>
            <input id="next" onclick="next()" type="button" value="ďalej >"/>
        </div><?php } ?>

</main>
</body>

<?php
require "footer.php";
?>
