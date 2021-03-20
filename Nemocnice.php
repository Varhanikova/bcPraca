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
</script>
<body>
<main class="container">

    <h3 class="pb-4 mb-4 fst-italic border-bottom  ">
        Štatistika nemocníc:
    </h3>
</main>
    <?php require "body.php" ?>

</body>

<?php
require "parts/footer.php";
?>
