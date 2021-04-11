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

        var ktore = "prva";
        var a =document.getElementById("date");
        a = a.value;
        var b =document.getElementById("date2");
        b = b.value;
        var m = "";
        var n = "";
        var o = "";
        var p = "";
        var r = "";
        var s="";
        if(document.getElementById("pcr_pot").checked){  m = "Počet PCR potvrdených prípadov";}
        if(document.getElementById("pcr_poc").checked){  n = "Počet vykonaných PCR testov";}
        if(document.getElementById("pcr_poz").checked){  o = "Počet pozitívnych z PCR testov";}
        if(document.getElementById("ag_poc").checked){  p = "Počet vykonaných AG testov";}
        if(document.getElementById("ag_poz").checked){  r = "Počet pozitívnych z AG testov";}
        if(document.getElementById("v").checked){  s = "všetko";}
        xhttp.open("GET", "stats/denne_tab.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&p=" + p + "&r=" + r + "&s=" + s + "&ktore=" + ktore, true);
        xhttp.send();
        setLinkValueDenne();
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

<main class="container ">
    <!-- <form method="post"> -->
    <div class="row pb-4 mb-4">
                   <div class="column col-lg-6">
                <div>
                    <label> Zvoľte si dátumy: </label> <br>
                </div>

                    <div>
                        <div>
                            <?php
                                $od = $storage->getDate('min', 'kazdodenne_stat');
                             ?>
                            &emsp;<label for="date"> Od: </label>
                            <input type="date" name="date" id="date"
                                   value="<?= $od ?>"
                                   max="<?= $storage->getDate('max', 'kazdodenne_stat') ?>"
                                   min="<?= $storage->getDate('min', 'kazdodenne_stat') ?>">
                        </div>
                        <div>
                            <?php
                                $do = $storage->getDate('max', 'kazdodenne_stat');
                             ?>

                            &emsp;<label for="date2"> Do: </label>
                            <input type="date" name="date2" id="date2"
                                   value="<?= $do ?>"
                                   max="<?= $storage->getDate('max', 'kazdodenne_stat') ?>"
                                   min="<?= $storage->getDate('min', 'kazdodenne_stat') ?>"<br>
                        </div>
                    </div>



            </div>
            <div class="column col-lg-6">
                <div>
                    <label for="umrtia_na_kov"> Začiarknite položky, ktoré sa majú zobraziť: </label>
                </div>

                <div>
                    &emsp; <input onclick="odznac(this)" type="checkbox" id="pcr_pot" name="pcr_pot"
                                  value="Počet PCR potvrdených prípadov" checked="checked">
                    <label for="pcr_pot"> Počet PCR potvrdených prípadov</label><br>
                    &emsp; <input onclick="odznac(this)" type="checkbox" id="pcr_poc" name="pcr_poc"
                                  value="Počet vykonaných PCR testov" checked="checked">
                    <label for="pcr_poc"> Počet vykonaných PCR testov</label><br>
                    &emsp; <input onclick="odznac(this)" type="checkbox" id="pcr_poz" name="pcr_poz"
                                  value="Počet pozitívnych z PCR testov" checked="checked">
                    <label for="pcr_poz"> Počet pozitívnych z PCR testov</label><br>
                    &emsp; <input onclick="odznac(this)" type="checkbox" id="ag_poc" name="ag_poc"
                                  value="Počet vykonaných AG testov" checked="checked">
                    <label for="ag_poc"> Počet vykonaných AG testov</label><br>
                    &emsp; <input onclick="odznac(this)" type="checkbox" id="ag_poz" name="ag_poz"
                                  value="Počet pozitívnych z AG testov" checked="checked">
                    <label for="ag_poz"> Počet pozitívnych z AG testov</label><br>
                    <input onclick="oznac_vsetky(this,5,'pcr_pot','pcr_poc','pcr_poz','ag_poc','ag_poz')"
                           type="checkbox"
                           id="v" name="v" value="všetky" checked="checked">

                    <label for="v"> všetky </label><br>
                </div>
            </div>

    </div>

    </div>
    <div class="row pb-4 mb-4">
        <div class="col-sm-1">
            <button onclick="displayResults(1)" name="Send1">Filtruj</button>
        </div>
    </div>
    <!-- </form> -->
<?php require "body.php";


?>
</main>
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
    <script>  var m=1;
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

            xhttp2.open("GET", "stats/denne_tab.php?c=" + c + "&ktore=druha", true);
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
        } </script>
</main>
</body>


<?php
require "parts/footer.php";
?>
