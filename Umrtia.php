<?php
require "header.php";
$storage = new DB_storage();
$umrtia = [];

if (isset($_POST['Send1'])) {
    $chyba1 = 0;

    if (!isset($_POST['umrtia_na_kov']) && !isset($_POST['umrtia_s_kov']) && !isset($_POST['celk'])) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nezačiarkli ste žiadnu položku na zobrazenie!");
        </script>
        <?php
    }
    if ($_POST['date'] > $_POST['date2']) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nesprávne zadaný dátum!");
        </script
    <?php }

    if ($chyba1 == 0) {
        $umrtia = $storage->getDeathsAtDate($_POST['date'], $_POST['date2']);
    }
} else {
    $umrtia = $storage->getDeathsAll();
}

?>
<script>

    var j = 1;
    var size = parseInt('<?= sizeof($umrtia) ?>');
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
            var m = "<?= isset($_POST['umrtia_na_kov']) ?>";

            if (m !== "") {
                m = "počet úmrtí na kovid";
            }

            var n = "<?= isset($_POST['umrtia_s_kov']) ?>";

            if (n !== "") {
                n = "počet úmrtí s kovid";
            }

            var o = "<?= isset($_POST['celk']) ?>";

            if (o !== "") {
                o = "celkový počet úmrtí ";
            }

            var s = "<?= isset($_POST['v']) ?>";

            if (s !== "") {
                s = "všetko";
            }
            <?php if(isset($_POST['Send1'])) { ?>
            var a = "<?=$_POST['date'] ?>";
            var b = '<?=$_POST['date2'] ?>';
            <?php } ?>
            var ktore = "umrtia";
        } else {
            a = "";
            b = "";
            m = "počet úmrtí na kovid";
            n = "počet úmrtí s kovid";
            o = "celkový počet úmrtí ";
            s = "všetko";
            var ktore = "umrtia1";
        }

        xhttp.open("GET", "stats/tabulky.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&s=" + s + "&ktore=" + ktore, true);
        xhttp.send();
    }

</script>
<body>
<main class="container">
    <div class="col-lg-12">
        <h3 class="pb-4 mb-4 fst-italic border-bottom text-center">
            Štatistika úmrtí:
        </h3>
        <h4 class="pb-4 mb-4 fst-italic ">
            Počty úmrtí po dňoch:
        </h4>
    </div>
</main>

<?php require "body.php";
$na = $storage->mesacneUmrtiaNaKov();

?>
<main class="container ">
    <div class="col-lg-12">
        <h4 class="pb-4 mb-4 fst-italic  ">
            Percentá úmrtí po mesiacoch:
        </h4>
    </div>
    <p class='pb-4 mb-2 '></p>
    <table id="tu">
        <tr>
            <th>Rok</th>
            <th>Mesiac</th>
            <th>percentá úmrtí na kovid</th>
            <th>percentá úmrtí s kovid</th>
        </tr>

        <?php
        for ($i = 0; $i < sizeof($na); $i++) { ?>
            <tr>
                <td> <?= $na[$i + 3] ?> </td>
                <td><?= $storage->getMesiac($na[$i + 2]) ?> </td>

                <td> <?= $na[$i] ?> %</td>

                <td> <?= $na[$i + 1] ?> %</td>
            </tr>
            <?php $i += 3;
        } ?>


    </table>
    <p class='pb-4 mb-2 '></p>

</main>

</body>
<?php
require "parts/footer.php";
?>
