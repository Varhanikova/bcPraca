<?php
require "header.php";
require_once "DB_storage.php";
$storage = new DB_storage();
$testy = [];
$perc = $storage->mesacnepozitivne();
if (isset($_POST['Send1'])) {
    $chyba1 = 0;

    if (!isset($_POST['pcr_pot']) && !isset($_POST['pcr_poc']) && !isset($_POST['pcr_poz']) && !isset($_POST['ag_poz']) && !isset($_POST['ag_poc']) && !isset($_POST['v'])) {
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
        </script>
    <?php }

    if ($chyba1 == 0) {
        $testy = $storage->getAllKazdodenneStat($_POST['date'], $_POST['date2']);
    }
} else {
    $testy = $storage->getAllDenne();
}
?>

<script>
    var m=1;
    var size1 = parseInt('<?= sizeof($perc) ?>');
    ukaz1(m);
    function ukaz1(m){
        var xhttp2 = new XMLHttpRequest();

        xhttp2.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tu2").innerHTML = this.responseText;
            }
        };
        var c = m.toString();

        xhttp2.open("GET", "stats/denne_tab.php?c=" + c, true);
        xhttp2.send();
    }
    function next2() {
        if (m+40 < size1) {
            m += 40;
            ukaz1(m);
        }
    }
    function previous2() {
        if (m-40 > 0) {
            m-= 40;
            ukaz1(m);
        }
    }
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
        var je = "<?= isset($_POST['Send1'])?>";
        if (je !== "") {
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
        } else {
            a = "";
            b = "";
            m = "Počet PCR potvrdených prípadov";
            n = "Počet vykonaných PCR testov";
            o = "Počet pozitívnych z PCR testov";
            p = "Počet vykonaných AG testov";
            r = "Počet pozitívnych z AG testov";
            s = "všetko";
            var ktore = "denne1";
        }

        xhttp.open("GET", "stats/tabulky.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&p=" + p + "&r=" + r + "&s=" + s + "&ktore=" + ktore, true);
        xhttp.send();
    }
</script>
<body>
<main class="container">
    <h3 class="pb-4 mb-4 fst-italic border-bottom text-center ">
        Štatistika každodenného testovania:
    </h3>
    <h4 class="pb-4 mb-4 fst-italic ">
        Počty z testovania po dňoch:
    </h4>
</main>
<?php require "body.php";


?>

<main class="container ">
    <div class="col-lg-12">
        <h4 class="pb-4 mb-4 fst-italic  ">
            Percentá pozitívnych z testovaných po mesiacoch:
        </h4>
    </div>
    <p class='pb-4 mb-2 '></p>


    <table id="tu2">



    </table>
    <p class='pb-4 mb-2 '></p>
    <div class="col-lg-12 text-center pb-4 mb-4 fst-italic ">

        <input id="prev1" onclick="previous2()" type="button" value="< späť"/>
        <input id="next1" onclick="next2()" type="button" value="ďalej >"/>

    </div>
</main>
</body>


<?php
require "parts/footer.php";
?>
