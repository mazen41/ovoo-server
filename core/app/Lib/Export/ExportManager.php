<?php

namespace App\Lib\Export;

use Exception;
use Illuminate\Support\Facades\Schema;

class ExportManager
{
    /**
     * @var string $modelName
     * The name of the model being used for the export. 
     * It is capitalized to match the expected model class name.
     */
    public $modelName;

    /**
     * @var string $exportType
     * The type of export file format (e.g., csv, pdf, excel, print).
     */
    public $exportType;

    /**
     * @var string $modelNameNameSpace
     * The fully qualified namespace of the model being exported.
     * This is dynamically generated using the model name.
     */
    public $modelNameNameSpace;

    /**
     * @var object $modelInstance
     * An instance of the model used for export. 
     * This is created dynamically when needed.
     */
    public $modelInstance;

    /**
     * @var \Illuminate\Database\Query\Builder $baseQuery
     * The base query used to retrieve data for export.
     * This query is passed in and may be customized as needed.
     */
    public $baseQuery;

    /**
     * @var string $printPageSize
     * The print page size 
     */
    public $printPageSize;

    /**
     * Constructor for the ExportManager class.
     *
     * @param \Illuminate\Database\Query\Builder $baseQuery The base query to retrieve export data.
     * @param string $modelName The name of the model being exported.
     * @param string $exportType The type of export file (csv, pdf, excel, print).
     * @param string $printPageSize the page size for the print..
     */
    public function __construct($baseQuery, string $modelName, string $exportType, $printPageSize = "A4 portrait")
    {
        $this->modelName          = ucfirst($modelName);
        $this->modelNameNameSpace = "App\\Models\\" . ucfirst($modelName);
        $this->exportType    = $exportType;
        $this->baseQuery     = $baseQuery;
        $this->printPageSize = $printPageSize;
    }

    /**
     * Initiates the export process.
     *
     * @throws \Exception if the model does not exist or the export type is not supported.
     * @return mixed Returns the exported file based on the specified export type.
     */
    public function export()
    {
        if (!$this->checkModelExists()) {
            $this->setException("The model App\\Models\\$this->modelName does not exist");
        }
        if (!$this->checkExportType()) {
            $this->setException("The export file type $this->exportType is not supported");
        }

        $this->createModelInstance();

        $modelColumns = $this->getModelColumnNames();
        $columns      = $this->getColumns($modelColumns);
        $data         = $this->getData();

        if (!$data->count()) {
            $this->setException("No data available for export.");
        }

        $fileHeaders = $columns;
        $fileBody    = $this->makeData($data, $modelColumns);
        $methodName  = $this->exportType;

        return (new ExportFileType($fileHeaders, $fileBody, $this->modelName, $this->printPageSize))->$methodName();
    }

    /**
     * Returns the list of supported export file types.
     *
     * @return array List of supported file types such as csv, pdf, excel, and print.
     */
    private function getSupportedExportTypes(): array
    {
        return ['csv', 'pdf', 'excel', 'print'];
    }

    /**
     * Retrieves the column names of the model's table.
     *
     * @return array List of column names from the model's table.
     */
    private function getModelColumnNames(): array
    {
        return Schema::getColumnListing(($this->modelInstance)->getTable());
    }

    /**
     * Checks if the model class exists within the application.
     *
     * @return bool True if the model exists, false otherwise.
     */
    private function checkModelExists(): bool
    {
        return class_exists("App\\Models\\$this->modelName");
    }

    /**
     * Checks if the provided export type is valid and supported.
     *
     * @return bool True if the export type is supported, false otherwise.
     */
    private function checkExportType(): bool
    {
        return in_array($this->exportType, $this->getSupportedExportTypes());
    }

    /**
     * Creates an instance of the model class for the export.
     *
     * @return void
     */
    private function createModelInstance(): void
    {
        $this->modelInstance = new $this->modelNameNameSpace;
    }

    /**
     * Retrieves exportable  columns based on the model's structure.
     * If the model defines an `exportColumns` method, those are used, otherwise all columns are exported.
     *
     * @param array $modelColumns The list of columns from the model's table.
     * @return array Array containing 'exportable' and 'selectable' columns.
     * @throws \Exception if any column definition is invalid.
     */
    private function getColumns($modelColumns): array
    {
        if (method_exists($this->modelInstance, 'exportColumns')) {
            $makeExportableColumns = [];

            foreach ($this->modelInstance->exportColumns() as $k => $definedColumns) {
                if (is_array($definedColumns)) {
                    $columName = $k;

                    if (!array_key_exists('callback', $definedColumns) && !in_array($columName, $modelColumns)) {
                        $this->setException("A callback is required when $k value is an array");
                    }

                    if (array_key_exists('name', $definedColumns)) {
                        $makeExportableColumns[] = $definedColumns['name'];
                    } else {
                        $makeExportableColumns[] = $columName;
                    }
                } else {
                    if (is_string($k)) {
                        $columName = $k;
                    } else {
                        if (!in_array($definedColumns, $modelColumns)) {
                            $this->setException("$definedColumns must be required a value");
                        }

                        $columName = $definedColumns;
                    }
                    $makeExportableColumns[] = $columName;
                }
            }
            return $makeExportableColumns;
        } else {
            return $modelColumns;
        }
    }

    /**
     * Retrieves the data to be exported based on the provided columns.
     *
     * @return \Illuminate\Support\Collection The retrieved data collection.
     */
    private function getData()
    {
        return $this->baseQuery->get();
    }

    /**
     * Formats the data for export by applying any custom export logic defined in the model.
     *
     * @param \Illuminate\Support\Collection $data The data retrieved for export.
     * @return array The formatted data ready for export.
     */
    public function makeData($data): array
    {
        $data = $data->makeVisible($this->modelInstance->getHidden());

        if (method_exists($this->modelInstance, "exportColumns")) {
            $newData = [];
            $exportColumns = $this->modelInstance->exportColumns();

            foreach ($data as $item) {
                $newItem = [];
                foreach ($exportColumns as $k => $exportColumn) {
                    if (is_array($exportColumn)) {
                        $columName = $k;
                        if (array_key_exists("callback", $exportColumn)) {
                            $value = $exportColumn['callback']($item);
                        } else {
                            $value = $item->$columName;
                        }
                    } else {
                        if (is_string($k)) {
                            $columName = $k;
                            $value = $exportColumn;
                        } else {
                            $columName = $exportColumn;
                            $value = $item->$columName;
                        }
                    }
                    $newItem[] = $value;
                }
                $newData[] = $newItem;
            }
            return $newData;
        } else {
            return collect($data)
                ->map(function ($value) {
                    return collect($value)->values()->toArray();
                })->toArray();
        }
    }

    /**
     * Throws an exception with a custom error message.
     *
     * @param string $message The error message.
     * @throws \Exception
     */
    private function setException(string $message): void
    {
        throw new Exception($message);
    }
}
