<?php
require_once 'third-party/dompdf-example/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
class PdfCreator
{
    public function crear($html)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');//Vetical=portrait,Horizontal=landscape
        $dompdf->render();
        $dompdf->stream("document.pdf" , ['Attachment' => 0]);
    }
}