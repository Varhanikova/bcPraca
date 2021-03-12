<?php
require "header.php";
require_once "DB_storage.php";
$storage = new DB_storage();
$dat=$storage->getDatumy();
for($i=0;$i<5;$i++) {
?>



<p><?=$dat[$i] ?></p>







<?php
}
require "footer.php";
?>
