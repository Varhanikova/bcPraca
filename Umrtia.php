<?php
require "header.php";
$storage = new DB_storage();
$umrtia='';

if (isset($_POST['Send1'])) {
$chyba1 =0;
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
            $umrtia = $storage->getDeathsAtDate($_POST['date'], $_POST['date2']);
        }
    }
}

?>
<script>
     function oznac_vsetky() {
         if (document.getElementById('umrtia_na_kov').checked == false) {
             checkboxes = document.getElementById('umrtia_na_kov');
             checkboxes.checked = true;
             checkboxes1 = document.getElementById('umrtia_s_kov');
             checkboxes1.checked = true;
             checkboxes2 = document.getElementById('celk');
             checkboxes2.checked = true;

         } else {
             checkboxes = document.getElementById('umrtia_na_kov');
             checkboxes.checked = false;
             checkboxes1 = document.getElementById('umrtia_s_kov');
             checkboxes1.checked = false;
             checkboxes2 = document.getElementById('celk');
             checkboxes2.checked = false;

         }
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
        <label> Zvoľte si dátumy(voliteľné):  </label> <br>
            </div>
            <div class="col-lg-6">
                <label for="umrtia_na_kov"> Začiarknite položky, ktoré sa majú zobraziť:  </label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
        &emsp;<label> Od:  </label>
        <input type="date" name="date" id="date">
        <label> Do:   </label>
        <input type="date" name="date2" id="date" value="date"><br>
            </div>
            <div class="col-lg-6">
        &emsp; <input type="checkbox" id="umrtia_na_kov" name="umrtia_na_kov" value="na">
        <label > počet úmrtí na kovid</label><br>
        &emsp;  <input type="checkbox" id="umrtia_s_kov" name="umrtia_s_kov" value="s">
        <label > počet úmrtí s kovid</label><br>
        &emsp;  <input type="checkbox" id="celk" name="celk" value="celk">
        <label > celkový počet úmrtí </label><br>
                <input onclick="oznac_vsetky()" type="checkbox" id="v" name="v" value="v">
                <label > všetky </label><br>
            </div>
        </div>
         <input  type="submit" name="Send1" value="Zobraz">
    </form>

<p class="pb-4 mb-2 "></p>
<table>
    <tr>
        <?php  if($umrtia!='') { ?>
        <th>Dátum</th>
        <?php }?>
        <?php  if(isset($_POST['umrtia_na_kov']) || isset($_POST['v']) ) { ?>
            <th>Počet úmrtí na kovid</th>
        <?php }
        if(isset($_POST['umrtia_s_kov'])|| isset($_POST['v'])) { ?>
        <th>Počet úmrtí s kovid</th>
        <?php }
        if(isset($_POST['celk'])|| isset($_POST['v'])) { ?>
        <th>Celkový počet úmrtí</th>
        <?php } ?>
    </tr>
    <?php if($umrtia!='') {
    for($i=0;$i<sizeof($umrtia);$i++) {?>
    <tr>
        <td> <?= $umrtia[$i]->getDatum() ?></td>
      <?php  if(isset($_POST['umrtia_na_kov'])|| isset($_POST['v'])) { ?>
          <td><?=$umrtia[$i]->getPocNaKov() ?></td>
            <?php }
            if(isset($_POST['umrtia_s_kov'])|| isset($_POST['v'])) { ?>

        <td><?=$umrtia[$i]->getPocSKov() ?></td>
                <?php }
            if(isset($_POST['celk'])|| isset($_POST['v'])) { ?>
        <td><?=$umrtia[$i]->getCelk() ?></td>
                <?php } ?>
    </tr>
    <?php }} ?>
    </table>


</main>
</body>




<?php
require "footer.php";
?>
