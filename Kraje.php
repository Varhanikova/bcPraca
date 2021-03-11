<?php
require "header.php";
require_once "DB_storage.php";
$storage = new DB_storage();
$array = $storage->getDatumy();

?>
<div id="nabielo">
<p> <?php echo $array[0]->getDen() ?></p>
</div>





<?php
require "footer.php";
?>
