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
    function odznac() {
        var a = "<?= isset($_POST['ag_vyk']) ?>";
        var b = "<?= isset($_POST['ag_poz']) ?>";
        var c = "<?= isset($_POST['pcr_poz']) ?>";
        var d = "<?= isset($_POST['poz_celk']) ?>";
        var e = "<?= isset($_POST['newcases']) ?>";
        checkboxes = document.getElementById('v');

        if (a === "" || b === "" || c === "" || d === "" || e === "") {
            checkboxes.checked = false;
        }
    }
    function oznac_vsetky(source) {
        checkboxes = document.getElementById('ag_vyk');
        checkboxes.checked = source.checked;
        checkboxes1 = document.getElementById('ag_poz');
        checkboxes1.checked = source.checked;
        checkboxes2 = document.getElementById('pcr_poz');
        checkboxes2.checked = source.checked;
        checkboxes3 = document.getElementById('poz_celk');
        checkboxes3.checked = source.checked;
        checkboxes4 = document.getElementById('newcases');
        checkboxes4.checked = source.checked;

    }
</script>

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
    <h3 class="pb-4 mb-4 fst-italic border-bottom ">
        Štatistika testovania po krajoch:
    </h3>

    <form method="post" autocomplete="off">
        <div class="row">
            <div class="col-lg-4">
                <label> Zvoľte si dátumy: </label>
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


                &emsp; <input onclick="odznac()" type="checkbox" id="ag_vyk" name="ag_vyk" value="av" checked="checked">
                <label> Počet vykonaných Ag testov</label><br>
                &emsp; <input onclick="odznac()" type="checkbox" id="ag_poz" name="ag_poz" value="ap" checked="checked">
                <label> Počet pozitívnych Ag testov</label><br>
                &emsp; <input onclick="odznac()" type="checkbox" id="pcr_poz" name="pcr_poz" value="pp" checked="checked">
                <label> Počet pozitívnych PCR testov </label><br>
                &emsp; <input onclick="odznac()" type="checkbox" id="newcases" name="newcases" value="nc" checked="checked">
                <label> Počet nových prípadov</label><br>
                &emsp; <input onclick="odznac()" type="checkbox" id="poz_celk" name="poz_celk" value="pc" checked="checked">
                <label> Počet pozitívnych celkom</label><br>
                <input onclick="oznac_vsetky(this)" type="checkbox" id="v" name="v" checked="checked">
                <label> všetky </label><br>
            </div>

        </div>

        <input type="submit" name="Send1" value="Zobraz">
    </form>

    <p class="pb-4 mb-2 "></p>
    <table id="tu">

    </table>
    <p class='pb-4 mb-2 '></p>
    <?php if (isset($_POST['Send1'])) { ?>
        <div class="col-lg-11 text-center">
            <input id="prev" onclick="previous()" type="button" value="< späť"/>
            <input id="next" onclick="next()" type="button" value="ďalej >"/>
        </div>
    <?php } ?>

</main>
</body>


<?php
require "footer.php";
?>
