<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PDF\AnalysisRequest;
use App\Events\PDF\Uploaded;

class PDFController extends Controller
{
    const TEMP_DIR = 'app/public/temp/';
    const PUBLIC_DIR = 'storage/temp/';
    const ALLOWED_FILES = ['application/pdf'];

    /**
     * Analysis the PDF file.
     */
    public function analysis(AnalysisRequest $request) 
    {   
        $url = $request->input('file');
        $content = null;

        try {
            $content = file_get_contents($url);
        } catch (\ErrorException $e) {
            $errors = $this->addErrorMessage('file_get_contents', $e->getMessage());
            return redirect()
                    ->back()
                    ->with('errors', $errors);
        }

        $filename = time() . '-' . basename($url);
        if ( ! file_exists(storage_path(self::TEMP_DIR))) {
            mkdir(storage_path(self::TEMP_DIR));
        }
        $tempFilename = storage_path(self::TEMP_DIR . $filename);
        file_put_contents($tempFilename, $content);

        $mimeType = mime_content_type($tempFilename);
        if( ! in_array($mimeType, self::ALLOWED_FILES)) {
            $errors = $this->addErrorMessage('mime_content_type', "The file must be of PDF type");
                return redirect()
                        ->back()
                        ->with('errors', $errors);
        }

        $outputPDF = storage_path('app/public/output/' . time() . '.pdf');
        Uploaded::dispatch($tempFilename, $outputPDF);
                
        return redirect()
                ->back()
                ->withInput()
                ->with([
                    'success' => 'Successfuly analized!',
                    'file' => $outputPDF,
                ]);

    }
}
