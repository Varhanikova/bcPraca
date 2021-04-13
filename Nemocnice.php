<?php
/**
 * štatistika nemocníc
 */
require "header.php";
$storage = new DB_storage();
$hosp = [];
$nemocnice = $storage->getAllHospitals();
$okresy = $storage->getOkresy();
$chcem = "";
$hosp = $storage->getAllHospital_stat();
?>
<script>
    $(function () {
        var availableTags = [
            <?php for($i = 0;$i < sizeof($okresy);$i++) { $str = $okresy[$i]->getOkres();?>
            "<?=$str?> ",
            <?php  } ?>
        ];
        $("#tags").autocomplete({
            source: availableTags
        });
    });
</script>
<body>
<main class="container">

    <h3 class="pb-4 mb-4 fst-italic border-bottom text-center ">
        Štatistika nemocníc:
    </h3>
    <h4 class="pb-4 mb-4 fst-italic  ">
        Počty pacientov v nemocniciach v danom okrese:
    </h4>
</main>
<?php require "body.php" ;

$nem = $storage->nemocky("2020-12-01", "2021-01-15");
?>
<main class="container ">
    <div class="row pb-4 mb-4">
        <div class="column col-lg-1"></div>
        <div class="column col-lg-3">
                <div>
                    <label> Zvoľte si dátumy: </label>
                </div>

                    <div>
                        <?php
                            $od3 = $storage->getDate('min', 'hospitals_stat');
                         ?>

                        <label> Od: </label>
                        <input type="date" name="date" id="date"
                               value="<?= $od3 ?>"
                               max="<?= $storage->getDate('max', 'hospitals_stat') ?>"
                               min="<?= $storage->getDate('min', 'hospitals_stat') ?>"><br>
                        <label> Do: </label>
                        <?php
                            $do3 = $storage->getDate('max', 'hospitals_stat');
                         ?>

                        <input type="date" name="date2" id="date2"
                               value="<?= $do3 ?>"
                               max="<?= $storage->getDate('max', 'hospitals_stat') ?>"
                               min="<?= $storage->getDate('min', 'hospitals_stat') ?>"><br>
                    </div>
            <p class='pb-4 mb-2 '></p>
            <div>
                <label for="krajelist">Zvoľte okres:</label>
            </div>
            <div>
                <div class="ui-widget">
                    <label for="tags"> </label>
                    <input id="tags" name="tags" value="Okres Bratislava I">
                </div>
            </div>
            </div>

        <div class="column col-lg-2"></div>
            <div class="column col-lg-4">
                <div>
                    <label> Začiarknite položky, ktoré sa majú zobraziť: </label>
                </div>
                    <div>
                        &emsp; <input onclick="odznac(this)" type="checkbox" id="obs" name="obs" value="obs"
                                      checked="checked">
                        <label> Počet obsadených lôžok</label><br>
                        &emsp; <input onclick="odznac(this)" type="checkbox" id="pluc" name="pluc" value="pluc"
                                      checked="checked">
                        <label> Počet osôb na pľúcnej ventilácii</label><br>
                        &emsp; <input onclick="odznac(this)" type="checkbox" id="hosp" name="hosp" value="hos"
                                      checked="checked">
                        <label> Celkový počet hospitalizovaných </label><br>
                        <input onclick="oznac_vsetky(this,3,'obs','pluc','hosp','','')" type="checkbox" id="v"
                               name="v"
                               value="v" checked="checked">
                        <label> všetky </label><br>
                    </div>
            </div>

        <?php if (isset($_SESSION["name"])) {
            if ($_SESSION["name"] == 'admin') {
                ?>

                <div class="admin_part column col-lg-2">

                    <div class="row col-lg-12 pb-2 mb-2">
                        <div class="col-lg-12">
                            <h4 class="fst-italic text-center" style="color: white">Zvoľ:</h4>
                        </div>
                    </div>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div class="row ">
                                <a class="btn"  style="font-size: medium" onclick="imp()">Import &raquo;</a>
                            </div></li>
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <a class="btn" style="font-size: medium" onclick="exp()">Export &raquo;</a>
                            </div></li>
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div class="row ">
                                <a onclick="setLinkValueNemocnice()" style="font-size: medium" oncontextmenu="setLinkValueNemocnice()" id="nemocnice" class="btn " href=""> PDF export &raquo;</a>
                            </div></li>
                    </ul>
                </div>

            <?php }
        }
        ?>

    </div>
    <div class="row pb-4 mb-4">
        <div class="col-sm-5">
            <button onclick="displayResults(1)" name="Send1">Filtruj</button>
        </div>
        <p class='pb-4 mb-2 '></p>
        <div class=" col-sm-6 p-4 mb-3 bg-light rounded " id="rozbal" style="display: none; color: black">
            <form class="form-horizontal" method="post" name="frmCSVImport"
                  id="frmCSVImport" enctype="multipart/form-data">
                <input type="file" name="myFile" id="myFile" accept=".csv">
                <button  type="submit" id="import" name="import" class="btn-submit">Import</button>
                <br/>
            </form>
            <h4> ! Formát dát: </h4>
            <a> id_datum ; rok ; mesiac ; den ; id_nemocnice ; obsadene_lozka ; pluc_ventilacia ;
                hospitalizovani</a>
            <a></a>
        </div>
    </div>

    <script>
        var j = 1;
        var size = parseInt('<?= sizeof($hosp) ?>');
        displayResults(j);
        function displayResults(j) {
            if(document.getElementById("date").value > document.getElementById("date2").value ) {
                window.alert("Nesprávne zadaný dátum!");
            } else if(document.getElementById("obs").checked===false && document.getElementById("pluc").checked===false && document.getElementById("hosp").checked===false){
                window.alert("Nezačiarkli ste žiadnu položku na zobrazenie!");
            } else if(document.getElementById("tags").value === ""){
                window.alert("Nie je zvolený žiaden okres!");
            } else {
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
                var t = "";
                var s="";
                if(document.getElementById("obs").checked){  m = "Počet osôb na pľúcnej ventilácii";}
                if(document.getElementById("pluc").checked){  n = "Počet obsadených lôžok";}
                if(document.getElementById("hosp").checked){  o = "Celkový počet hospitalizovaných";}
                if(document.getElementById("v").checked){  s = "všetko";}
                t = document.getElementById("tags").value;
                xhttp.open("GET", "stats/nemoc_tab.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&s=" + s + "&ktore=" + ktore + "&t=" + t, true);
                xhttp.send();
            }
        }
    </script>
