<?php
$storage = new DB_storage();
$kraje = $storage->getKraje();

if (isset($_POST['import'])) {
    if ($_FILES["myFile"]["error"] > 0) {
        echo "Return Code: " . $_FILES["myFile"]["error"] . "<br />";

    }
    $fileName = $_FILES['myFile']['tmp_name'];
    if ($_FILES['myFile']['size'] > 0) {
        $file = fopen($fileName, "r");
        $vyslo = 0;
        $pocet = 0;
             if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) {
                 $pocet = 10; //$idkraj,$id_dat,$rok,$mes,$den,$agvyk,$agpoz,$pcrpoz,$new,$celk
             } else if (strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) {
                 $pocet = 8;//$id_dat,$rok,$mes,$den,$idnem,$obs,$pluc,$hosp
             } else if (strpos($_SERVER['REQUEST_URI'], "Denne") !== false) {
                 $pocet = 9;//$id_dat,$rok,$mes,$den,$pcrpot,$pcrpoc,$pcrpoz,$agpoc,$agpoz
             } else if (strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false) {
                 $pocet = 7;//$id_dat,$rok,$mesiac,$den,$pockov,$pocskov,$celk
             }

        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {

            if(count($column)==$pocet) {

            $par = [];

            for ($i = 0; $i < $pocet; $i++) {
                if (isset($column[$i])) {
                    $par[$i] = $column[$i];
                }
            }
            if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { //************* && sizeof($par)==$pocet
                //$idkraj,$id_dat,$rok,$mes,$den,$agvyk,$agpoz,$pcrpoz,$new,$celk
                $vyslo += $storage->importKraje($par[0], $par[1], $par[2], $par[3], $par[4], $par[5], $par[6], $par[7], $par[8], $par[9]);
            } else if (strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) {
                //$id_dat,$rok,$mes,$den,$idnem,$obs,$pluc,$hosp
                $vyslo += $storage->importHosp($par[0], $par[1], $par[2], $par[3], $par[4], $par[5], $par[6], $par[7]);
            } else if (strpos($_SERVER['REQUEST_URI'], "Denne") !== false) {
                //$id_dat,$rok,$mes,$den,$pcrpot,$pcrpoc,$pcrpoz,$agpoc,$agpoz
                $vyslo += $storage->importDenne($par[0], $par[1], $par[2], $par[3], $par[4], $par[5], $par[6], $par[7], $par[8]);
            } else if (strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false) {
                //$id_dat,$rok,$mesiac,$den,$pockov,$pocskov,$celk
                $vyslo += $storage->importDeaths($par[0], $par[1], $par[2], $par[3], $par[4], $par[5], $par[6]);
            }
        } else {
                $vyslo=-1;
            }
        }
        if ($vyslo > 0) {
            ?>
            <script>
                window.alert("Nepodarilo sa vložiť!");
            </script>
        <?php } else if($vyslo==0){ ?>
            <script>
                window.alert("Vložili sa údaje");
            </script>
            <?php

        } else { ?>
            <script> window.alert("Súbor neobsahuje správny počet záznamov! " );</script>
      <?php  }
    }
}

?>

<script>
displayResults(1);
    function imp() {
        if (document.getElementById("rozbal").style.display == "none") {
            document.getElementById("rozbal").style.display = "block";
        } else {
            document.getElementById("rozbal").style.display = "none";
        }
    }

    function exp() {
        var r = confirm("Naozaj chceš exportovať túto štatistiku?"); //******skúsiť dať názov štat
        if (r === true) {
            <?php if (strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false) {
            $storage->exportDeaths(); ?>
            alert("Štatistika Úmrtí bola exportovaná!");
            <?php
            } else if(strpos($_SERVER['REQUEST_URI'], "Denne") !== false) {
            $storage->exportDenne();
            ?>
            alert("Štatistika Denných testov bola exportovaná!");
            <?php
            } else if(strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) {
            $storage->exportKraje();
            ?>
            alert("Štatistika Krajov bola exportovaná!");
            <?php
            } else if(strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) {
            $storage->exportHosp();
            ?>
            alert("Štatistika Nemocníc bola exportovaná!");
            <?php
            }
            ?>
        }
    }
