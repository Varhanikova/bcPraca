<?php
require('../fpdf/fpdf.php');
require "DB_storage.php";
$storage = new DB_storage();
class PDF extends FPDF
{
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
        $this->SetX(45);
        $this->Cell(50,6,'pocet umrti na kovid',1,0,'L',1);
        $this->SetX(90);
        $this->Cell(45,6,'pocet umrti s kovid',1,0,'L',1);
        $this->SetX(135);
        $this->Cell(45,6,'celkovy pocet umrti',1,0,'L',1);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Helvetica','I',10);

        $this->Cell(0,10,$this->PageNo(),0,0,'C');
    }
}
$stat = $storage->getDeaths();
$pdf=new PDF();
$pdf->AddPage();

$pdf->SetTitle('Umrtia');
$pdf->SetFillColor(232,232,232);

$pdf->SetTitle('Umrtia');
$pdf->SetFillColor(232,232,232);
//Bold Font for Field Name
$y=32;
for($i=0;$i<sizeof($stat);$i++) {
    if($i %40==0 && $i>0) {
        $pdf->AddPage();
        $y=32;
    }
    $pdf->Ln();
    $pdf->SetY($y);
    $pdf->SetX(15);
    $pdf->Cell(30, 6, $stat[$i]->getDatum(), 1);
    $pdf->SetX(45);
    $pdf->Cell(45, 6, $stat[$i]->getPocNaKov(), 1);
    $pdf->SetX(90);
    $pdf->Cell(45, 6, $stat[$i]->getPocSKov(), 1);
    $pdf->SetX(135);
    $pdf->Cell(45, 6, $stat[$i]->getCelk(), 1);
    $y+=6;
}


$pdf->Output('example2.pdf','I');
?>