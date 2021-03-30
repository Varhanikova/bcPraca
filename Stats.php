<?php
require "header.php";
?>
<!doctype html>
<html lang="en">
<head>
    <script>
        document.getElementById("stats").className += " active";
    </script>
</head>
<body>
<div class="container ">
    <hr class="featurette-divider ">
    <div class="row featurette">
        <div class="col-md-8">
            <h2 class="featurette-heading">1. štatistika: <span class="text-muted">Denné testovanie.</span></h2>
            <p class="lead">Štatistika v prvej tabuľke ukazuje po dňoch deň informácie o počte urobených PCR testov, počte pozitívnych z
                PCR testov,
                počte vykonaných AG testov, počte pozitívnych z AG testov a nakoniec o celkovom počte potvrdených z PCR
                testov.<br>
            Druhá tabuľka zobrazuje mesačne v percentách počet pozitívne testovaných z PCR testovania aj z AG testovanie. </p>
            <p><a class="btn btn-secondary" href="DenneTestovanie.php">Pozri štatistiku &raquo;</a></p>
        </div>

    </div>

    <hr class="featurette-divider ">

    <div class="row featurette my-md-5 pt-md-5 border-top">
        <div class="col-md-8 order-md-2">
            <h2 class="featurette-heading">2. štatistika: <span class="text-muted">Testovanie po krajoch.</span></h2>
            <p class="lead">Štatistika v prvej tabuľke ukazuje jednotlivé kraje po dňoch s informáciami o vykonaných AG testov ,
                počtu pozitívnych z AG testov, počet pozitívnych z PCR testov, počet nových prípadov a koľko je celkovo
                pozitívnych.<br>
            V druhej tabuľke nájdeme priemerný počet pozitívne testovaných PCR aj AG testami vo zvolenom kraji v jednotlivých mesiasoch.
            </p>
            <p><a class="btn btn-secondary" href="Kraje.php"">Pozri štatistiku &raquo;</a></p>
        </div>
        <div class="col-md-3 order-md-1">

        </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette my-md-5 pt-md-5 border-top">
        <div class="col-md-8">
            <h2 class="featurette-heading">3. štatistika: <span class="text-muted">Štatistiky nemocníc.</span></h2>
            <p class="lead">Štatistika v prvej tabuľke ukazuje počty obsadenosti lôžok, použitých pľúcnych ventilácií a počty hospitalizovaných vo zvolenom okrese v jednotlivých dňoch.
                <br> Druhá tabuľka obsahuje jednotlivé počty zo všetkých nemocníc na Slovensku zoradené po dňoch.</p>
            <p><a class="btn btn-secondary" href="Nemocnice.php">Pozri štatistiku &raquo;</a></p>
        </div>

    </div>
    <hr class="featurette-divider ">

    <div class="row featurette my-md-5 pt-md-5 border-top">
        <div class="col-md-8 order-md-2">
            <h2 class="featurette-heading">4. štatistika: <span class="text-muted">Štatistiky úmrtí.</span></h2>
            <p class="lead">Štatistika ukazuje po dňoch informácie o počte úmrtí na ochorenie COVID-19, počte úmrtí s
                týmto ochorením
                a celkový počet úmrtí na toto ochorenie.<br> V druhej tabuľke sú percentuálne vyjadrené úmrtia za dané mesiace.</p>
            <p><a class="btn btn-secondary" href="Umrtia.php">Pozri štatistiku &raquo;</a></p>
        </div>
        <div class="col-md-3 order-md-1">

        </div>
    </div>
    <hr class="featurette-divider">


</div>

<?php
require "parts/footer.php";
?>
</body>
</html>
