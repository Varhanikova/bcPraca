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
$ulozene = "";
if(isset($_POST['krajelist'])){
    $ulozene = $_POST['krajelist'];
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

<main class="container ">
     <form method="post">
        <div class="row pb-4 mb-4">
            <?php if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false || strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>
                <div class="column col-lg-4">
                    <div>
                        <label> Zvoľte si dátumy: </label>
                    </div>
                    <?php if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { ?>
                        <div>
                            <?php if (isset($_POST['Send1'])) {
                                $od2 = $_POST['date'];
                            } else {
                                $od2 = $storage->getDate('min', 'kraje_stat');
                            } ?>

                            <label> Od: </label>
                            <input type="date" name="date" id="date"
                                   value="<?= $od2 ?>"
                                   min="<?= $storage->getDate('min', 'kraje_stat') ?>"
                                   max="<?= $storage->getDate('max', 'kraje_stat') ?>">
                            <br>
                            <label> Do: </label>
                            <?php if (isset($_POST['Send1'])) {
                                $do2 = $_POST['date2'];
                            } else {
                                $do2 = $storage->getDate('max', 'kraje_stat');
                            } ?>

                            <input type="date" name="date2" id="date2"
                                   value="<?= $do2 ?>"
                                   min="<?= $storage->getDate('min', 'kraje_stat') ?>"
                                   max="<?= $storage->getDate('max', 'kraje_stat') ?>">
                            <br><br>
                        </div>
                    <?php } else if (strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>
                        <div>
                            <?php if (isset($_POST['Send1'])) {
                                $od3 = $_POST['date'];
                            } else {
                                $od3 = $storage->getDate('min', 'hospitals_stat');
                            } ?>

                            <label> Od: </label>
                            <input type="date" name="date" id="date"
                                   value="<?= $od3 ?>"
                                   max="<?= $storage->getDate('max', 'hospitals_stat') ?>"
                                   min="<?= $storage->getDate('min', 'hospitals_stat') ?>"><br>
                            <label> Do: </label>
                            <?php if (isset($_POST['Send1'])) {
                                $do3 = $_POST['date2'];
                            } else {
                                $do3 = $storage->getDate('max', 'hospitals_stat');
                            } ?>

                            <input type="date" name="date2" id="date2"
                                   value="<?= $do3 ?>"
                                   max="<?= $storage->getDate('max', 'hospitals_stat') ?>"
                                   min="<?= $storage->getDate('min', 'hospitals_stat') ?>"><br>
                        </div> <?php } ?>

                </div>
                <div class="column col-lg-4">
                    <div>
                        <?php if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { ?>
                            <label for="krajelist">Zvoľte kraj:</label>
                        <?php } else { ?>
                            <label for="krajelist">Zvoľte okres:</label>
                        <?php } ?>
                    </div>
                    <?php if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { ?>
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
                    <?php } else if (strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>
                        <?php if (isset($_POST['Send1'])) {
                            $okres = $_POST['tags'];
                        } else {
                            $okres = "Okres Bratislava I";
                        } ?>
                        <div>
                            <div class="ui-widget">
                                <label for="tags"> </label>
                                <input id="tags" name="tags" value="<?= $okres ?>">
                            </div>
                        </div> <?php } ?>

                </div>

                <div class="column col-lg-4">
                    <div>
                        <label> Začiarknite položky, ktoré sa majú zobraziť: </label>
                    </div>
                    <?php if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { ?>
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
                    <?php } else if (strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>

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
                        </div> <?php } ?>

                </div>


            <?php } else if (strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false || strpos($_SERVER['REQUEST_URI'], "DenneTestovanie") !== false) { ?>

            <div class="column col-lg-6">
                <div>
                    <label> Zvoľte si dátumy: </label> <br>
                </div>
                <?php if (strpos($_SERVER['REQUEST_URI'], "DenneTestovanie") !== false) { ?>
                    <div>
                        <div>
                            <?php if (isset($_POST['Send1'])) {
                                $od = $_POST['date'];
                            } else {
                                $od = $storage->getDate('min', 'kazdodenne_stat');
                            } ?>
                            &emsp;<label for="date"> Od: </label>
                            <input type="date" name="date" id="date"
                                   value="<?= $od ?>"
                                   max="<?= $storage->getDate('max', 'kazdodenne_stat') ?>"
                                   min="<?= $storage->getDate('min', 'kazdodenne_stat') ?>">
                        </div>
                        <div>
                            <?php if (isset($_POST['Send1'])) {
                                $do = $_POST['date2'];
                            } else {
                                $do = $storage->getDate('max', 'kazdodenne_stat');
                            } ?>

                            &emsp;<label for="date2"> Do: </label>
                            <input type="date" name="date2" id="date2"
                                   value="<?= $do ?>"
                                   max="<?= $storage->getDate('max', 'kazdodenne_stat') ?>"
                                   min="<?= $storage->getDate('min', 'kazdodenne_stat') ?>"<br>
                        </div>
                    </div>

                <?php } else if (strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false) { ?>
                    <div>
                        <div>
                            <?php if (isset($_POST['Send1'])) {
                                $od1 = $_POST['date'];
                            } else {
                                $od1 = $storage->getDate('min', 'deaths_stat');
                            } ?>

                            &emsp;<label for="date"> Od: </label>
                            <input type="date" name="date" id="date"
                                   value="<?= $od1 ?>"
                                   max="<?= $storage->getDate('max', 'deaths_stat') ?>"
                                   min="<?= $storage->getDate('min', 'deaths_stat') ?>">
                        </div>
                        <div>
                            <?php if (isset($_POST['Send1'])) {
                                $do1 = $_POST['date2'];
                            } else {
                                $do1 = $storage->getDate('max', 'deaths_stat');
                            } ?>

                            &emsp;<label for="date2"> Do: </label>
                            <input type="date" name="date2" id="date2"
                                   value="<?= $do1 ?>"
                                   max="<?= $storage->getDate('max', 'deaths_stat') ?>"
                                   min="<?= $storage->getDate('min', 'deaths_stat') ?>"><br>
                        </div>
                    </div>
                <?php } ?>

            </div>
            <div class="column col-lg-6">
                <div>
                    <label for="umrtia_na_kov"> Začiarknite položky, ktoré sa majú zobraziť: </label>
                </div>
                <?php if (strpos($_SERVER['REQUEST_URI'], "DenneTestovanie") !== false){ ?>
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
                    <?php } else if (strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false) { ?>

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
                        <?php } ?>
                        <label for="v"> všetky </label><br>
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>
        <div class="row pb-4 mb-4">
            <div class="col-sm-1">
                <button onclick="displayResults(1)" name="Send1">Filtruj</button>
            </div>
        </div>
  </form>
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

                        <?php if (isset($_POST['Send1']) && strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false) { ?>
                            <p><a class="btn btn-secondary "
                                  href="pdf.php?a=<?= $_POST['date'] ?>&b=<?= $_POST['date2'] ?>&d=<?= isset($_POST['umrtia_s_kov']) ?>&c=<?= isset($_POST['umrtia_na_kov']) ?>&e=<?= isset($_POST['celk']) ?>">
                                    PDF export &raquo;</a></p>
                        <?php } else if (strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false) { ?>
                            <p><a class="btn btn-secondary " href="pdf.php?a=a&b=a"> PDF export &raquo;</a></p>
                        <?php } else if (isset($_POST['Send1']) && strpos($_SERVER['REQUEST_URI'], "DenneTestovanie") !== false) { ?>
                            <p><a class="btn btn-secondary "
                                  href="PDFDenne.php?a=<?= $_POST['date'] ?>&b=<?= $_POST['date2'] ?>&c=<?= isset($_POST['pcr_pot']) ?>&d=<?= isset($_POST['pcr_poc']) ?>&e=<?= isset($_POST['pcr_poz']) ?>&f=<?= isset($_POST['ag_poc']) ?>&g=<?= isset($_POST['ag_poz']) ?>">
                                    PDF export &raquo;</a></p>
                        <?php } else if (strpos($_SERVER['REQUEST_URI'], "DenneTestovanie") !== false) { ?>
                            <p><a class="btn btn-secondary " href="PDFDenne.php?a=a&b=a"> PDF export &raquo;</a></p>
                        <?php } else if (isset($_POST['Send1']) && strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>
                            <p><a class="btn btn-secondary "
                                  href="PDFHospitals.php?a=<?= $_POST['date'] ?>&b=<?= $_POST['date2'] ?>&c=<?= isset($_POST['obs']) ?>&d=<?= isset($_POST['pluc']) ?>&e=<?= isset($_POST['hosp']) ?>&f=<?= ($_POST['tags']) ?>&g=">
                                    PDF export &raquo;</a></p>
                        <?php } else if (strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>
                            <p><a class="btn btn-secondary " href="PDFHospitals.php?a=a&b=a"> PDF export &raquo;</a></p>
                        <?php } else if (isset($_POST['Send1']) && strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { ?>
                            <p><a class="btn btn-secondary "
                                  href="PDFKraje.php?a=<?= $_POST['date'] ?>&b=<?= $_POST['date2'] ?>&c=<?= isset($_POST['ag_vyk']) ?>&d=<?= isset($_POST['ag_poz']) ?>&e=<?= isset($_POST['pcr_poz']) ?>&f=<?= isset($_POST['newcases']) ?>&g=<?= isset($_POST['poz_celk']) ?>&h=<?= $_POST['krajelist'] ?>">
                                    PDF export &raquo;</a></p>
                        <?php } else if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { ?>
                            <p><a class="btn btn-secondary " href="PDFKraje.php?a=a&b=a"> PDF export &raquo;</a></p>
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
</main>
<main class="container ">
    <p class='pb-4 mb-2 '></p>
    <table id="tu">
    </table>
    <p class='pb-4 mb-2 '></p>
    <div class="col-lg-12 text-center pb-4 mb-4 fst-italic border-bottom">
        <?php// if (isset($_POST['Send1'])) { ?>
        <input id="prev" onclick="previous()" type="button" value="< späť"/>
        <input id="next" onclick="next()" type="button" value="ďalej >"/>
        <?php// } ?>
    </div>
</main>





