<?php
require "header.php";
require_once "DB_storage.php";
$storage = new DB_storage();
$testy = [];
if (isset($_POST['Send1'])) {
    $chyba1 = 0;

    if (!isset($_POST['pcr_pot']) && !isset($_POST['pcr_poc']) && !isset($_POST['pcr_poz']) && !isset($_POST['ag_poz']) && !isset($_POST['ag_poc']) && !isset($_POST['v'])) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nezačiarkli ste žiadnu položku na zobrazenie!");
        </script>
        <?php
    }
    if (!empty($_POST['date']) && $storage->isThere($_POST['date'], "kazdodenne_stat") == '') {
        $chyba1 = 1;
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if (empty($_POST['date'])) {
        $_POST['date'] = "2020-03-06";
    }
    if (!empty($_POST['date2']) && $storage->isThere($_POST['date2'], "kazdodenne_stat") == '') {
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
    var j = 1;
    var size = parseInt('<?= sizeof($testy) ?>');
    displayResults(j);

    function displayResults(j) {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tu").innerHTML = this.responseText;
            }
        };
        var c = j.toString();
        <?php if(isset($_POST['Send1'])) { ?>
        var a = "<?=$_POST['date'] ?>";
        var b = '<?=$_POST['date2'] ?>';
        <?php } ?>
        var m = "<?= isset($_POST['pcr_pot']) ?>";

        if (m !== "") {
            m = "Počet PCR potvrdených prípadov";
        }

        var n = "<?= isset($_POST['pcr_poc']) ?>";

        if (n !== "") {
            n = "Počet vykonaných PCR testov";
        }

        var o = "<?= isset($_POST['pcr_poz']) ?>";

        if (o !== "") {
            o = "Počet pozitívnych z PCR testov";
        }

        var p = "<?= isset($_POST['ag_poc']) ?>";

        if (p !== "") {
            p = "Počet vykonaných AG testov";
        }
        var r = "<?= isset($_POST['ag_poz']) ?>";

        if (r !== "") {
            r = "Počet pozitívnych z AG testov";
        }
        var s = "<?= isset($_POST['v']) ?>";

        if (s !== "") {
            s = "všetko";
        }
        var ktore = "kazdodenne";


        xhttp.open("GET", "stats/tabulky.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&p=" + p + "&r=" + r + "&s=" + s + "&ktore=" + ktore, true);
        xhttp.send();
    }
</script>
<body>
<main class="container">
    <h3 class="pb-4 mb-4 fst-italic border-bottom ">
        Štatistika každodenného testovania:
    </h3>
</main>
<?php require "body.php" ?>

</body>
<?php
require "parts/footer.php";
?>
