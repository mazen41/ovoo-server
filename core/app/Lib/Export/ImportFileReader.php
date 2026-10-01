<?php

namespace App\Lib\Export;

use App\Models\ContactList;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportFileReader
{
    /*
    |--------------------------------------------------------------------------
    | Import File Reader
    |--------------------------------------------------------------------------
    |
    | This class basically generated for read data or insert data from user import file
    | several kind of files read here
    | like csv,xlsx,csv
    |
    */

    public $dataInsertMode = true;

    /**
     * colum name of upload file ,like name,email,mobile,etc
     * colum name must be same of target table colum name
     *
     * @var array
     */
    public $columns = [];

    /**
     * check the value exits on DB: table
     *
     * @var array
     */

    public $uniqueColumns = [];

    /**
     * on upload model class
     *
     * @var string
     */
    public $modelName;

    /**
     * upload file
     *
     * @var object
     */
    public $file;

    /**
     * supported input file extensions
     *
     * @var array
     */
    public $fileSupportedExtension = ['csv', 'xlsx'];


    /**
     * Here store all data from read text,csv,excel file
     *
     * @var array
     */

    public $allData = [];

    /**
     * Here could be pre-defined reference data like contactList,contactTags
     *
     * @var array
     */

    public $references = [];

    /**
     * ALL Unique data store here
     */
    public $allUniqueData = [];

    public $notify;


    public function __construct($file, $modelName = null)
    {
        $this->file      = $file;
        $this->modelName = $modelName;
    }

    public function readFile()
    {
        $fileExtension = $this->file->getClientOriginalExtension();

        if (!in_array($fileExtension, $this->fileSupportedExtension)) {
            return $this->exceptionSet("File type not supported");
        }
        $spreadsheet = IOFactory::load($this->file);

        $data = array_filter($spreadsheet->getActiveSheet()->toArray(), function ($row) {
            return array_filter($row, fn($value) => $value !== null && trim($value) !== '') !== [];
        });

        if (count($data) <= 0) {
            return   $this->exceptionSet("File can not be empty");
        }

        $this->validateFileHeader(array_filter(@$data[0]));

        unset($data[0]);
        foreach ($data as  $item) {
            $item = array_map('trim', $item);
            $this->dataReadFromFile($item);
        };

        return $this->saveData();
    }

    public function validateFileHeader($fileHeader)
    {
        if (!is_array($fileHeader) || count($fileHeader) != count($this->columns)) {
            $this->exceptionSet("Invalid file format");
        }
        foreach ($fileHeader as $k => $header) {
            if (trim(strtolower($header)) != strtolower(@$this->columns[$k])) {
                $this->exceptionSet("Invalid file format");
            }
        }
    }

    public function dataReadFromFile($data)
    {
        if (gettype($data) != 'array') {
            return $this->exceptionSet('Invalid data formate provided inside upload file.');
        }

        $data = array_map(fn($value) => trim((string) $value), $data);

        $data = array_slice($data, 0, count($this->columns));

        if (count($data) != count($this->columns)) {
            return  $this->exceptionSet('Invalid data formate provided inside upload file.');
        }

        if ($this->dataInsertMode && (!$this->uniqueColumCheck($data))) {
            $this->allUniqueData[] = array_combine($this->columns, $data);
        }

        $this->allData[] = $data;
    }

    function uniqueColumCheck($data)
    {
        $user = getParentUser();
        $dialCodeValue = $data[2] ?? null;
        $mobileValue   = $data[3] ?? null;

        if ($mobileValue) {
            return $this->modelName::where('user_id', $user->id)->where('mobile_code', $dialCodeValue)->where('mobile', $mobileValue)->exists();
        }

        return false;
    }

    public function saveData()
    {
        $user = getParentUser();

        if (!featureAccessLimitCheck($user->contact_limit, count($this->allUniqueData))) {
            $this->exceptionSet('You have reached the maximum number of contact limit');
        }

        if (count($this->allUniqueData) > 0 && $this->dataInsertMode) {
            try {
                $this->allUniqueData = array_map(function ($data) use ($user) {
                    $data['user_id']    = $user->id;
                    $data['created_at'] = Carbon::now();
                    $data['updated_at'] = Carbon::now();
                    return $data;
                }, $this->allUniqueData);

                $this->modelName::insert($this->allUniqueData);

                $insertedIds = $this->modelName::where('user_id', $user->id)
                    ->latest('id')
                    ->take(count($this->allUniqueData))
                    ->pluck('id')
                    ->toArray();

                if (!empty($insertedIds) && !empty($this->references['contact_list_id'])) {
                    $contactListId = $this->references['contact_list_id'];

                    $pivotData = [];
                    foreach ($insertedIds as $contactId) {
                        $pivotData[$contactId] = ['created_at' => Carbon::now()];
                    }

                    $contactList = ContactList::find($contactListId);

                    if ($contactList) {
                        $contactList->contact()->syncWithoutDetaching($pivotData);
                    }
                }

                decrementFeature($user, 'contact_limit', count($this->allUniqueData));

            } catch (Exception $e) {
                $this->exceptionSet('This file can\'t be uploaded. It may contains duplicate data.');
            }
        }

        $this->notify = count($this->allUniqueData) . " data added successfully total " . count($this->allData) . ' data';
    }


    public function exceptionSet($exception)
    {
        throw new Exception($exception);
    }

    public function getReadData()
    {
        return $this->allData;
    }

    public function notifyMessage()
    {
        return $this->notify;
    }
}
