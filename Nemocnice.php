<?php
require "header.php";
$storage = new DB_storage();
$hosp = "";
$nemocnice = $storage->getHospitals();
$chcem ="";
if (isset($_POST['Send1'])) {
    $chyba1 =0;
    if($_POST['nemocncielist']=='všetky' || empty($_POST['nemocncielist'])){
        $chcem = "";
    } else {
        $chcem = "and nazov = '" .$_POST['nemocncielist'] . "' ";
    }

    if (!empty($_POST['date']) && $storage->isThere($_POST['date']) == '' ) { $chyba1=1;
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if(empty($_POST['date'])) {
        $_POST['date'] = "2020-09-24";
    }
    if(($_POST['date2']!="") && $storage->isThere($_POST['date2'])=='') {
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php  } else if($_POST['date2']==""){$_POST['date2']="2021-02-14";}

    if($chyba1==0){
        if(isset($_POST['date2'])) {
            $hosp = $storage->getAllHospitalStat($_POST['date'], $_POST['date2'],$chcem);
        }
    }
}

?>


<body>
<main class="container">

        <h3 class="pb-4 mb-4 fst-italic border-bottom  ">
            Štatistika úmrtí:
        </h3>
        <form method="post">
            <div class="row">
                <div class="col-lg-6">
                    <label> Zvoľte si dátumy(voliteľné):  </label> <br>
                </div>

                <div class="col-lg-6">
                    <label for="umrtia_na_kov"> Začiarknite položky, ktoré sa majú zobraziť:  </label>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <label> Od:  </label>
                    <input type="date" name="date" id="date"><br>
                    <label> Do:   </label>
                    <input type="date" name="date2" id="date" value="date"><br>
                </div>


                <div class="col-lg-6">
                    &emsp; <input type="checkbox" id="obs" name="obs" value="obs">
                    <label > Počet obsadených lôžok</label><br>
                    &emsp;  <input type="checkbox" id="pluc" name="pluc" value="pluc">
                    <label > Počet osôb na pľúcnej ventilácii</label><br>
                    &emsp;  <input type="checkbox" id="hosp" name="hosp" value="hos">
                    <label > Celkový počet hospitalizovaných </label><br>
                    <input onclick="oznac_vsetky()" type="checkbox" id="v" name="v" value="v">
                    <label > všetky </label><br>
                </div>
            </div>
            <div class="row pb-4 mb-4 fst-italic">
                <div >
                    <label for="krajelist">Zvoľte nemocnicu:</label>
                </div>
                <div class="col-lg-2">
                    <select id="nemocncielist" name="nemocncielist">
                        <?php for($i=0;$i<sizeof($nemocnice);$i++) { ?>
                            <option value="<?=$nemocnice[$i]->getNazov()?>"><?=$nemocnice[$i]->getNazov()?> </option>
                        <?php }?>
                        <option value="všetky">všetky</option>
                    </select>
                </div></div>
            <input  type="submit" name="Send1" value="Zobraz">
        </form>
        <p class="pb-4 mb-2 "></p>
        <table>
            <tr>
                <?php  if($hosp!='') { ?>
                    <th>Dátum</th>
                    <th>Názov nemocnice</th>

                <?php }if(isset($_POST['obs']) || isset($_POST['v']) ) { ?>
       <th>Počet obsadených lôžok</th>
        <?php } if(isset($_POST['pluc']) || isset($_POST['v']) ) { ?>
    <th>Počet osôb na pľúcnej ventilácii</th>
        <?php } if(isset($_POST['hosp']) || isset($_POST['v']) ) { ?>
     <th>Celkový počet hospitalizovaných </th>
        <?php } ?>
            </tr>
            <?php
            if($hosp!='') { for($i=0;$i<sizeof($hosp);$i++) {?>
                <tr>
                    <td> <?= $hosp[$i]->getDatum() ?></td>
                    <td><?=$hosp[$i]->getNemocnica() ?></td>
                    <?php if(isset($_POST['obs'])|| isset($_POST['v'])) { ?>
                    <td><?=$hosp[$i]->getObsadeneLozka() ?></td>
                <?php } if(isset($_POST['pluc'])|| isset($_POST['v'])) { ?>
                    <td><?=$hosp[$i]->getPlucVent() ?></td>
                <?php } if(isset($_POST['hosp'])|| isset($_POST['v'])) { ?>
                    <td><?=$hosp[$i]->getHospitalizovani() ?></td>
                    <?php } ?>
                </tr>
            <?php }} ?>
        </table>


</main>
</body>

<?php
require "footer.php";
?>
