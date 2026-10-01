<?php

namespace App\Lib\Export;

use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportFileType
{
    /**
     * @var array $fileHeaders Stores the headers for the exported file.
     */
    public $fileHeaders;

    /**
     * @var array $fileBody Stores the body content for the exported file.
     */
    public $fileBody;

    /**
     * @var string $modelName Stores name of the exported model name
     */
    public $modelName;

    /**
     * @var string $printPageSize
     * The print page size 
     */
    public $printPageSize;

    /**
     * Constructor to initialize the export file's headers and body content.
     * 
     * @param array $fileHeaders An array containing the headers for the exported file.
     * @param array $fileBody An array containing the body content for the exported file.
     * @param string $modelName The name of the model, which will be used to name the export file.
     * @param string $printPageSize the page size for the print..
     * 
     */
    public function __construct(array $fileHeaders, array $fileBody, $modelName, $printPageSize)
    {
        $this->fileHeaders = $fileHeaders;
        $this->fileBody    = $fileBody;
        $this->modelName   = strtolower($modelName);
        $this->printPageSize   = $printPageSize;
    }

    /**
     * Exports the data to a CSV file.
     * 
     * @return \Illuminate\Http\Response Returns a download response for the generated CSV file.
     * @throws Exception If there is an error opening the file.
     */
    public function csv()
    {
        $fileName = $this->fileName('csv');
        $file     = fopen($fileName, 'w');
        if ($file === false) {
            throw new Exception("Error opening the file");
        }
        fputcsv($file, $this->fileHeaders);
        foreach ($this->fileBody as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
        return response()->download($fileName)->deleteFileAfterSend(true);
    }

    /**
     * Exports the data to a PDF file.
     * 
     * @return void
     */
    public function pdf()
    {
        $data['headers']       = $this->fileHeaders;
        $data['body']          = $this->fileBody;
        $data['printPageSize'] = $this->printPageSize;
        $data['pageTitle']     = "Download: " . $this->modelName;
        $pdf                   = Pdf::loadView('admin.partials.pdf', compact('data'));
        $fileName              = $this->fileName('pdf');
        return $pdf->download($fileName);
    }
    /**
     * Exports the data to a PDF file.
     * 
     * @return void
     */
    public function print()
    {
        $data['headers']       = $this->fileHeaders;
        $data['body']          = $this->fileBody;
        $data['printPageSize'] = $this->printPageSize;
        $data['pageTitle']     = "Print: " . $this->modelName;
        $pdf                   = Pdf::loadView('admin.partials.pdf', compact('data'));
        return $pdf->stream();
    }

    /**
     * Exports the data to an Excel file.
     * 
     * @return void
     */
    public function excel()
    {
        $fileName = $this->fileName('xlsx');

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Set the headers
        $column = 'A';
        foreach ($this->fileHeaders as $header) {
            $sheet->setCellValue($column . '1', $header);
            $column++;
        }

        // Set the body data
        $rowNumber = 2;  // Start from the second row
        foreach ($this->fileBody as $row) {
            $column = 'A';
            foreach ($row as $cellValue) {
                $sheet->setCellValue($column . $rowNumber, $cellValue);
                $column++;
            }
            $rowNumber++;
        }

        // Save the Excel file
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }

    /**
     * Generates the file location for the export file.
     * 
     * @param string $fileType The type of file to be created (e.g., 'csv', 'pdf', 'excel').
     * @return string The path to the created file.
     */
    private function fileName($fileType)
    {
        return $this->modelName . ".$fileType";
    }
}
