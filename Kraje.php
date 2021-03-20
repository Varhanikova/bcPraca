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

    if (!empty($_POST['date2']) && $storage->isThere($_POST['date2'], "kraje_stat") == '') {
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if ($_POST['date2'] == "") {
        $_POST['date2'] = "2021-02-17";
    }

    if (!empty($_POST['date']) && $storage->isThere($_POST['date'], "kraje_stat") == '') {
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
    var j = 1;
    var size = parseInt('<?= sizeof($array) ?>');
    displayResults(j);

    function displayResults(j) {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tu").innerHTML = this.responseText;
            }
        };
        var c = j.toString();
        var a = "<?=$_POST['date'] ?>";
        var b = '<?=$_POST['date2'] ?>';

        var m = "<?= isset($_POST['ag_vyk']) ?>";

        if (m !== "") {
            m = "Počet vykonaných AG testov";
        }

        var n = "<?= isset($_POST['ag_poz']) ?>";

        if (n !== "") {
            n = "Počet pozitívnych z AG testov";
        }

        var o = "<?= isset($_POST['pcr_poz']) ?>";

        if (o !== "") {
            o = "Počet pozitívnych z PCR testov";
        }

        var p = "<?= isset($_POST['newcases']) ?>";

        if (p !== "") {
            p = "Počet nových prípadov";
        }
        var r = "<?= isset($_POST['poz_celk']) ?>";

        if (r !== "") {
            r = "Počet celkovo pozitívnych prípadov ";
        }
        var s = "<?= isset($_POST['v']) ?>";

        if (s !== "") {
            s = "všetko";
        }
        var ktore = "kraje";
        var t = "<?= ($_POST['krajelist']) ?>";

        xhttp.open("GET", "stats/tabulky.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&p=" + p + "&r=" + r + "&s=" + s + "&ktore=" + ktore + "&t=" + t, true);
        xhttp.send();
    }


</script>
<body>
<main class="container">
    <h3 class="pb-4 mb-4 fst-italic border-bottom ">
        Štatistika testovania po krajoch:
    </h3>
</main>
    <?php require "body.php" ?>


</body>


<?php
require "parts/footer.php";
?>
