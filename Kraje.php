<?php
require "header.php";
$storage = new DB_storage();
$array = [];
$chcem = "";
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
    if ($_POST['date'] > $_POST['date2']) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nesprávne zadaný dátum!");
        </script>
    <?php }
    if ($chyba1 == 0) {
        $array = $storage->getKrajeStat($_POST['date'], $_POST['date2'], $chcem);
    }
} else {
    $array = $storage->getAllKraje();
}

if(isset($_POST['Send2']) ){
    $uloz = $_POST['krajelist2'];
    $krajemes = $storage->mesacneKraje( $_POST['krajelist2']);
    // header("Location: Kraje.php?#cislo2");
} else {
    $krajemes = $storage->mesacneKraje( "Bratislavský kraj");
    // header("Location: Kraje.php?#cislo2");
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
        var je = "<?= isset($_POST['Send1'])?>";
        if (je !== "") {
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
        } else {
            t = "";
            m = "Počet vykonaných AG testov";
            n = "Počet pozitívnych z AG testov";
            o = "Počet pozitívnych z PCR testov";
            p = "Počet nových prípadov";
            r = "Počet celkovo pozitívnych prípadov ";
            s = "všetko";
            a = '';
            b = '';
            ktore = "kraje1";
        }
        xhttp.open("GET", "stats/tabulky.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&p=" + p + "&r=" + r + "&s=" + s + "&ktore=" + ktore + "&t=" + t, true);
        xhttp.send();
    }
</script>

<body>
<main class="container">
    <h3 class="pb-4 mb-4 fst-italic border-bottom text-center ">
        Štatistika testovania po krajoch:
    </h3>
    <h4 class="pb-4 mb-4 fst-italic">
        Počty pri testovaniach:
    </h4>
</main>
<?php require "body.php";


?>

<main class="container cislo2">
    <h4 class="pb-4 mb-4 fst-italic  ">
        Mesačný priemer kraja:
    </h4>

    <form method="post">
        <div class="row">
            <div class="column col-lg-2">
                <label for="krajelist">Zvoľte kraj: </label>
            </div>
        </div>
        <div class="row">
            <div class="column col-lg-2">
                <select id="krajelist2" name="krajelist2" >
                    <?php for ($i = 0; $i < sizeof($kraje); $i++) {
                        if($kraje[$i]->getKraj()==$uloz) {?>
                            <option selected="selected"><?= $kraje[$i]->getKraj() ?> </option>
                        <?php } else { ?>
                            <option ><?= $kraje[$i]->getKraj() ?> </option>
                        <?php  } }?>

                </select>
            </div>
            <div class="row pb-4 mb-2">
                <div class="col-sm-1" >
                    <input type="submit" name="Send2" value="Filtruj">
                </div>
            </div>
        </div>
    </form>

    <p class='pb-4 mb-2 '></p>
    <table id="tu2">
        <tr>
            <th>Kraj</th>
            <th>Rok</th>
            <th>Mesiac</th>
            <th>Priemer z PCR pozitívnych</th>
            <th>Priemer z AG pozitívnych</th>
        </tr>

        <?php   for ($i = 0; $i < sizeof($krajemes); $i++) { ?>
            <tr>
                <td><?=$krajemes[$i]?></td>

                <td><?=$krajemes[$i+4]?></td>

                <td><?=$storage->getMesiac( $krajemes[$i+3])?> </td>

                <td> <?=$krajemes[$i+2]?></td>

                <td><?= $krajemes[$i+1]?></td>
            </tr> <?php
            $i+=4;
        }?>
    </table>
    <p class='pb-4 mb-2 '></p>
</main>
</body>


<?php
require "parts/footer.php";
?>
