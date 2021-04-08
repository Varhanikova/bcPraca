<?php
require "header.php";
$storage = new DB_storage();
$hosp = [];
$nemocnice = $storage->getAllHospitals();
$okresy = $storage->getOkresy();
$chcem = "";

if (isset($_POST['Send1'])) {
    $chyba1 = 0;

    if (!isset($_POST['obs']) && !isset($_POST['pluc']) && !isset($_POST['hosp']) && !isset($_POST['v'])) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nezačiarkli ste žiadnu položku na zobrazenie!");
        </script>
        <?php
    }
    if (!empty($_POST['tags'])) {
        $chcem = $_POST['tags'] ;
    }
    if ($_POST['date'] > $_POST['date2']) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nesprávne zadaný dátum!");
        </script>
    <?php }

    if ($chyba1 == 0) {
            $hosp = $storage->getAllHospital_stat1($_POST['date'], $_POST['date2'], $chcem);
    }
} else {
    $hosp = $storage->getAllHospital_stat();
}
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
<script>

    var j = 1;
    var size = parseInt('<?= sizeof($hosp) ?>');
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
        var m = "<?= isset($_POST['obs']) ?>";

        if (m !== "") {
            m = "Počet obsadených lôžok";
        }

        var n = "<?= isset($_POST['pluc']) ?>";

        if (n !== "") {
            n = "Počet osôb na pľúcnej ventilácii";
        }

        var o = "<?= isset($_POST['hosp']) ?>";

        if (o !== "") {
            o = "Celkový počet hospitalizovaných";
        }

        var s = "<?= isset($_POST['v']) ?>";

        if (s !== "") {
            s = "všetko";
        }
        <?php if(isset($_POST['Send1'])) { ?>
        var a = "<?=$_POST['date'] ?>";
        var b = '<?=$_POST['date2'] ?>';
        var t = "<?= ($_POST['tags']) ?>";
        <?php } ?>
        var ktore = "nemocnice";
        } else {
            n = "Počet osôb na pľúcnej ventilácii";
            m = "Počet obsadených lôžok";
            o = "Celkový počet hospitalizovaných";
            s = "všetko";
            a="";
            b="";
            t="Okres Bratislava I";
            var ktore = "nemocnice1";
        }
        xhttp.open("GET", "stats/tabulky.php?c=" + c + " &a=" + a + "&b=" + b + "&m=" + m + "&n=" + n + "&o=" + o + "&s=" + s + "&ktore=" + ktore + "&t=" + t, true);
        xhttp.send();
    }
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

            xhttp2.open("GET", "stats/nemoc_tab.php?a=" + a + "&b=" + b + "&c=" + c, true);
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
