<?php
require "header.php";
$storage = new DB_storage();
$array = [];
$chcem = "";

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
?>
<script>
    var j = 1;
    var size = parseInt("<?= sizeof($array) ?>");
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
            var a="";
            var b="";
            <?php if(isset($_POST['Send1'])) { ?>
                a = "<?=$_POST['date'] ?>";
                b = '<?=$_POST['date2'] ?>';
            var t = "<?= ($_POST['krajelist']) ?>";
            <?php } ?>

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
$kraje = $storage->getKraje();

?>
<main class="container cislo2">
    <h4 class="pb-4 mb-4 fst-italic  ">
        Mesačný priemer kraja:
    </h4>
        <div class="row">
            <div class="column col-lg-2">
                <label for="krajelist">Zvoľte kraj: </label>
            </div>
        </div>
        <div class="row">
            <div class="column col-lg-2">
                <select id="krajelist2" name="krajelist2" >
                    <?php for ($i = 0; $i < sizeof($kraje); $i++) {
                        //if($kraje[$i]->getKraj()==$uloz) {?>
                        <?php// } else { ?>
                            <option value="<?= $kraje[$i]->getKraj() ?>" ><?= $kraje[$i]->getKraj() ?> </option>
                        <?php  } //}?>

                </select>
            </div>
            <div class="row pb-4 mb-2">
                <div class="col-sm-1" >
                    <button  name="Send2" onclick="ukaz()">Filtruj </button>
                </div>
            </div>
        </div>

    <p class='pb-4 mb-2 '></p>
    <table id="tu2">

    </table>
    <p class='pb-4 mb-2 '></p>
</main>
<script>
    ukaz();
    function ukaz()
    { var xhttp2 = new XMLHttpRequest();

        xhttp2.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tu2").innerHTML = this.responseText;
            }
        };

            var a =document.getElementById("krajelist2");
            a = a.value;

        xhttp2.open("GET", "stats/kraje_tab.php?a=" + a, true);
        xhttp2.send();
    }



</script>
</body>


<?php
require "parts/footer.php";
?>
