<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Korona</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
            crossorigin="anonymous"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://kit.fontawesome.com/e7858c52b6.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/e060c06e60.js" crossorigin="anonymous"></script>
    <script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
        }
        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }
    </script>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<div id="top">
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
        <img class="logo" src="pics/COVID-19.jpeg" alt="logo">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample06"
                aria-controls="navbarsExample06" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample06">
            <ul class="navbar-nav mr-auto" id="idecko" onload="active()">
                <li class="nav-item">
                    <a class="nav-link"  id="index" href="index.php">Home</a>
                </li>
                <li class="nav-item" >
                    <a class="nav-link"  id="info" href="Info.php">Info</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="Stats.php" id="dropdown06" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false" >Štatistiky</a>
                    <div id="stats" class="dropdown-menu" aria-labelledby="dropdown06">
                        <a class="dropdown-item" href="DenneTestovanie.php">Denné testovanie</a>
                        <a class="dropdown-item" href="Kraje.php">Kraje</a>
                        <a class="dropdown-item" href="Nemocnice.php">Nemocnice</a>
                        <a class="dropdown-item" href="Umrtia.php">Úmrtia</a>

                    </div>
                </li>
            </ul>
                <div class="topnav">
                    <div class="login-container">
                        <form method="post">
                            <input type="text" placeholder="Username" name="username">
                            <input type="password" placeholder="Password" name="psw">
                            <button type="submit" name="Send" class="fas fa-sign-in-alt" id="login" ></button>
                        </form>
                    </div>
                </div>
            <div class="topnav">
                <div class="login-container">
                    <form method="post">
                        <button type="submit" name="logout">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</div>




