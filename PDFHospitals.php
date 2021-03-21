<?php
require('../fpdf/fpdf.php');
require "DB_storage.php";

$storage = new DB_storage();

class PDFHospitals extends FPDF
{
    function Header()
    {
        $this->SetFont('Helvetica', 'B', 15);
        $this->SetXY(12, 10);
        $this->Cell(0, 10, 'Nemocnice', 1, 0, 'C');


    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 10);
        $this->Cell(0, 10, $this->PageNo(), 0, 0, 'C');
    }
}


$c = " ";
$d = " ";
$e = " ";
$f = " ";
if ($_GET['a'] == "a") {
    $stat = $storage->getAllHospital_stat();
} else {
    $chcem = "and okres = '" . $_GET['f'] . "' ";
    $stat = $storage->getHospitalStat($_GET['a'], $_GET['b'], $chcem);
    $c = $_GET['c'];
    $d = $_GET['d'];
    $e = $_GET['e'];
    $f = $_GET['f'];
}

$pdf = new PDFHospitals();


$pdf->SetTitle('Hospitals');

$y = 46;
for ($i = 0; $i < sizeof($stat); $i++) {
    if ($c == "" && $d!="" && $e!="" || $d=="" && $c!="" && $e!="" || $e=="" && $c!="" && $d!="") {
        $x = 9;
        $xx = 9;
        $nem = 110;
    } else if($c != "" && $d != "" && $e != "") {
        $nem=85;
        $x = 5;
        $xx = 5;
}else {
        $nem=130;
        $x = 10;
        $xx = 10;
    }
    if ($i % 16 == 0) {
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetY(26);
        $pdf->SetX($x);

        $pdf->MultiCell(25, 19.5, 'Datum', 1, 'L', 1);
        $x+=25;
        $pdf->SetY(26);
        $pdf->SetX($x);
        $pdf->MultiCell($nem, 19.5, 'Nazov nemocnice', 1, 'L', 1);
        $x+=$nem;

        if ($c != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->MultiCell(30, 6.5, 'Pocet obsadenych lozok', 1, 'L', 1);
            $x += 30;

        }
        if ($d != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->multiCell(30, 6.5, 'Pocet osob na plucnej ventilacii', 1, 'L', 1);
            $x += 30;
        }
        if ($e != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->multiCell(30, 4.9, 'Celkovy pocet hospitalizovanych', 1, 'L', 1);
            $x += 30;
        }
        $y = 46;
    }

    $pdf->Ln();
    $pdf->SetY($y);
    $pdf->SetX($xx);
    $pdf->multiCell(25, 14, $stat[$i]->getDatum(), 1);
    $xx+=25;

    $pdf->SetY($y);
    $pdf->SetX($xx);
    if ((($i % 16) == 15 && $i > 0) || ($i + 1) == sizeof($stat)) {
        $pdf->multicell($nem, 4.7, $stat[$i]->getNemocnica(), 1);
    } else {
        $pdf->multicell($nem, 4.7, $stat[$i]->getNemocnica(), 'LRT');
    }
    $xx += $nem;
    if ($c != "") {
        $pdf->SetY($y);
        $pdf->SetX($xx);
        $pdf->multiCell(30, 14, $stat[$i]->getObsadeneLozka(), 1);
        $xx += 30;
    }
    if ($d != "") {
        $pdf->SetY($y);
        $pdf->SetX($xx);
        $pdf->multiCell(30, 14, $stat[$i]->getPlucVent(), 1);
        $xx += 30;
    }
    if ($e != "") {
        $pdf->SetY($y);
        $pdf->SetX($xx);
        $pdf->multiCell(30, 14, $stat[$i]->getHospitalizovani(), 1);
        $xx += 30;
    }
    $y += 14;
}

$pdf->Output('hospitals_stat.pdf', 'I');
?>