</script>
    <?php if (isset($_SESSION["name"])) {
    if ($_SESSION["name"] == 'admin') {
    ?>
    <div class="row">
        <div class="col-lg-6">
        </div>
        <div class="admin_part col-lg-6">
            <div class="p-4 mb-3 bg-light rounded">
                <div class="row col-lg-12 pb-2 mb-2">
                    <div class="col-lg-12">
                        <h4 class="fst-italic text-center">Zvoľ:</h4>
                    </div>
                </div>
                <div class="row col-lg-12">
                    <div class="col-lg-4">
                        <p><a class="btn btn-secondary " onclick="imp()"> Import &raquo;</a></p>
                    </div>

                    <div class="col-lg-4">
                        <p><a class="btn btn-secondary " onclick="exp()"> Export &raquo;</a></p>
                    </div>
                    <div class="col-lg-4">

                        <?php  if (strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false) { ?>
                            <p><a onclick="setLinkValueUmrtia()" oncontextmenu="setLinkValueUmrtia()" id="umrtia" class="btn btn-secondary " href=""> PDF export &raquo;</a></p>
                        <?php } else if (strpos($_SERVER['REQUEST_URI'], "DenneTestovanie") !== false) { ?>
                            <p><a onclick="setLinkValueDenne()" oncontextmenu="setLinkValueDenne()" id="denne" class="btn btn-secondary " href=""> PDF export &raquo;</a></p>
                        <?php } else if (strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>
                            <p><a oncontextmenu="setLinkValueNemocnice()" onclick="setLinkValueNemocnice()" id="nemocnice" class="btn btn-secondary " href=""> PDF export &raquo;</a></p>
                        <?php } else if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { ?>
                            <p><a id="kraje" onclick="setLinkValueKraje()" oncontextmenu="setLinkValueKraje()" class="btn btn-secondary " href=""> PDF export &raquo;</a></p>
                        <?php } ?>

                    </div>
                </div>
                <div class="row col-lg-12 " id="rozbal" style="display: none">
                    <form class="form-horizontal" method="post" name="frmCSVImport"
                          id="frmCSVImport" enctype="multipart/form-data">
                        <input type="file" name="myFile" id="myFile" accept=".csv">
                        <button  type="submit" id="import" name="import" class="btn-submit">Import</button>
                        <br/>
                    </form>
                    <h4> ! Formát dát: </h4>
                    <?php if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { ?>
                        <a> id_kraj ; id_datum ; rok ; mesiac ; den ; ag_vykonane ; ag_poz ; pcr_poz ; newcases ;
                            poc_celkovo</a>
                    <?php } else if (strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>
                        <a> id_datum ; rok ; mesiac ; den ; id_nemocnice ; obsadene_lozka ; pluc_ventilacia ;
                            hospitalizovani</a>
                        <?php
                    } else if (strpos($_SERVER['REQUEST_URI'], "Denne") !== false) { ?>
                        <a> id_datum ; rok ; mesiac ; den ; pcr_potvrdene ; pcr_poc ; pcr_poz ; ag_poc ; ag_poz</a>
                        <?php

                    } else if (strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false) { ?>
                        <a> id_datum ; rok ; mesiac ; den ; poc_na_kovid ; poc_s_kovid ; celkovo</a>
                        <?php
                    } ?>
                    <a></a>
                </div>

            </div>
        </div>
    </div>
        <?php }
        }
        ?>

<script>

    function setLinkValueUmrtia() {
        var a = document.getElementById("date");
        var b = document.getElementById("date2");
        var c = "";
        var d = "";
        var e = "";

        if(document.getElementById("umrtia_na_kov").checked){  c = "počet úmrtí na kovid";}
        if(document.getElementById("umrtia_s_kov").checked){  d = "počet úmrtí s kovid";}
        if(document.getElementById("celk").checked){ e = "celkový počet úmrtí ";}

        var vysl = "pdf.php?a=" + a.value + "&b=" + b.value + "&c="+ c + "&d=" + d + "&e=" +e;
        document.getElementById('umrtia').setAttribute("href",vysl);
    }
    function setLinkValueNemocnice() {
        var a = document.getElementById("date");
        var b = document.getElementById("date2");
        var c = "";
        var d = "";
        var e = "";
        var f = "";

        if(document.getElementById("obs").checked){  c = "počet úmrtí na kovid";}
        if(document.getElementById("pluc").checked){  d = "počet úmrtí s kovid";}
        if(document.getElementById("hosp").checked){ e = "celkový počet úmrtí ";}
        f = document.getElementById("tags").value;
        var vysl = "PDFHospitals.php?a=" + a.value + "&b=" + b.value + "&c="+ c + "&d=" + d + "&e=" +e + "&f=" + f;
        document.getElementById('nemocnice').setAttribute("href",vysl);
    }

    function setLinkValueDenne(){
        var a =document.getElementById("date");
        var b =document.getElementById("date2");
        var c = "";
        var d = "";
        var e = "";
        var f = "";
        var g = "";

        if(document.getElementById("pcr_pot").checked){  c = "Počet PCR potvrdených prípadov";}
        if(document.getElementById("pcr_poc").checked){  d = "Počet vykonaných PCR testov";}
        if(document.getElementById("pcr_poz").checked){  e = "Počet pozitívnych z PCR testov";}
        if(document.getElementById("ag_poc").checked){  f = "Počet vykonaných AG testov";}
        if(document.getElementById("ag_poz").checked){  g = "Počet pozitívnych z AG testov";}

        var vysl = "PDFDenne.php?a=" + a.value + "&b=" + b.value + "&c="+ c + "&d=" + d + "&e=" +e + "&f=" + f + "&g=" + g;
        document.getElementById('denne').setAttribute("href",vysl);

    }
    function setLinkValueKraje(){
        var a =document.getElementById("date");
        var b =document.getElementById("date2");
        var c = "";
        var d = "";
        var e = "";
        var f = "";
        var g = "";
        var h="";

        if(document.getElementById("ag_vyk").checked){  c = "Počet vykonaných AG testov";}
        if(document.getElementById("ag_poz").checked){  d = "Počet pozitívnych z AG testov";}
        if(document.getElementById("pcr_poz").checked){  e = "Počet pozitívnych z PCR testov";}
        if(document.getElementById("newcases").checked){  f = "Počet nových prípadov";}
        if(document.getElementById("poz_celk").checked){  g = "Počet celkovo pozitívnych prípadov";}
        h = document.getElementById("krajelist").value;
        var vysl = "PDFKraje.php?a=" + a.value + "&b=" + b.value + "&c="+ c + "&d=" + d + "&e=" +e + "&f=" + f + "&g=" + g + "&h="+h;
        document.getElementById('kraje').setAttribute("href",vysl);

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





