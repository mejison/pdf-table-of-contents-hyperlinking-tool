<?php


namespace App\Listeners\PDF;

use App\Events\PDF\Uploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\PdfToText\Pdf;
use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;

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
        ini_set('memory_limit', '-1');

        $path = $event->path;
        $outputFilename = $event->outputfilename;
        $targetPages = $this->searchPageWithTableOfContent($path);

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($path); // get total page
        
        for($page = 1; $page <= $pageCount; $page ++) {
            if (in_array($page - 1, array_keys($targetPages))) {
                $this->TableOfContentpage($pdf, $page, $path, $targetPages[$page - 1]);
            } else {
                $this->importPage($pdf, $page, $path);
            }
        }

        // $pdf->Output();  
        $pdf->Output($outputFilename, 'F');
    }

    private function TableOfContentpage($pdf, $indexPage, $filePath, $items) {
        $p = $pdf->addTOCPage();    

        $pdf->SetFont('dejavusans', 'B', 16);
        $pdf->MultiCell(0, 0, 'Table Of Content', 0, 'C', 0, 1, '', '', true, 0);
        $pdf->Ln();

        $pdf->SetFont('dejavusans', '', 12);
    
        collect($items)->each(function($page, $title) use (&$pdf, &$p, &$index) {
            
            $index_link = $pdf->AddLink();
            $pdf->SetLink($index_link, 0, '*' . $page);
            
            $titleLength = strlen($title);
            $pdf->Cell(0, 8, $title . str_repeat('.', 100 - $titleLength) . $page, 0, 1, 'L', false, $index_link);
        });
        $pdf->endTOCPage();

    }

    private function importPage($pdf, $indexPage, $filePath) {
        $pdf->AddPage();
    
        $pdf->setSourceFile($filePath);
        $tplId = $pdf->importPage($indexPage);
        $pageSize = $pdf->getImportedPageSize($tplId);
    
        $pdf->useTemplate($tplId, 0, 0, $pageSize['width'], $pageSize['height'], $pageSize['orientation']);
    }

    private function searchPageWithTableOfContent($file) {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($file);
        $pages = $pdf->getPages();
        $target = [];

        for($page = 0; $page < count($pages); $page ++) {
            $text = $pages[$page]->getText();
            $text = str_replace(['\\',  "\r"], '', $text);
            preg_match_all("/(.*)\s*[\.\/]{10,}\s*(\d{1,3})/im", $text, $results);
            if(count($results)) {
                array_shift($results);
                $results[0] = array_map(function($item) {
                    return trim(str_replace(['\\',  "\r", "\n", ".", "\t"], '', $item));
                }, $results[0]);
                $results = array_combine($results[0], $results[1]);
                $target[$page] = $results;
            }
        }
        return array_filter($target);
    }
}
