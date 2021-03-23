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

        xhttp.open("GET", "stats/tabulky.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&s=" + s + "&ktore=" + ktore, true);
        xhttp.send();
    }

</script>
<body>
<main class="container">
    <div class="col-lg-12">
        <h3 class="pb-4 mb-4 fst-italic border-bottom ">
            Štatistika úmrtí:
        </h3>
    </div>
</main>
</body>
<?php require "body.php" ;
$na = $storage->mesacneUmrtiaNaKov();
//$s= $storage->mesacneUmrtiaSKov();
$mesiace = ["september","","október","","november","","december","","január","","február"];
?>
<main class="container " >

    <div class="col-lg-12">
        <h3 class="pb-4 mb-4 fst-italic  ">
            Percentuálne vyjadrené mesačné úmrtia:
        </h3>
    </div>
        <p class='pb-4 mb-2 '></p>
        <table id="tu">
                <tr>
                    <th>Mesiac</th>
                    <th>percentá úmrtí na kovid</th>
                    <th>percentá úmrtí s kovid</th>
                </tr>

                <?php
                for($i=0;$i<sizeof($na);$i++) { ?>
            <tr>

                <td><?= $mesiace[$i] ?> </td>

                    <td> <?= $na[$i] ?> %</td>
                <?php $i++; ?>
                    <td> <?= $na[$i] ?> %</td>
            </tr>
             <?php   }?>


        </table>
        <p class='pb-4 mb-2 '></p>

</main>




</body>
<?php
require "parts/footer.php";
?>
