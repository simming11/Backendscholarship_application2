<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use PDF;
use TCPDF;

class PDFController extends Controller
{
    public function generatePDF()
{
    // Create a new TCPDF instance
    $pdf = new TCPDF();

    // Set document information
    $pdf->SetCreator('Laravel');
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Laravel TCPDF Example');

    // Add a page
    $pdf->AddPage();

    // Set the font to THSarabunNew
    $pdf->SetFont('thsarabunnew', '', 14); 

    // Write content
    $pdf->Write(0, 'เทสโดยใช้ TCPDF');

    // Output the generated PDF
    $pdf->Output('example.pdf', 'I');
}
}