</main>
<main class="container ">
    <p class='pb-4 mb-2 '></p>
    <table id="tu">
    </table>
    <p class='pb-4 mb-2 '></p>
    <div class="col-lg-12 text-center pb-4 mb-4 fst-italic border-bottom">
        <input id="prev" onclick="previous()" type="button" value="< späť"/>
        <input id="next" onclick="next()" type="button" value="ďalej >"/>
    </div>
</main>
<main class="container ">
    <div class="col-lg-12">
        <h4 class="pb-4 mb-4 fst-italic  ">
            Situácia v nemocniciach na celom Slovensku:
        </h4>
    </div>

    <div class="row">
        <div class="column col-lg-3">
        <?php if (isset($_POST['Send1'])) {
            $od3 = $_POST['date'];
        } else {
            $od3 = $storage->getDate('min', 'hospitals_stat');
        } ?>
        <div >
        <label> Od: </label>
        <input type="date" name="date3" id="date3"
               value="<?= $od3 ?>"
               max="<?= $storage->getDate('max', 'hospitals_stat') ?>"
               min="<?= $storage->getDate('min', 'hospitals_stat') ?>"></div>
        <div >
            <label> Do:  </label>
            <?php if (isset($_POST['Send1'])) {
                $do3 = $_POST['date2'];
            } else {
                $do3 = $storage->getDate('max', 'hospitals_stat');
            } ?>
            <input type="date" name="date4" id="date4"
                   value="<?= $do3 ?>"
                   max="<?= $storage->getDate('max', 'hospitals_stat') ?>"
                   min="<?= $storage->getDate('min', 'hospitals_stat') ?>">
        </div></div>

       <div class="column pb-4 mb-2">
            <div class="col-sm-1" >
                <button  name="Send" onclick="ukaz1('1')">Filtruj </button>
            </div>
        </div>
    </div>
    <script>
        var k = 1;
        const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        const firstDate = new Date( document.getElementById("date4").value);
        const secondDate =new Date( document.getElementById("date3").value);

        const diffDays = Math.round((firstDate.getTime() - secondDate.getTime())/oneDay);

        ukaz1(k);
        function ukaz1(k){
            var xhttp2 = new XMLHttpRequest();

            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("tu2").innerHTML = this.responseText;
                }
            };
            var c = k.toString();
            var a =document.getElementById("date3");
            a = a.value;
            var b =document.getElementById("date4");
            b = b.value;

            xhttp2.open("GET", "stats/nemoc_tab.php?a=" + a + "&b=" + b + "&c=" + c + "&ktore=druha", true);
            xhttp2.send();
        }

        function next1() {
            if (k < ((diffDays*4)-27)) {
                k += 40;
                ukaz1(k);
            }
        }

        function previous1() {
            if (k > 1) {
                k-= 40;
                ukaz1(k);
            }
        }
    </script>
        <p class='pb-4 mb-2 '></p>
    <table id="tu2">

    </table>

    <p class='pb-4 mb-2 '></p>
    <div class="col-lg-12 text-center pb-4 mb-4 fst-italic ">

        <input id="prev1" onclick="previous1()" type="button" value="< späť"/>
        <input id="next1" onclick="next1()" type="button" value="ďalej >"/>

    </div>
</main>

</body>



<?php
require "parts/footer.php";
?>
