<?php
require "header.php";
$storage = new DB_storage();
$umrtia = [];
$perc = $storage->mesacneUmrtiaNaKov();
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

        xhttp2.open("GET", "stats/umrtia_tab.php?c=" + c, true);
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


?>
<main class="container ">
    <div class="col-lg-12">
        <h4 class="pb-4 mb-4 fst-italic  ">
            Percentá úmrtí po mesiacoch:
        </h4>
    </div>
    <p class='pb-4 mb-2 '></p>
    <table id="tu2">

    </table>
    <p class='pb-4 mb-2 '></p>
    <div class="row ">
        <div class="col-lg-5"></div>
    <div class="col-lg-2 text-center pb-4 mb-4  fst-italic ">

        <input id="prev1" onclick="previous2()" type="button" value="< späť"/>
        <input id="next1" onclick="next2()" type="button" value="ďalej >"/>

    </div>
    </div>
</main>
</body>
<?php
require "parts/footer.php";
?>
