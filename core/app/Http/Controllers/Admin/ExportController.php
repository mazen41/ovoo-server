<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Lib\Export\ExportManager;
use Exception;

class ExportController extends Controller
{
    public function export($model, $type)
    {
        try {
            return (new ExportManager($model, $type))->export();
        } catch (Exception $ex) {
            $notify[] = ['error', $ex->getMessage()];
            return back()->withNotify($notify);
        }
    }
}
