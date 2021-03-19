<?php
require_once "DB_storage.php";
$storage = new DB_storage();
$kraje = $storage->getKraje();

if (isset($_POST['import'])) {
    $fileName = $_FILES['myFile']['tmp_name'];
    if ($_FILES['myFile']['size'] > 0) {
        $file = fopen($fileName, "r");
        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
            $iddat = "";
            if (isset($column[0])) {
                $iddat = $column[0];
            }
            $pockov = "";
            if (isset($column[1])) {
                $pockov = $column[1];
            }
            $pocskov = "";
            if (isset($column[2])) {
                $pocskov = $column[2];
            }
            $celk = "";
            if (isset($column[3])) {
                $celk = $column[3];
            }
            $storage->importDeaths($iddat,$pockov,$pocskov,$celk);
        }
    }

}
?>

<script>
    function imp() {
        if(document.getElementById("rozbal").style.display== "none") {
            document.getElementById("rozbal").style.display = "block";
        } else {
            document.getElementById("rozbal").style.display = "none";
        }
    }

    function exp() {
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
</script>

<main class="container">

    <form method="post">
        <div class="row pb-4 mb-4">
            <?php if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false || strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>
                <div class="column col-lg-4">
                    <div>
                        <label> Zvoľte si dátumy: </label>
                    </div>
                    <?php if (strpos($_SERVER['REQUEST_URI'], "Kraje") !== false) { ?>
                        <div>
                            <label> Od: </label>
                            <input type="date" name="date" id="date" value="2020-09-03" min="2020-09-03"
                                   max="2021-02-17">
                            <br>
                            <label> Do: </label>
                            <input type="date" name="date2" id="date2" value="2021-02-17" min="2020-09-03"
                                   max="2021-02-17">
                            <br><br>
                        </div>
                    <?php } else if (strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>
                        <div>
                            <label> Od: </label>
                            <input type="date" name="date" id="date" value="2020-10-01" max="2021-02-18"
                                   min="2020-10-01"><br>
                            <label> Do: </label>
                            <input type="date" name="date2" id="date2" value="2021-02-18" max="2021-02-18"
                                   min="2020-10-01"><br>
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
                                <?php for ($i = 0; $i < sizeof($kraje); $i++) { ?>
                                    <option value="<?= $kraje[$i]->getKraj() ?>"><?= $kraje[$i]->getKraj() ?> </option>
                                <?php } ?>
                                <option value="všetky">všetky</option>
                            </select>
                        </div>
                    <?php } else if (strpos($_SERVER['REQUEST_URI'], "Nemocnice") !== false) { ?>

                        <div>
                            <div class="ui-widget">
                                <label for="tags"> </label>
                                <input id="tags" name="tags" value="Okres Bratislava I">
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
                        &emsp;<label for="date"> Od: </label>
                        <input type="date" name="date" id="date" value="2020-09-24" max="2021-02-18" min="2020-09-24">
                        <label for="date2"> Do: </label>
                        <input type="date" name="date2" id="date2" value="2021-02-18" max="2021-02-18" min="2020-09-24"><br>
                    </div>

                <?php } else if (strpos($_SERVER['REQUEST_URI'], "Umrtia") !== false) { ?>
                    <div>
                        &emsp;<label for="date"> Od: </label>
                        <input type="date" name="date" id="date" value="2020-09-24" max="2021-02-14" min="2020-09-24">
                        <label for="date2"> Do: </label>
                        <input type="date" name="date2" id="date2" value="2021-02-14" max="2021-02-14" min="2020-09-24"><br>
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
        <div class="row ">
            <div class="col-lg-6">
                <input type="submit" name="Send1" value="Zobraz">
            </div>
            <?php if (isset($_SESSION["name"])) {
                if ($_SESSION["name"] == 'admin') {
                    ?>
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
                                    <p><a class="btn btn-secondary " > PDF export &raquo;</a></p>
                                </div>
                            </div>
                            <div class="row col-lg-12 " id="rozbal" style="display: none">
                                <form class="form-horizontal" action="" method="post" name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                                    <input type="file" name="myFile" id="myFile" accept=".csv">
                                    <button type="submit" id="submit" name="import"  class="btn-submit">Import</button>
                                    <br/>
                                </form>
                            </div>

                        </div>
                    </div>

                <?php }
            }
            ?>
        </div>
    </form>
</main>
<main class="container">
    <p class='pb-4 mb-2 '></p>
    <table id="tu">

    </table>
    <p class='pb-4 mb-2 '></p>
    <?php if (isset($_POST['Send1'])) { ?>

    <div class="col-lg-11 text-center">
        <input id="prev" onclick="previous()" type="button" value="< späť"/>
        <input id="next" onclick="next()" type="button" value="ďalej >"/>
    </div>
</main>
<?php } ?>




