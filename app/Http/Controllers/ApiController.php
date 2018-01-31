<?php

namespace App\Http\Controllers;

use App\Support\Helpers;
use App\Support\ConnectDatabase;
use App\Support\Firebird;
use App\Support\ResultBuilder;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->connDB = new ConnectDatabase();
        $this->resultBuilder = new ResultBuilder();
    }

    public function getManyData($dbName, $tableName)
    {
        $database = \DB::table('dbs')->where('label', $dbName)->first();

        $this->connDB->setDatabase($database->driver, $database);
        $query = \DB::connection($database->driver)->table($tableName);

        $result = $this->resultBuilder
            ->buildMany(request(), $query, $tableName)
            ->get();

        $arr = [];
        foreach ($result as $r) {
            array_push($arr, get_object_vars($r));
        }

        $this->connDB->unsetDatabase($database->driver);

        return response()->json([
            'data' => Helpers::array_utf8_encode($arr),
        ]);
    }

    public function getSingleData($dbName, $tableName, $id)
    {
        $database = \DB::table('dbs')->where('label', $dbName)->first();

        $this->connDB->setDatabase($database->driver, $database);
        $query = \DB::connection($database->driver)->table($tableName);

        $result = $this->resultBuilder
            ->buildSingle(request(), $query, $tableName, $id);

        $data = (array) $result->first();
        $this->connDB->unsetDatabase($database->driver);

        return response()->json([
            'data' => Helpers::array_utf8_encode($data),
        ]);
    }
}
