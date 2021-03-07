<?php
require "header.php";
require_once "DB_storage.php";
$storage = new DB_storage();

?>
<!DOCTYPE html>
<html lang="en">
<head>

</head>

<script>
    document.getElementById("index").className += " active";
</script>
<div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">COVID-19 na Slovensku</h1>
    <p class="lead">Na tejto stránke môžte nájsť:</p>
</div>

<div class="container">
<div class="row posun">
    <div class="col-lg-6">
        <img src="pics/logo.png" width="140" height="140">
        <h2>Informácie</h2>
        <p>Základné informácie o ochorení COVID-19</p>
        <p><a class="btn btn-secondary" href="Info.php">Pozri viac &raquo;</a></p>
    </div>
    <div class="col-lg-6">
<img src="pics/graph.png" height="140" width="140">
        <h2>Štatistiky</h2>
        <p>Zozbierané štatistiky k ochoreniu COVID-19.</p>
        <p><a class="btn btn-secondary" href="Stats.php">Pozri viac &raquo;</a></p>
    </div>
</div>
</div>


<div class="container my-md-5">
    <div class="row bg-white p-5 rounded white">
           <div class="col-lg-6">
            <h1>Zásady R.O.R.</h1>
                <p class="lead">Správne nosenie rúšok (zakrytý nos aj ústa)</p>
                <p class="lead">Dodržiavanie odstupu od cudzích ľudí </p>
                <p class="lead">Časté umývanie rúk či ich dezinfekcia </p>

           </div>
           <div class="col-lg-6 zasady1">
                <img class="zasady" src="pics/zasady.jpeg" >
           </div>

    </div>
</div>
<?php
require "footer.php";
?>
</body>
</html>
