<?php
require('../fpdf/fpdf.php');
require "DB_storage.php";

$storage = new DB_storage();
/**
 * trieda pre export do PDF tabuľky krajov
 */
class PDFKraje extends FPDF
{
    /**
     * hlavička súboru
     */
    function Header()
    {
        $this->SetFont('Helvetica', 'B', 15);
        $this->SetXY(12, 10);
        $this->Cell(0, 10, 'Kraje', 1, 0, 'C');
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
$g = " ";
if ($_GET['a'] == "a") {
    $stat = $storage->getAllKraje();
} else {
    $chcem = "and kraj = '" . $_GET['h'] . "' ";
    $stat = $storage->getKrajeStat($_GET['a'], $_GET['b'], $chcem);
    $c = $_GET['c'];
    $d = $_GET['d'];
    $e = $_GET['e'];
    $f = $_GET['f'];
    $g = $_GET['g'];
}

$pdf = new PDFKraje();
$pdf->SetTitle('Kraje');

$y = 46;
for ($i = 0; $i < sizeof($stat); $i++) {
    if ($c == "" || $d == "" || $e == "" || $f == "") {
        $x = 15;
        $xx = 15;
    } else {
        $x = 7;
        $xx = 7;
    }
    $kraj = 50;
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
        $pdf->MultiCell(23, 19.5, 'Datum', 1, 'L', 1);
        $x += 23;
        $pdf->SetY(26);
        $pdf->SetX($x);
        $pdf->MultiCell($kraj, 19.5, 'Kraj', 1, 'L', 1);
        $x += $kraj;

        if ($c != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->MultiCell(25, 4.9, 'Pocet vykonanych AG testov', 1, 'L', 1);
            $x += 25;

        }
        if ($d != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->multiCell(25, 6.5, 'Pocet pozitivnych AG', 1, 'L', 1);
            $x += 25;
        }
        if ($e != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->multiCell(25, 6.5, 'Pocet pozitivnych z PCR', 1, 'L', 1);
            $x += 25;
        }
        if ($f != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->multiCell(25, 6.5, 'Pocet novych pripadov', 1, 'L', 1);
            $x += 25;
        }
        if ($g != "") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->multiCell(25, 4.9, 'Pocet celkovo pozitivnych', 1, 'L', 1);

        }
        $y = 46;
    }
    /**
     * výpis každého zvoleného záznamu
     */
    $pdf->Ln();
    $pdf->SetY($y);
    $pdf->SetX($xx);
    $pdf->Cell(23, 14, $stat[$i]->getDatum(), 1);
    $pdf->cell($kraj, 14, $stat[$i]->getIdKraj(), 1);
    if ($c != "") {
        $pdf->Cell(25, 14, $stat[$i]->getAgVyk(), 1);
    }
    if ($d != "") {
        $pdf->Cell(25, 14, $stat[$i]->getAgPoz(), 1);
    }
    if ($e != "") {
        $pdf->Cell(25, 14, $stat[$i]->getPcrPoz(), 1);
    }
    if ($f != "") {
        $pdf->Cell(25, 14, $stat[$i]->getNewcases(), 1);
    }
    if ($g != "") {
        $pdf->Cell(25, 14, $stat[$i]->getPozCelk(), 1);
    }
    $y += 14;
}
/**
 * volanie metódy na zobrazenie výstupu
 */
$pdf->Output('kraje_stat.pdf', 'I');
?>