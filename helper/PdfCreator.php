<?php
require_once 'third-party/dompdf-example/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
class PdfCreator
{
    public function create($html)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("document.pdf" , ['Attachment' => 0]);
    }
}