<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PDF\AnalysisRequest;

class PDFController extends Controller
{
    const TEMP_DIR = 'app/public/temp/';
    const PUBLIC_DIR = 'storage/temp/';

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

        $filename = basename($url);
        if ( ! file_exists(storage_path(self::TEMP_DIR))) {
            mkdir(storage_path(self::TEMP_DIR));
        }
        
        file_put_contents(storage_path(self::TEMP_DIR . $filename), $content);

        $outputPDF = asset(self::PUBLIC_DIR . $filename);

        return redirect()
                ->back()
                ->withInput()
                ->with([
                    'success' => 'Successfuly analized!',
                    'file' => $outputPDF,
                ]);

    }
}
