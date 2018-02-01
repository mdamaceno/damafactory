<?php

namespace App\Http\Controllers;

use App\Support\Helpers;
use App\Support\ConnectDatabase;
use App\Support\Firebird;
use App\Support\ResultBuilder;
use App\Exceptions\DatabaseException;
use ForceUTF8\Encoding;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->connDB = new ConnectDatabase();
        $this->resultBuilder = new ResultBuilder();
    }

    private function database($dbName)
    {
        $database = \DB::connection(env('DB_CONNECTION'))
            ->table('dbs')
            ->where('label', $dbName)
            ->first();

        return $database;
    }

    public function getManyData($dbName, $tableName)
    {
        $database = $this->database($dbName);
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
            'data' => Encoding::toUTF8($arr),
        ]);
    }

    public function getSingleData($dbName, $tableName, $id)
    {
        $database = $this->database($dbName);
        $this->connDB->setDatabase($database->driver, $database);
        $query = \DB::connection($database->driver)->table($tableName);

        $result = $this->resultBuilder
            ->buildSingle(request(), $query, $tableName, $id);

        $data = (array) $result->first();
        $this->connDB->unsetDatabase($database->driver);

        return response()->json([
            'data' => Encoding::toUTF8($data),
        ]);
    }

    public function postData($dbName, $tableName)
    {
        $database = $this->database($dbName);
        $this->connDB->setDatabase($database->driver, $database);
        $query = \DB::connection($database->driver)->table($tableName);

        $result = $this->resultBuilder
            ->buildCreate(request(), $query, $tableName);

        $id = $result['id'];
        $paramsToSave = $result['paramsToSave'];

        if ($query->insert($paramsToSave)) {
            if (count($id) > 1) {
                $this->connDB->unsetDatabase($database->driver);

                return response()->json([
                    'data' => [
                        'sucesss' => true,
                        'last_id' => $id,
                    ],
                ]);
            }

            $columnId = array_keys($id)[0];
            $query = \DB::connection($database->driver)->table($tableName);
            $lastInsert = $query
                ->where($columnId, $id[$columnId])
                ->first();

            $this->connDB->unsetDatabase($database->driver);

            return response()->json([
                'data' => Encoding::toUTF8($lastInsert),
            ]);
        }

        $this->connDB->unsetDatabase($database->driver);

        return new DatabaseException('insert');
    }
}
