<?php
require "header.php";
require_once "DB_storage.php";
$storage = new DB_storage();
$testy = "";
if (isset($_POST['Send1'])) {
    $chyba1 = 0;

    if (!isset($_POST['pcr_pot']) && !isset($_POST['pcr_poc']) && !isset($_POST['pcr_poz']) && !isset($_POST['ag_poz']) && !isset($_POST['ag_poc']) && !isset($_POST['v'])) {
        $chyba1 = 1; ?>
        <script>
            window.alert("Nezačiarkli ste žiadnu položku na zobrazenie!");
        </script>
        <?php
    }
    if (!empty($_POST['date']) && $storage->isThere($_POST['date'],"kazdodenne_stat") == '') {
        $chyba1 = 1;
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if (empty($_POST['date'])) {
        $_POST['date'] = "2020-03-06";
    }
    if (!empty($_POST['date2']) && $storage->isThere($_POST['date2'],"kazdodenne_stat") == '') {
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if ($_POST['date2'] == "") {
        $_POST['date2'] = "2021-02-18";
    }

    if ($chyba1 == 0) {
        if (isset($_POST['date2'])) {
            $testy = $storage->getAllKazdodenneStat($_POST['date'], $_POST['date2']);
        }
    }
}
?>
<script>
    function odznac() {
        var a = "<?= isset($_POST['pcr_pot']) ?>";
        var b = "<?= isset($_POST['pcr_poc']) ?>";
        var c = "<?= isset($_POST['pcr_poz']) ?>";
        var d = "<?= isset($_POST['ag_poc']) ?>";
        var e = "<?= isset($_POST['ag_poz']) ?>";
        checkboxes = document.getElementById('v');

        if (a === "" || b === "" || c === "" || d === "" || e === "") {
            checkboxes.checked = false;
        }
    }

    function oznac_vsetky(source) {

             let checkboxes = document.getElementById('pcr_pot');
            checkboxes.checked = source.checked;
           let checkboxes1 = document.getElementById('pcr_poc');
            checkboxes1.checked = source.checked;
           let checkboxes2 = document.getElementById('pcr_poz');
            checkboxes2.checked = source.checked;
            let  checkboxes3 = document.getElementById('ag_poc');
            checkboxes3.checked = source.checked;
            let  checkboxes4 = document.getElementById('ag_poz');
            checkboxes4.checked = source.checked;


    }

</script>
<script>
    var j = 1;
    var size = parseInt('<?= sizeof($testy) ?>');
    displayResults(j);
    function displayResults(j) {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("tu").innerHTML = this.responseText;
            }        };
        var c = j.toString();
        var a ="<?=$_POST['date'] ?>";
        var b = '<?=$_POST['date2'] ?>';

        var m = "<?= isset($_POST['pcr_pot']) ?>";

        if(  m!=="") {
            m = "Počet PCR potvrdených prípadov" ;
        }

        var n ="<?= isset($_POST['pcr_poc']) ?>";

        if( n!=="") {
            n = "Počet vykonaných PCR testov" ;
        }

        var o ="<?= isset($_POST['pcr_poz']) ?>";

        if(o!=="") {
            o = "Počet pozitívnych z PCR testov" ;
        }

        var p ="<?= isset($_POST['ag_poc']) ?>";

        if(  p!=="") {
            p = "Počet vykonaných AG testov" ;
        }
        var r ="<?= isset($_POST['ag_poz']) ?>";

        if(  r!=="") {
            r = "Počet pozitívnych z AG testov" ;
        }
        var s ="<?= isset($_POST['v']) ?>";

        if(  s!=="") {
            s = "všetko" ;
        }
        var ktore="kazdodenne";


        xhttp.open("GET", "stats/tabulky.php?c="+ c + " &a=" +a  +"&b=" + b + "&m=" + m + "&n=" +n + "&o=" + o + "&p=" + p + "&r="+r+"&s="+s+ "&ktore="+ktore, true);
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


<body>
<main class="container">

    <h3 class="pb-4 mb-4 fst-italic border-bottom ">
        Štatistika každodenného testovania:
    </h3>
    <form method="post">
        <div class="row">
            <div class="col-lg-6">
                <label> Zvoľte si dátumy: </label> <br>
            </div>
            <div class="col-lg-6">
                <label > Začiarknite položky, ktoré sa majú zobraziť: </label>
            </div>
        </div>
        <div class="row ">
            <div class="col-lg-6">
                &emsp;<label for="date"> Od: </label>
                <input type="date" name="date" id="date" max="2021-02-18" min="2020-03-06" value="2020-03-06">
                <label for="date2"> Do: </label>
                <input type="date" name="date2" id="date2" value="2021-02-18" max="2021-02-18" min="2020-03-06"><br>
            </div>
            <div class="col-lg-6">
                &emsp; <input onclick="odznac()" type="checkbox" id="pcr_pot" name="pcr_pot" value="Počet PCR potvrdených prípadov" checked="checked">
                <label for="pcr_pot"> Počet PCR potvrdených prípadov</label><br>
                &emsp; <input onclick="odznac()" type="checkbox" id="pcr_poc" name="pcr_poc" value="Počet vykonaných PCR testov" checked="checked">
                <label for="pcr_poc"> Počet vykonaných PCR testov</label><br>
                &emsp; <input onclick="odznac()" type="checkbox" id="pcr_poz" name="pcr_poz" value="Počet pozitívnych z PCR testov" checked="checked">
                <label for="pcr_poz"> Počet pozitívnych z PCR testov</label><br>
                &emsp; <input onclick="odznac()" type="checkbox" id="ag_poc" name="ag_poc" value="Počet vykonaných AG testov" checked="checked">
                <label for="ag_poc"> Počet vykonaných AG testov</label><br>
                &emsp; <input onclick="odznac()" type="checkbox" id="ag_poz" name="ag_poz" value="Počet pozitívnych z AG testov" checked="checked">
                <label for="ag_poz"> Počet pozitívnych z AG testov</label><br>
                <input onclick="oznac_vsetky(this)" type="checkbox" id="v" name="v" value="všetky" checked="checked">
                <label for="v"> všetky </label><br>
            </div>
        </div>
        <input type="submit" name="Send1" value="Zobraz">
    </form>

    <p class="pb-4 mb-2 "></p>
    <table id="tu">

    </table>
    <p class='pb-4 mb-2 '></p>
    <?php if(isset($_POST['Send1'])) { ?>
    <div class="col-lg-11 text-center">
        <input  id="prev" onclick="previous()" type="button" value="< späť" />
        <input id="next" onclick="next()" type="button" value="ďalej >" />
    </div>
    <?php } ?>
</main>
</body>
<?php
require "footer.php";
?>
