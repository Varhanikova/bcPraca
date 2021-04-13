<?php

require('../fpdf/fpdf.php');
require_once "DB_storage.php";
$storage = new DB_storage();
/**
 * trieda pre export do PDF tabuľky úmrtí
 */
class PDF extends FPDF
{
    /**
     * hlavička súboru
     */
    function Header()
    {
        $this->SetFont('Helvetica','B',15);
        $this->SetXY(12, 10);
        $this->Cell(0,10,'Umrtia',1,0,'C');

        $this->SetFont('Arial','B',12);
        $this->SetFillColor(232,232,232);
        $this->SetY(26);
        $this->SetX(15);
        $this->Cell(30,6,'Datum',1,0,'L',1);
    }
    /**
     * päta súboru
     */
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Helvetica','I',10);
        $this->Cell(0,10,$this->PageNo(),0,0,'C');
    }
}
/**
 * získanie premenných z $_GET
 */
$c=" ";
$d=" ";
$e=" ";
if( $_GET['a']=="a") {
    $stat = $storage->getDeathsAll();
} else {
    $stat = $storage->getDeathsAtDate($_GET['a'],$_GET['b']);
    $c=$_GET['c'];
    $d=$_GET['d'];
    $e=$_GET['e'];
}
/**
 * vytvorenie prvej strany PDF súboru
 */
$pdf=new PDF();
$pdf->AddPage();

$pdf->SetTitle('Umrtia');
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(232,232,232);
$pdf->SetY(26);
$pdf->SetX(45);
/**
 * výpis každého zvoleného stĺpca tabuľky
 */
if($c!="") {
    $pdf->Cell(45, 6, 'pocet umrti na kovid', 1,0,'L',1);
}
if($d!="") {
    $pdf->Cell(45, 6, 'pocet umrti s kovid', 1, 0, 'L', 1);
}
if($e!="") {
    $pdf->Cell(45, 6, 'celkovy pocet umrti', 1, 0, 'L', 1);
}
$y=32;
/**
 * výpis každého zvoleného záznamu
 */
for($i=0;$i<sizeof($stat);$i++) {
    if($i %40==0 && $i>0) {
        $pdf->AddPage();
        if($c!="") {
            $pdf->Cell(45, 6, 'pocet umrti na kovid', 1,0,'L',1);
        }
        if($d!="") {
            $pdf->Cell(45, 6, 'pocet umrti s kovid', 1, 0, 'L', 1);
        }
        if($e!="") {
            $pdf->Cell(45, 6, 'celkovy pocet umrti', 1, 0, 'L', 1);
        }
        $y=32;
    }
    $pdf->Ln();
    $pdf->SetY($y);
    $pdf->SetX(15);
    $pdf->Cell(30, 6, $stat[$i]->getDatum(), 1);

    if($c!="") {
        $pdf->Cell(45, 6, $stat[$i]->getPocNaKov(), 1);
    }
    if($d!="") {
        $pdf->Cell(45, 6, $stat[$i]->getPocSKov(), 1);
    }
    if($e!="") {
        $pdf->Cell(45, 6, $stat[$i]->getCelk(), 1);
    }
    $y += 6;
}
/**
 * volanie metódy na zobrazenie výstupu
 */
$pdf->Output('umrtia.pdf','I');
?>