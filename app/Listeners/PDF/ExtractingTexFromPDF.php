<?php

namespace App\Listeners\PDF;

use App\Events\PDF\Uploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\PdfToText\Pdf;

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

        $text = (new Pdf('/usr/local/bin/pdftotext'))
            ->setPdf($path)
            ->text();
        
        // dd($text);
    }
}
