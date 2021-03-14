<?php
require "header.php";
$storage = new DB_storage();
$umrtia = '';

if (isset($_POST['Send1'])) {
    $chyba1 = 0;

    if (!isset($_POST['umrtia_na_kov']) && !isset($_POST['umrtia_s_kov']) && !isset($_POST['celk'])) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nezačiarkli ste žiadnu položku na zobrazenie!");
        </script>
        <?php
    }

    if (!empty($_POST['date']) && $storage->isThere($_POST['date'],"deaths_stat") == '') {
        $chyba1 = 1;
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if (empty($_POST['date'])) {
        $_POST['date'] = "2020-09-24";
    }
    if (!empty($_POST['date2']) && $storage->isThere($_POST['date2'],"deaths_stat") == '') {
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if ($_POST['date2'] == "") {
        $_POST['date2'] = "2021-02-14";
    }

    if ($chyba1 == 0) {
        if (isset($_POST['date2'])) {
            $umrtia = $storage->getDeathsAtDate($_POST['date'], $_POST['date2']);
        }
    }
}

?>
<script>
    function odznac() {
        var a = "<?= isset($_POST['umrtia_na_kov']) ?>";
        var b = "<?= isset($_POST['umrtia_s_kov']) ?>";
        var c = "<?= isset($_POST['celk']) ?>";
        checkboxes = document.getElementById('v');

        if (a === "" || b === "" || c === "") {
            checkboxes.checked = false;
        }
    }
    function oznac_vsetky(source) {

            checkboxes = document.getElementById('umrtia_na_kov');
            checkboxes.checked = source.checked;
            checkboxes1 = document.getElementById('umrtia_s_kov');
            checkboxes1.checked = source.checked;
            checkboxes2 = document.getElementById('celk');
            checkboxes2.checked = source.checked;


    }
</script>
<body>
<main class="container">

    <h3 class="pb-4 mb-4 fst-italic border-bottom ">
        Štatistika úmrtí:
    </h3>
    <form method="post">
        <div class="row">
            <div class="col-lg-6">
                <label> Zvoľte si dátumy: </label> <br>
            </div>
            <div class="col-lg-6">
                <label for="umrtia_na_kov"> Začiarknite položky, ktoré sa majú zobraziť: </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                &emsp;<label for="date"> Od: </label>
                <input type="date" name="date" id="date" value="2020-09-24" max="2021-02-14" min="2020-09-24">
                <label for="date2"> Do: </label>
                <input type="date" name="date2" id="date2" value="2021-02-14" max="2021-02-14" min="2020-09-24"><br>
            </div>
            <div class="col-lg-6">
                &emsp; <input onclick="odznac()" type="checkbox" id="umrtia_na_kov" name="umrtia_na_kov" value="počet úmrtí na kovid" checked="checked">
                <label> počet úmrtí na kovid</label><br>
                &emsp; <input  onclick="odznac()" type="checkbox" id="umrtia_s_kov" name="umrtia_s_kov" value="počet úmrtí s kovid" checked="checked">
                <label> počet úmrtí s kovid</label><br>
                &emsp; <input onclick="odznac()" type="checkbox" id="celk" name="celk" value="celkový počet úmrtí " checked="checked">
                <label> celkový počet úmrtí </label><br>
                <input onclick="oznac_vsetky(this)" type="checkbox" id="v" name="v" value="všetky" checked="checked">
                <label> všetky </label><br>
            </div>
        </div>
        <input type="submit" name="Send1" value="Zobraz">
    </form>
    <p class='pb-4 mb-2 '></p>
    <table id="tu">

    </table>
    <p class='pb-4 mb-2 '></p>
    <?php if(isset($_POST['Send1'])) { ?>

    <div class="col-lg-11 text-center">
    <input  id="prev" onclick="previous()" type="button" value="< späť" />
    <input id="next" onclick="next()" type="button" value="ďalej >" />
    </div>
    <?php } ?>
    <script>
        var j = 1;
        var size = parseInt('<?= sizeof($umrtia) ?>');
        displayResults(j);
        function displayResults(j) {
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("tu").innerHTML = this.responseText;
                }        };
            var c = j.toString();
            var m = "<?= isset($_POST['umrtia_na_kov']) ?>";

            if(  m!=="") {
                m = "počet úmrtí na kovid" ;
            }

            var n ="<?= isset($_POST['umrtia_s_kov']) ?>";

            if( n!=="") {
                n = "počet úmrtí s kovid" ;
            }

            var o ="<?= isset($_POST['celk']) ?>";

            if(o!=="") {
                o = "celkový počet úmrtí " ;
            }

            var s ="<?= isset($_POST['v']) ?>";

            if(  s!=="") {
                s = "všetko" ;
            }

            var a = "<?=$_POST['date'] ?>";
            var b = '<?=$_POST['date2'] ?>';

            var ktore="umrtia";

            xhttp.open("GET", "stats/tabulky.php?c="+ c + " &a=" +a  +"&b=" + b + "&m=" + m + "&n=" +n + "&o=" + o + "&s=" + s + "&ktore="+ktore, true);
            xhttp.send();
        }
        function next() {
            if (j < size-14 ){
                j+=15;
                displayResults(j);
            }
        }
        function previous() {
            if (j > 1) {
                j-=15;
                displayResults(j);
            }}
    </script>
</main>
</body>
<?php
require "footer.php";
?>
