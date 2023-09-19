<?php

namespace App\Listeners\PDF;

use App\Events\PDF\Uploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\PdfToText\Pdf;
use TCPDF;

class ExtractingTexFromPDF
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\PDF\Uploaded  $event
     * @return void
     */
    public function handle(Uploaded $event)
    {
        $path = $event->path;
        $outputFilename = $event->outputfilename;

        $text = (new Pdf('/usr/local/bin/pdftotext'))
            ->setPdf($path)
            ->text();
        
        preg_match_all("/([\d\w]*)\s*\.{10,}\s*(\d*)/i", $text, $results);

        if(count($results)) {
            array_shift($results);
            $results = array_combine($results[0], $results[1]);
            $this->createPDFfile($results, $outputFilename);
        }
    }

    private function createPDFfile(array $items, string $outputFilename) { // table of content
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 14, '', true);
        $pdf->AddPage();

        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        collect($items)->each(function($page, $title) use (&$pdf) {
            $index_link = $pdf->AddLink();
            $pdf->SetLink($index_link, 0, '*' . $page);
            $pdf->Cell(0, 10, $title . str_repeat('.', 90) . $page, 0, 1, 'R', false, $index_link);
        });

        $pdf->writeHTMLCell(0, 0, '', '', str_repeat('<br />', 1000), 0, 1, 0, true, '', true);
        $pdf->Output($outputFilename, 'F');
    }
}
