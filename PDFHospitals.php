<?php
require('../fpdf/fpdf.php');
require "DB_storage.php";

$storage = new DB_storage();

class PDFHospitals extends FPDF
{
    function Header()
    {
        $this->SetFont('Helvetica','B',15);
        $this->SetXY(12, 10);
        $this->Cell(0,10,'Nemocnice',1,0,'C');

        $this->SetFont('Arial','B',12);
        $this->SetFillColor(232,232,232);
        $this->SetY(26);
        $this->SetX(5);
        $this->MultiCell(25,19.5,'Datum',1,'L',1);
        $this->SetY(26);
        $this->SetX(30);
        $this->MultiCell(85, 19.5, 'Nazov nemocnice', 1, 'L', 1);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Helvetica','I',10);
        $this->Cell(0,10,$this->PageNo(),0,0,'C');
    }
}


$c=" ";
$d=" ";
$e=" ";
$f=" ";
if( $_GET['a']=="a") {
    $stat = $storage->getAllHospitals();
} else {
    $chcem = "and okres = '" . $_GET['f']. "' ";
    $stat = $storage->getHospitalStat($_GET['a'],$_GET['b'],$chcem);
    $c=$_GET['c'];
    $d=$_GET['d'];
    $e=$_GET['e'];
    $f= $_GET['f'];
}

$pdf=new PDFHospitals();
$pdf->AddPage();

$pdf->SetTitle('Hospitals');
$pdf->SetFillColor(232,232,232);
//$pdf->SetX(45);
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(232,232,232);
$pdf->SetY(26);
$pdf->SetX(35);


if($c!="") {
    $pdf->SetY(26);
    $pdf->SetX(115);
    $pdf->MultiCell(30, 6.5, 'Pocet obsadenych lozok', 1,  'L', 1);

}
if($d!="") {
    $pdf->SetY(26);
    $pdf->SetX(145);
    $pdf->multiCell(30, 6.5, 'Pocet osob na plucnej ventilacii', 1,  'L', 1);
}
//$pdf->SetX(135);
if($e!="") {
    $pdf->SetY(26);
    $pdf->SetX(175);
    $pdf->multiCell(30, 4.9, 'Celkovy pocet hospitalizovanych', 1,  'L', 1);
}


//Bold Font for Field Name
$y=46;
for($i=0;$i<sizeof($stat);$i++) {
    if($i %16==0 && $i>0) {
        $pdf->AddPage();

        if($c!="") {
            $pdf->SetY(26);
            $pdf->SetX(115);
            $pdf->MultiCell(30, 6.5, 'Pocet obsadenych lozok', 1,  'L', 1);

        }
        if($d!="") {
            $pdf->SetY(26);
            $pdf->SetX(145);
            $pdf->multiCell(30, 6.5, 'Pocet osob na plucnej ventilacii', 1,  'L', 1);
        }
        if($e!="") {
            $pdf->SetY(26);
            $pdf->SetX(175);
            $pdf->multiCell(30, 4.9, 'Celkovy pocet hospitalizovanych', 1,  'L', 1);
        }
        $y=46;
    }


    $pdf->Ln();
    $pdf->SetY($y);
    $pdf->SetX(5);
    $pdf->multiCell(25, 14, $stat[$i]->getDatum(), 1);

    $pdf->SetY($y);
    $pdf->SetX(30);
    if(($i%16)==15 && $i>0) {$pdf->multicell(85, 4.7, $stat[$i]->getNemocnica(),  1);} else {
        $pdf->multicell(85, 4.7, $stat[$i]->getNemocnica(), 'LRT');
    }

    if($c!="") {
        $pdf->SetY($y);
        $pdf->SetX(115);
        $pdf->multiCell(30, 14, $stat[$i]->getObsadeneLozka(), 1);
    }
    if($d!="") {
        $pdf->SetY($y);
        $pdf->SetX(145);
        $pdf->multiCell(30, 14,  $stat[$i]->getPlucVent(), 1);
    }
    if($e!="") {
        $pdf->SetY($y);
        $pdf->SetX(175);
        $pdf->multiCell(30, 14, $stat[$i]->getHospitalizovani(), 1);
    }
    $y +=  14;
}


$pdf->Output('umrtia.pdf','I');
?>