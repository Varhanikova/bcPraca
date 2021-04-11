<?php
require "header.php";
$storage = new DB_storage();
$umrtia = [];
$perc = $storage->mesacneUmrtiaNaKov();
 $umrtia = $storage->getDeathsAll();?>

        <script>
            if(!document.getElementById("umrtia_na_kov").checked && !document.getElementById("umrtia_s_kov").checked && !document.getElementById("celk").checked)
            window.alert("Nezačiarkli ste žiadnu položku na zobrazenie!");
        </script>

        <script>
            if(document.getElementById("date").value > document.getElementById("date2").value) {
                window.alert("Nesprávne zadaný dátum!");
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
<main class="container ">

    <div class="row pb-4 mb-4">

        <div class="column col-lg-6">
            <div>
                <label> Zvoľte si dátumy: </label> <br>
            </div>
              <div>
                    <div>
                        <?php
                            $od1 = $storage->getDate('min', 'deaths_stat');
                         ?>

                        &emsp;<label for="date"> Od: </label>
                        <input type="date" name="date" id="date"
                               value="<?= $od1 ?>"
                               max="<?= $storage->getDate('max', 'deaths_stat') ?>"
                               min="<?= $storage->getDate('min', 'deaths_stat') ?>">
                    </div>
                    <div>
                        <?php
                            $do1 = $storage->getDate('max', 'deaths_stat');
                         ?>

                        &emsp;<label for="date2"> Do: </label>
                        <input type="date" name="date2" id="date2"
                               value="<?= $do1 ?>"
                               max="<?= $storage->getDate('max', 'deaths_stat') ?>"
                               min="<?= $storage->getDate('min', 'deaths_stat') ?>"><br>
                    </div>
                </div>
        </div>
        <div class="column col-lg-6">
            <div>
                <label for="umrtia_na_kov"> Začiarknite položky, ktoré sa majú zobraziť: </label>
            </div>
                <div>
                    &emsp; <input onclick="odznac(this)" type="checkbox" id="umrtia_na_kov" name="umrtia_na_kov"
                                  value="počet úmrtí na kovid" checked="checked">
                    <label> počet úmrtí na kovid</label><br>
                    &emsp; <input onclick="odznac(this)" type="checkbox" id="umrtia_s_kov" name="umrtia_s_kov"
                                  value="počet úmrtí s kovid" checked="checked">
                    <label> počet úmrtí s kovid</label><br>
                    &emsp; <input onclick="odznac(this)" type="checkbox" id="celk" name="celk"
                                  value="celkový počet úmrtí " checked="checked">
                    <label> celkový počet úmrtí </label><br>
                    <input onclick="oznac_vsetky(this,3,'umrtia_na_kov','umrtia_s_kov','celk','','')"
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

<?php require "body.php";

?>
    <script>
        setLinkValueUmrtia();

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

            var ktore = "prva";
            var a =document.getElementById("date");
            a = a.value;
            var b =document.getElementById("date2");
            b = b.value;
            var m = "";
            var n = "";
            var o = "";
            var s="";
            if(document.getElementById("umrtia_na_kov").checked){  m = "počet úmrtí na kovid";}
            if(document.getElementById("umrtia_s_kov").checked){  n = "počet úmrtí s kovid";}
            if(document.getElementById("celk").checked){ o = "celkový počet úmrtí ";}
            if(document.getElementById("v").checked){ s = "všetko ";}

            xhttp.open("GET", "stats/umrtia_tab.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&s=" + s + "&ktore=" + ktore, true);
            xhttp.send();
            setLinkValueUmrtia();
        }

    </script>
</main>
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
    <script> var m=1;
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

            xhttp2.open("GET", "stats/umrtia_tab.php?c=" + c + "&ktore=druha", true);
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
    </script>
</main>
</body>
<?php
require "parts/footer.php";
?>
