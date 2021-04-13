<?php
require('../fpdf/fpdf.php');
require "DB_storage.php";

$storage = new DB_storage();
/**
 * trieda pre export do PDF tabuľky denného testovania
 */
class PDFDenne extends FPDF
{
    /**
     * hlavička súboru
     */
    function Header()
    {
        $this->SetFont('Helvetica','B',15);
        $this->SetXY(12, 10);
        $this->Cell(0,10,'Denne Testovanie',1,0,'C');

        $this->SetFont('Arial','B',12);
        $this->SetFillColor(232,232,232);
        $this->SetY(26);
        $this->SetX(15);
        $this->MultiCell(25,15,'Datum',1,'L',1);
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
$pdf = new PDFDenne();
$c=" ";
$d=" ";
$e=" ";
$f=" ";
$g=" ";
if( $_GET['a']=="a") {
    $stat = $storage->getAllDenne();
} else {
    $stat = $storage->getAllKazdodenneStat($_GET['a'],$_GET['b']);
    $c = $_GET['c'];
    $d = $_GET['d'];
    $e = $_GET['e'];
    $f = $_GET['f'];
    $g = $_GET['g'];
}
/**
 * vytvorenie prvej strany PDF súboru
 */
$pdf->AddPage();

$pdf->SetTitle('Denne Testovanie');
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$x=40;
/**
 * výpis každého zvoleného stĺpca tabuľky
 */
if($c!="") {
    $pdf->SetY(26);
    $pdf->SetX($x);
    $pdf->MultiCell(30, 5, 'Pocet PCR potvrdenych pripadov', 1,'L',1);
    $x+=30;
}
if($d!=""){
    $pdf->SetY(26);
    $pdf->setX($x);
    $pdf->MultiCell(30, 5, 'Pocet vykonanych PCR testov', 1,  'L', 1);
    $x+=30;
}
if($e!="") {
    $pdf->setY(26);
    $pdf->setX($x);
    $pdf->MultiCell(30, 5, 'Pocet pozitivnych z PCR testov', 1, 'L', 1);
    $x+=30;
}
if($f!="") {
    $pdf->setY(26);
    $pdf->setX($x);

    $pdf->MultiCell(30, 5, 'Pocet vykonanych AG testov', 1,  'L', 1);
    $x+=30;
}
if($g!="") {
    $pdf->setY(26);
    $pdf->setX($x);
    $pdf->MultiCell(30, 5, 'Pocet pozitivnych z AG testov', 1,  'L', 1);
    $x+=30;
}
$y=42;
/**
 * výpis každého zvoleného záznamu
 */
for($i=0;$i<sizeof($stat);$i++) {
    if($i %35==0 && $i>0) {
        $pdf->AddPage();

        $x=40;
        if($c!="") {
            $pdf->SetY(26);
            $pdf->SetX($x);
            $pdf->MultiCell(30, 5, 'Pocet PCR potvrdenych pripadov', 1,'L',1);
            $x+=30;
        }
        if($d!=""){
            $pdf->SetY(26);
            $pdf->setX($x);
            $pdf->MultiCell(30, 5, 'Pocet vykonanych PCR testov', 1,  'L', 1);
            $x+=30;
        }
        if($e!="") {
            $pdf->setY(26);
            $pdf->setX($x);
            $pdf->MultiCell(30, 5, 'Pocet pozitivnych z PCR testov', 1, 'L', 1);
            $x+=30;
        }
        if($f!="") {
            $pdf->setY(26);
            $pdf->setX($x);

            $pdf->MultiCell(30, 5, 'Pocet vykonanych AG testov', 1,  'L', 1);
            $x+=30;
        }
        if($g!="") {
            $pdf->setY(26);
            $pdf->setX($x);
            $pdf->MultiCell(30, 5, 'Pocet pozitivnych z AG testov', 1,  'L', 1);
            $x+=30;
        }
        $y=42;
    }
    $pdf->Ln();
    $pdf->SetY($y);
    $pdf->SetX(15);
    $pdf->Cell(25, 6, $stat[$i]->getDatum(), 1);

    if($c!="") {
        $pdf->Cell(30, 6, $stat[$i]->getPcrPotv(), 1);
    }
    if($d!="") {
        $pdf->Cell(30, 6, $stat[$i]->getPcrPoc(), 1);
    }
    if($e!="") {
        $pdf->Cell(30, 6, $stat[$i]->getPcrPoz(), 1);
    }
    if($f!="") {
        $pdf->Cell(30, 6, $stat[$i]->getAgPoc(), 1);
    }
    if($g!="") {
        $pdf->Cell(30, 6, $stat[$i]->getAgPoz(), 1);
    }
    $y += 6;
}
/**
 * volanie metódy na zobrazenie výstupu
 */
$pdf->Output('example2.pdf','I');
?>