<?php

namespace App\Http\Controllers\API;

use App\Dbs;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\InsertDatabaseRequest;
use App\Http\Requests\API\UpdateDatabaseRequest;
use App\Repositories\DatabaseRepository;
use App\Support\ResultBuilder;
use ForceUTF8\Encoding;

class DatabasesController extends Controller
{
    private $dbRepository;

    public function __construct()
    {
        $this->resultBuilder = new ResultBuilder();
        $this->dbRepository = new DatabaseRepository(null, env('DB_CONNECTION'));
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
        $query = $dbRepository->getQuery($tableName, $dbName);

        $result = $this->resultBuilder
            ->buildMany(request(), $query, $tableName)
            ->get();

        $arr = [];
        foreach ($result as $r) {
            array_push($arr, get_object_vars($r));
        }

        $this->dbRepository->unsetDatabase();

        return response()->json([
            'data' => Encoding::toUTF8($arr),
        ]);
    }

    public function getSingleData($dbName, $tableName, $id)
    {
        $query = $dbRepository->getQuery($tableName, $dbName);

        $result = $this->resultBuilder
            ->buildSingle(request(), $query, $tableName, $id);

        $data = (array) $result->first();

        $this->dbRepository->unsetDatabase();

        return response()->json([
            'data' => Encoding::toUTF8($data),
        ]);
    }

    public function postData($dbName, $tableName)
    {
        $query = $dbRepository->getQuery($tableName, $dbName);

        $result = $this->resultBuilder
            ->buildCreate(request(), $query, $tableName);

        $id = $result['id'];
        $paramsToSave = $result['paramsToSave'];

        if ($query->insert($paramsToSave)) {
            if (count($id) !== 1) {
                $this->dbRepository->unsetDatabase();

                return response()->json([
                    'data' => [
                        'sucesss' => true,
                        'last_id' => $id,
                    ],
                ]);
            }

            $columnId = array_keys($id)[0];
            $query = $dbRepository->getQuery($tableName, $dbName);
            $lastInsert = $query
                ->where($columnId, $id[$columnId])
                ->first();

            $this->dbRepository->unsetDatabase();

            return response()->json([
                'data' => Encoding::toUTF8($lastInsert),
            ]);
        }

        $this->dbRepository->unsetDatabase();

        return new DatabaseException('insert');
    }

    public function updateData($dbName, $tableName, $id)
    {
        $query = $dbRepository->getQuery($tableName, $dbName);

        $result = $this->resultBuilder
            ->buildUpdate(request(), $query, $tableName, $id);

        $id = $result['id'];
        $paramsToSave = $result['paramsToSave'];

        $columnId = array_keys($id)[0];
        if ($query->where($columnId, $id[$columnId])->update($paramsToSave) > 0) {
            $lastUpdate = $query
                ->where($columnId, $id[$columnId])
                ->first();

            $this->dbRepository->unsetDatabase();

            return response()->json([
                'data' => Encoding::toUTF8($lastUpdate),
            ]);
        }

        return new DatabaseException('update');
    }

    public function updateFilteringData($dbName, $tableName)
    {
        $query = $dbRepository->getQuery($tableName, $dbName);

        $result = $this->resultBuilder
            ->buildFilteringUpdate(request(), $query, $tableName);

        $paramsToSave = $result['paramsToSave'];

        if (count($paramsToSave) < 1) {
            $this->dbRepository->unsetDatabase();

            return response()->json([
                'data' => [
                    'sucesss' => true,
                    'rows_updated' => 0,
                ],
            ]);
        }

        $query = $result['query'];

        $rowsUpdated = $query->update($paramsToSave);

        if ($rowsUpdated > 0) {
            $this->dbRepository->unsetDatabase();

            return response()->json([
                'data' => [
                    'sucesss' => true,
                    'rows_updated' => $rowsUpdated,
                ],
            ]);
        }

        $this->dbRepository->unsetDatabase();

        return response()->json([
            'data' => [
                'sucesss' => false,
                'rows_updated' => 0,
            ],
        ]);
    }

    public function deleteData($dbName, $tableName, $id)
    {
        $query = $dbRepository->getQuery($tableName, $dbName);

        $result = $this->resultBuilder
            ->buildDelete(request(), $query, $tableName, $id);

        $id = $result['id'];
        $query = $result['query'];

        $columnId = array_keys($id)[0];

        if ($query->where($columnId, $id[$columnId])->delete() > 0) {
            $this->dbRepository->unsetDatabase();

            return response()->json([
                'data' => [
                    'sucesss' => true,
                ],
            ]);
        }

        $this->dbRepository->unsetDatabase();

        return new DatabaseException('delete');
    }

    public function deleteFilteringData($dbName, $tableName)
    {
        $query = $dbRepository->getQuery($tableName, $dbName);

        $result = $this->resultBuilder
            ->buildFilteringDelete(request(), $query, $tableName);

        $query = $result['query'];

        $rowsDeleted = $query->delete();

        if ($rowsDeleted > 0) {
            $this->dbRepository->unsetDatabase();

            return response()->json([
                'data' => [
                    'sucesss' => true,
                    'rows_deleted' => $rowsDeleted,
                ],
            ]);
        }

        $this->dbRepository->unsetDatabase();

        return response()->json([
            'data' => [
                'sucesss' => false,
                'rows_deleted' => 0,
            ],
        ]);
    }

    public function getDatabaseInfo($label)
    {
        $db = Dbs::select([
                'label',
                'driver',
                'host',
                'port',
                'database',
                'charset',
            ])
            ->where('label', $label)
            ->first();

        return response()->json([
            'data' => $db,
        ]);
    }

    public function insertDatabase(InsertDatabaseRequest $request)
    {
        Dbs::create($request->only(
            'label',
            'driver',
            'host',
            'port',
            'username',
            'password',
            'database',
            'charset'
        ));

        $db = Dbs::select([
                'label',
                'driver',
                'host',
                'port',
                'database',
                'charset',
            ])
            ->where('label', $request->get('label'))
            ->first();

        return response()->json([
            'data' => $db,
        ], 202);
    }

    public function updateDatabase(UpdateDatabaseRequest $request, $label)
    {
        $db = Dbs::where('label', $label)->first();

        $db->update($request->only(
            'label',
            'driver',
            'host',
            'port',
            'username',
            'password',
            'database',
            'charset'
        ));

        return response()->json([
            'data' => $db,
        ], 200);
    }
}
