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
$kraje = $storage->getKraje();
$krajemes = $storage->mesacneKraje("Bratislavský kraj");

$ulozene = "";
if(isset($_POST['krajelist'])){
    $ulozene = $_POST['krajelist'];
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
        var t="";
        if(document.getElementById("ag_vyk").checked){  m = "Počet vykonaných AG testov";}
        if(document.getElementById("ag_poz").checked){  n = "Počet pozitívnych z AG testov";}
        if(document.getElementById("pcr_poz").checked){  o = "Počet pozitívnych z PCR testov";}
        if(document.getElementById("newcases").checked){  p = "Počet nových prípadov";}
        if(document.getElementById("poz_celk").checked){  r = "Počet celkovo pozitívnych prípadov";}
        if(document.getElementById("v").checked){  s = "všetko";}
        t=document.getElementById("krajelist").value;
        xhttp.open("GET", "stats/kraje_tab.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&p=" + p + "&r=" + r + "&s=" + s + "&ktore=" + ktore + "&t=" + t, true);
        xhttp.send();
        setLinkValueKraje()
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


<main class="container ">
    <!-- <form method="post"> -->
    <div class="row pb-4 mb-4">

            <div class="column col-lg-4">
                <div>
                    <label> Zvoľte si dátumy: </label>
                </div>

                    <div>
                        <?php
                            $od2 = $storage->getDate('min', 'kraje_stat');
                         ?>

                        <label> Od: </label>
                        <input type="date" name="date" id="date"
                               value="<?= $od2 ?>"
                               min="<?= $storage->getDate('min', 'kraje_stat') ?>"
                               max="<?= $storage->getDate('max', 'kraje_stat') ?>">
                        <br>
                        <label> Do: </label>
                        <?php
                            $do2 = $storage->getDate('max', 'kraje_stat');
                         ?>

                        <input type="date" name="date2" id="date2"
                               value="<?= $do2 ?>"
                               min="<?= $storage->getDate('min', 'kraje_stat') ?>"
                               max="<?= $storage->getDate('max', 'kraje_stat') ?>">
                        <br><br>
                    </div>

            </div>
            <div class="column col-lg-4">
                <div>
                    <?php if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { ?>
                        <label for="krajelist">Zvoľte kraj:</label>
                    <?php } else { ?>
                        <label for="krajelist">Zvoľte okres:</label>
                    <?php } ?>
                </div>

                    <div>
                        <select id="krajelist" name="krajelist">
                            <?php for ($i = 0; $i < sizeof($kraje); $i++) {
                                if($kraje[$i]->getKraj()==$ulozene){ ?>
                                    <option selected="selected"><?= $kraje[$i]->getKraj() ?> </option>
                                <?php } else { ?>
                                    <option ><?= $kraje[$i]->getKraj() ?> </option>
                                <?php    }
                            }?>
                            <option value="všetky">všetky</option>
                        </select>
                    </div>
            </div>

            <div class="column col-lg-4">
                <div>
                    <label> Začiarknite položky, ktoré sa majú zobraziť: </label>
                </div>

                    <div>
                        &emsp; <input onclick="odznac(this)" type="checkbox" id="ag_vyk" name="ag_vyk" value="av"
                                      checked="checked">
                        <label> Počet vykonaných Ag testov</label><br>
                        &emsp; <input onclick="odznac(this)" type="checkbox" id="ag_poz" name="ag_poz" value="ap"
                                      checked="checked">
                        <label> Počet pozitívnych Ag testov</label><br>
                        &emsp; <input onclick="odznac(this)" type="checkbox" id="pcr_poz" name="pcr_poz" value="pp"
                                      checked="checked">
                        <label> Počet pozitívnych PCR testov </label><br>
                        &emsp; <input onclick="odznac(this)" type="checkbox" id="newcases" name="newcases"
                                      value="nc"
                                      checked="checked">
                        <label> Počet nových prípadov</label><br>
                        &emsp; <input onclick="odznac(this)" type="checkbox" id="poz_celk" name="poz_celk"
                                      value="pc"
                                      checked="checked">
                        <label> Počet pozitívnych celkom</label><br>
                        <input onclick="oznac_vsetky(this,5,'ag_vyk','ag_poz','newcases','poz_celk','pcr_poz')"
                               type="checkbox" id="v" name="v" checked="checked">
                        <label> všetky </label><br>
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
                        ?>

                            <option value="<?= $kraje[$i]->getKraj() ?>" ><?= $kraje[$i]->getKraj() ?> </option>
                        <?php  } ?>

                </select>
            </div>
            <div class="row pb-4 mb-2">
                <div class="col-sm-1" >
                    <button  name="Send2" onclick="ukaz(1)">Filtruj </button>
                </div>
            </div>
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
<script>
    var m=1;
    var size1 = parseInt('<?= sizeof($krajemes) ?>');
    ukaz(m);
    function ukaz(m)
    { var xhttp2 = new XMLHttpRequest();

        xhttp2.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tu2").innerHTML = this.responseText;
            }
        };
            var c = m.toString();
            var a =document.getElementById("krajelist2");
            a = a.value;

        xhttp2.open("GET", "stats/kraje_tab.php?a=" + a + "&c=" + c + "&ktore=druha", true);
        xhttp2.send();
    }

    function next2() {
        if (m+50 < size1) {
            m += 50;
            ukaz(m);
        }
    }
    function previous2() {
        if (m-50 > 0) {
            m-= 50;
            ukaz(m);
        }
    }


</script>
</body>


<?php
require "parts/footer.php";
?>
