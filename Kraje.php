<?php
require "header.php";
require_once "DB_storage.php";
$storage = new DB_storage();
$array = "";
$chcem="";
if (isset($_POST['Send1'])) {
    $chyba1 =0;
    if($_POST['krajelist']=='všetky' || empty($_POST['krajelist'])){
       $chcem = "";
    } else {
        $chcem = "and kraj = '" .$_POST['krajelist'] . "' ";
    }

    if(($_POST['date2']!="") && $storage->isThere($_POST['date2'])=='') {
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php  } else if($_POST['date2']==""){$_POST['date2']="2021-02-13";}

    if (!empty($_POST['date']) && $storage->isThere($_POST['date']) == '' ) { $chyba1=1;
        ?>
        <script>
            window.alert("Neplatný dátum");
        </script>
    <?php } else if(empty($_POST['date'])) {
        $_POST['date'] = "2020-09-03";
    }
    if($chyba1==0){
        if(isset($_POST['date2'])) {
            $array = $storage->getAllKrajeStat($_POST['date'], $_POST['date2'],$chcem);

        }
    }
}

?>
<script>
    function oznac_vsetky() {
    if(document.getElementById('ag_vyk').checked == false) {
        checkboxes = document.getElementById('ag_vyk');
        checkboxes.checked = true;
        checkboxes1 = document.getElementById('ag_poz');
        checkboxes1.checked = true;
        checkboxes2 = document.getElementById('pcr_poz');
        checkboxes2.checked = true;
        checkboxes3 = document.getElementById('poz_celk');
        checkboxes3.checked = true;
        checkboxes4 = document.getElementById('newcases');
        checkboxes4.checked = true;
    } else {
        checkboxes = document.getElementById('ag_vyk');
        checkboxes.checked = false;
        checkboxes1 = document.getElementById('ag_poz');
        checkboxes1.checked = false;
        checkboxes2 = document.getElementById('pcr_poz');
        checkboxes2.checked = false;
        checkboxes3 = document.getElementById('poz_celk');
        checkboxes3.checked = false;
        checkboxes4 = document.getElementById('newcases');
        checkboxes4.checked = false;
    }
    }

</script>
<body>
<main class="container">
        <h3 class="pb-4 mb-4 fst-italic border-bottom ">
            Štatistika testovania po krajoch:
        </h3>

        <form method="post">
            <div class="row">
                <div class="col-lg-4">
                 <label> Zvoľte si dátumy(voliteľné):  </label>
                </div>
                <div class="col-lg-4">
                    <label for="krajelist">Zvoľte kraj:</label>
                </div>
                <div class="col-lg-4">
                    <label> Začiarknite položky, ktoré sa majú zobraziť:  </label>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                   <label> Od:  </label>
                     <input type="date" name="date" id="date"> <br>
                     <label> Do:   </label>
                    <input type="date" name="date2" id="date" value="date"> <br><br>
                </div>
                 <div class="col-lg-4">
                      <select id="krajelist" name="krajelist">
                        <option value="Bratislavský kraj">Bratislavský kraj</option>
                        <option value="Trnavský kraj">Trnavský kraj</option>
                        <option value="Trenčiansky kraj">Trenčiansky kraj</option>
                        <option value="Nitriansky kraj">Nitriansky kraj</option>
                        <option value="Žilinský kraj">Žilinský kraj</option>
                        <option value="Banskobystrický kraj">Banskobystrický kraj</option>
                        <option value="Prešovský kraj">Prešovský kraj</option>
                        <option value="Košický kraj">Košický kraj</option>
                        <option value="všetky">všetky</option>
                      </select>
                 </div>
                <div class="col-lg-4">


                    &emsp; <input type="checkbox" id="ag_vyk" name="ag_vyk" value="av">
                    <label > Počet vykonaných Ag testov</label><br>
                    &emsp;  <input type="checkbox" id="ag_poz" name="ag_poz" value="ap">
                    <label > Počet pozitívnych Ag testov</label><br>
                    &emsp;  <input type="checkbox" id="pcr_poz" name="pcr_poz" value="pp">
                    <label > Počet pozitívnych PCR testov </label><br>
                    &emsp; <input type="checkbox" id="newcases" name="newcases" value="nc">
                    <label > Počet nových prípadov</label><br>
                    &emsp; <input type="checkbox" id="poz_celk" name="poz_celk" value="pc">
                    <label > Počet pozitívnych celkom</label><br>
                    <input onclick="oznac_vsetky()" type="checkbox" id="v" name="v">
                    <label > všetky </label><br>
                </div>

            </div>
            <input  type="submit" name="Send1" value="Zobraz">
        </form>
        <p class="pb-4 mb-2 "></p>
        <table >
            <tr>
                <?php  if($array!='') { ?>
                    <th>Kraje</th>
                    <th>Dátum</th>
                <?php }?>
                     <?php  if(isset($_POST['ag_vyk']) || isset($_POST['v'])) { ?>
           <th>Počet vykonaných Ag testov</th>
        <?php } if(isset($_POST['ag_poz']) || isset($_POST['v']) ) { ?>
           <th>Počet pozitívnych Ag testov</th>
        <?php } if(isset($_POST['pcr_poz'])|| isset($_POST['v'])) { ?>
           <th>Počet pozitívnych PCR testov</th>
        <?php } if(isset($_POST['newcases'])|| isset($_POST['v'])) { ?>
             <th>Počet nových prípadov</th>
        <?php } if(isset($_POST['poz_celk'])|| isset($_POST['v'])) { ?>
                <th>Počet pozitívnych celkom</th>
        <?php }?>

            </tr>
            <?php if($array!='') {
             for($i=0;$i< sizeof($array);$i++) {?>
                <tr>
                    <td> <?=$array[$i]->getIdKraj()?></td>
                    <td ><?=$array[$i]->getIdDatum() ?></td>
                     <?php  if(isset($_POST['ag_vyk'])|| isset($_POST['v'])) { ?>
             <td><?=$array[$i]->getAgVyk() ?></td>
            <?php } if(isset($_POST['ag_poz'])|| isset($_POST['v'])) { ?>
          <td><?=$array[$i]->getAgPoz() ?></td>
            <?php } if(isset($_POST['pcr_poz'])|| isset($_POST['v'])) { ?>
         <td><?=$array[$i]->getPcrPoz() ?></td>
            <?php } if(isset($_POST['newcases'])|| isset($_POST['v'])) { ?>
      <td><?=$array[$i]->getNewcases() ?></td>
            <?php } if(isset($_POST['poz_celk'])|| isset($_POST['v'])) { ?>
                    <td><?=$array[$i]->getPozCelk() ?></td>
            <?php }?>
                </tr>
            <?php }} ?>
        </table>


</main>
</body>





<?php
require "footer.php";
?>
