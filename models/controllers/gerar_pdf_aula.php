<?php
require('../libs/fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process title
    $titulo = html_entity_decode($_POST['titulo'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $titulo_pdf = mb_convert_encoding($titulo, 'ISO-8859-1', 'UTF-8');

    // Process content
    $conteudo = html_entity_decode($_POST['conteudo'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $conteudo = strip_tags($conteudo);
    $conteudo_pdf = mb_convert_encoding($conteudo, 'ISO-8859-1', 'UTF-8');

    class PDF extends FPDF
    {
        // Page header
        function Header()
        {
            // Logo
            // $this->Image('logo.png',10,6,30);
            // Arial bold 15
            $this->SetFont('Arial','B',15);
            // Move to the right
            $this->Cell(80);
            // Title
            // $this->Cell(30,10,'Title',1,0,'C');
            // Line break
            $this->Ln(20);
        }

        // Page footer
        function Footer()
        {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Page number
            $this->Cell(0,10,mb_convert_encoding('Página ', 'ISO-8859-1', 'UTF-8').$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    // Instanciation of inherited class
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,$titulo_pdf,0,1,'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial','',12);
    $pdf->MultiCell(0,10,$conteudo_pdf);
    $pdf->Output('D', str_replace(" ", "_", $titulo) . '.pdf');
}
?>