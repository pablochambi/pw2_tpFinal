<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

class PdfCreator{
    public function create($html)
    {
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml('hello world');

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream("document.pdf" , ['Attachment' => 0]);
    }
}
