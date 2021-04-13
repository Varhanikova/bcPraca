<?php
require('../fpdf/fpdf.php');
require "DB_storage.php";

$storage = new DB_storage();
/**
 * trieda pre export do PDF tabuľky nemocníc
 */
class PDFHospitals extends FPDF
{
    /**
     * hlavička súboru
     */
    function Header()
    {
        $this->SetFont('Helvetica', 'B', 15);
        $this->SetXY(12, 10);
        $this->Cell(0, 10, 'Nemocnice', 1, 0, 'C');
    }
    /**
     * päta súboru
     */
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 10);
        $this->Cell(0, 10, $this->PageNo(), 0, 0, 'C');
    }
}
/**
 * získanie premenných z $_GET
 */
$c = " ";
$d = " ";
$e = " ";
$f = " ";
if ($_GET['a'] == "a") {
    $stat = $storage->getAllHospital_stat();
} else {
    $chcem = $_GET['f'];
    $stat = $storage->getAllHospital_stat1($_GET['a'], $_GET['b'], $chcem);
    $c = $_GET['c'];
    $d = $_GET['d'];
    $e = $_GET['e'];
    $f = $_GET['f'];
}

$pdf = new PDFHospitals();
$pdf->SetTitle('Hospitals');
$y = 46;
for ($i = 0; $i < sizeof($stat); $i++) {
        $x = 15;
    if ($i % 16 == 0) {
        /**
         * vytvorenie novej strany PDF súboru
         */
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetY(26);
        $pdf->SetX($x);
        /**
         * výpis každého zvoleného stĺpca tabuľky
         */
        $pdf->MultiCell(25, 15, 'Datum', 1, 'L', 1);
        $x+=25;
        $pdf->SetY(26);
        $pdf->SetX($x);
        $pdf->MultiCell(50, 15, 'Okres', 1, 'L', 1);
        $x+=50;

        if ($c != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->MultiCell(35, 5, 'Pocet obsadenych lozok', 1, 'L', 1);
            $x += 35;

        }
        if ($d != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->multiCell(35, 5, 'Pocet osob na plucnej ventilacii', 1, 'L', 1);
            $x += 35;
        }
        if ($e != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->multiCell(35, 5, 'Celkovy pocet hospitalizovanych', 1, 'L', 1);
            $x += 35;
        }
        $y = 41;
    }
    /**
     * výpis každého zvoleného záznamu
     */
    $pdf->Ln();
    $pdf->SetY($y);
    $pdf->SetX(15);
    $pdf->cell(25, 14, $stat[$i], 1);
    $pdf->cell(50, 14, $stat[$i+1], 1);
    if ($c != "") {
        $pdf->cell(35, 14, $stat[$i+2], 1);
    }
    if ($d != "") {
        $pdf->cell(35, 14, $stat[$i+3], 1);
    }
    if ($e != "") {
        $pdf->cell(35, 14, $stat[$i+4], 1);
    }
    $y += 14;
    $i+=4;
}
/**
 * volanie metódy na zobrazenie výstupu
 */
$pdf->Output('hospitals_stat.pdf', 'I');
?>