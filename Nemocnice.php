<?php
require "header.php";
$storage = new DB_storage();
$hosp = [];
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
        $chcem = $_POST['tags'] ;
    }
    if ($_POST['date'] > $_POST['date2']) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nesprávne zadaný dátum!");
        </script>
    <?php }

    if ($chyba1 == 0) {
            $hosp = $storage->getAllHospital_stat1($_POST['date'], $_POST['date2'], $chcem);
    }
} else {
    $hosp = $storage->getAllHospital_stat();
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
        var je = "<?= isset($_POST['Send1'])?>";
        if (je !== "") {
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
        <?php if(isset($_POST['Send1'])) { ?>
        var a = "<?=$_POST['date'] ?>";
        var b = '<?=$_POST['date2'] ?>';
        var t = "<?= ($_POST['tags']) ?>";
        <?php } ?>
        var ktore = "nemocnice";
        } else {
            n = "Počet osôb na pľúcnej ventilácii";
            m = "Počet obsadených lôžok";
            o = "Celkový počet hospitalizovaných";
            s = "všetko";
            a="";
            b="";
            t="Okres Bratislava I";
            var ktore = "nemocnice1";
        }
        xhttp.open("GET", "stats/tabulky.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&s=" + s + "&ktore=" + ktore + "&t=" + t, true);
        xhttp.send();
    }
</script>
<body>
<main class="container">

    <h3 class="pb-4 mb-4 fst-italic border-bottom text-center ">
        Štatistika nemocníc:
    </h3>
    <h4 class="pb-4 mb-4 fst-italic  ">
        Počty pacientov v nemocniciach vrámci okresu:
    </h4>
</main>
    <?php require "body.php" ;

    $nem = $storage->mesacneStat();
    ?>
<main class="container ">
    <div class="col-lg-12">
        <h4 class="pb-4 mb-4 fst-italic  ">
            Mesačná situácia v nemocniciach na Slovensku:
        </h4>
    </div>
    <p class='pb-4 mb-2 '></p>
    <table id="tu">
        <tr>
            <th>Rok</th>
            <th>Mesiac</th>
            <th>obsadené lôžka</th>
            <th>pľúcna ventilácia</th>
            <th>hospitalizovaní</th>
        </tr>

        <?php
        for ($i = 0; $i < sizeof($nem); $i++) { ?>
            <tr>
                <td><?= $nem[$i+4] ?></td>
                <td><?= $storage->getMesiac( $nem[$i+3]) ?></td>
                <td><?= $nem[$i] ?></td>
                <td><?= $nem[$i+1] ?></td>
                <td><?= $nem[$i+2] ?></td>

            </tr>
            <?php $i+=4;
       } ?>


    </table>
    <p class='pb-4 mb-2 '></p>
</main>
</body>

<?php
require "parts/footer.php";
?>
