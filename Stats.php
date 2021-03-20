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
            <p class="lead">Štatistika ukazuje po dňoch deň informácie o počte urobených PCR testov, počte pozitívnych z
                PCR testov,
                počte vykonaných AG testo, počte pozitívnych z AG testov a nakoniec o celkovom počte potvrdených z PCR
                testov.</p>
            <p><a class="btn btn-secondary" href="DenneTestovanie.php">Pozri štatistiku &raquo;</a></p>
        </div>

    </div>

    <hr class="featurette-divider ">

    <div class="row featurette my-md-5 pt-md-5 border-top">
        <div class="col-md-8 order-md-2">
            <h2 class="featurette-heading">2. štatistika: <span class="text-muted">Testovanie po krajoch.</span></h2>
            <p class="lead">Štatistika ukazuje jednotlivé kraje po dňoch s informáciami o vykonaných AG testov ,
                počtu pozitívnych z AG testov, počet pozitívnych z PCR testov, počet nových prípadov a koľko je celkovo
                pozitívnych.</p>
            <p><a class="btn btn-secondary" href="Kraje.php"">Pozri štatistiku &raquo;</a></p>
        </div>
        <div class="col-md-3 order-md-1">

        </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette my-md-5 pt-md-5 border-top">
        <div class="col-md-8">
            <h2 class="featurette-heading">3. štatistika: <span class="text-muted">Štatistiky nemocníc.</span></h2>
            <p class="lead">Štatistika ukazuje jednotlivé nemocnice po dňoch s informáciami o obsadenosti lôžok, počte
                použitách
                pľúcnych ventilácií a počte hospitalizovaných.</p>
            <p><a class="btn btn-secondary" href="Nemocnice.php">Pozri štatistiku &raquo;</a></p>
        </div>

    </div>
    <hr class="featurette-divider ">

    <div class="row featurette my-md-5 pt-md-5 border-top">
        <div class="col-md-8 order-md-2">
            <h2 class="featurette-heading">4. štatistika: <span class="text-muted">Štatistiky úmrtí.</span></h2>
            <p class="lead">Štatistika ukazuje po dňoch informácie o počte úmrtí na ochorenie COVID-19, počte úmrtí s
                týmto ochorením
                a celkový počet úmrtí na toto ochorenie.</p>
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
