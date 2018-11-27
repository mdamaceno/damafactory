<?php

namespace App\Http\Controllers\API;

use App\Dbs;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\GenericRequest;
use App\Http\Requests\API\DeleteDatabaseRequest;
use App\Http\Requests\API\GetDatabaseRequest;
use App\Http\Requests\API\InsertDatabaseRequest;
use App\Http\Requests\API\UpdateDatabaseRequest;
use App\Repositories\DatabaseRepository;
use App\Support\Helpers;
use App\Support\ResultBuilder;
use App\Support\ResponseBuilder;
use ForceUTF8\Encoding;

class DatabasesController extends Controller
{
    private $dbRepository;

    public function __construct()
    {
        $this->resultBuilder = new ResultBuilder();
        $this->dbRepository = new DatabaseRepository(null, env('DB_CONNECTION'));
        $this->response = new ResponseBuilder();
    }

    public function getManyData(GenericRequest $request, $dbName, $tableName)
    {
        $query = $this->dbRepository->getQuery($tableName, $dbName, request()->header('Database-Token'));

        $result = $this->resultBuilder
            ->buildMany(request(), $query, $tableName)
            ->get();

        $arr = [];
        foreach ($result as $r) {
            array_push($arr, get_object_vars($r));
        }

        $this->dbRepository->unsetDatabase($dbName);

        return $this->response
                    ->setData(Encoding::toUTF8($arr))
                    ->json();
    }

    public function getSingleData(GenericRequest $request, $dbName, $tableName, $id)
    {
        $query = $this->dbRepository->getQuery($tableName, $dbName, request()->header('Database-Token'));

        $result = $this->resultBuilder
            ->buildSingle(request(), $query, $tableName, $id);

        $data = (array) $result->first();

        $this->dbRepository->unsetDatabase($dbName);

        return $this->response
                    ->setData(Encoding::toUTF8($data))
                    ->json();
    }

    public function postData(GenericRequest $request, $dbName, $tableName)
    {
        $query = $this->dbRepository->getQuery($tableName, $dbName, request()->header('Database-Token'));

        $result = $this->resultBuilder
            ->buildCreate(request(), $query, $tableName);

        $id = $result['id'];
        $paramsToSave = $result['paramsToSave'];

        if ($query->insert($paramsToSave)) {
            if (count($id) !== 1) {
                $this->dbRepository->unsetDatabase($dbName);

                return $this->response
                            ->setData([
                                'success' => true,
                                'last_id' => $id,
                            ])
                            ->setStatusCode(201)
                            ->json();
            }

            $columnId = array_keys($id)[0];
            $query = $dbRepository->getQuery($tableName, $dbName, request()->header('Database-Token'));
            $lastInsert = $query
                ->where($columnId, $id[$columnId])
                ->first();

            $this->dbRepository->unsetDatabase($dbName);

            return $this->response
                        ->setData(Encoding::toUTF8($lastInsert))
                        ->setStatusCode(201)
                        ->json();
        }

        $this->dbRepository->unsetDatabase($dbName);

        throw new DatabaseException('insert');
    }

    public function updateData(GenericRequest $request, $dbName, $tableName, $id)
    {
        $query = $this->dbRepository->getQuery($tableName, $dbName, request()->header('Database-Token'));

        $result = $this->resultBuilder
            ->buildUpdate(request(), $query, $tableName, $id);

        $id = $result['id'];
        $paramsToSave = $result['paramsToSave'];

        $columnId = array_keys($id)[0];

        if ($query->where($columnId, $id[$columnId])->update($paramsToSave) > 0) {
            $lastUpdate = $query
                ->where($columnId, $id[$columnId])
                ->first();

            $this->dbRepository->unsetDatabase($dbName);

            return $this->response
                        ->setData(Encoding::toUTF8($lastUpdate))
                        ->setStatusCode(202)
                        ->json();
        }

        throw new DatabaseException('update');
    }

    public function updateFilteringData(GenericRequest $request, $dbName, $tableName)
    {
        $query = $this->dbRepository->getQuery($tableName, $dbName, request()->header('Database-Token'));

        $result = $this->resultBuilder
            ->buildFilteringUpdate(request(), $query, $tableName);

        $paramsToSave = $result['paramsToSave'];

        if (count($paramsToSave) < 1) {
            $this->dbRepository->unsetDatabase($dbName);

            return $this->response
                        ->setData([
                            'sucesss' => true,
                            'rows_updated' => 0,
                        ])
                        ->setStatusCode(202)
                        ->json();
        }

        $query = $result['query'];

        $rowsUpdated = $query->update($paramsToSave);

        if ($rowsUpdated > 0) {
            $this->dbRepository->unsetDatabase($dbName);

            return $this->response
                        ->setData([
                            'sucesss' => true,
                            'rows_updated' => $rowsUpdated,
                        ])
                        ->setStatusCode(202)
                        ->json();
        }

        $this->dbRepository->unsetDatabase($dbName);

        return $this->response
                    ->setData([
                        'sucesss' => false,
                        'rows_updated' => 0,
                    ])
                    ->json();
    }

    public function deleteData(GenericRequest $request, $dbName, $tableName, $id)
    {
        $query = $this->dbRepository->getQuery($tableName, $dbName, request()->header('Database-Token'));

        $result = $this->resultBuilder
            ->buildDelete(request(), $query, $tableName, $id);

        $id = $result['id'];
        $query = $result['query'];

        $columnId = array_keys($id)[0];

        if ($query->where($columnId, $id[$columnId])->delete() > 0) {
            $this->dbRepository->unsetDatabase($dbName);

            return $this->response
                        ->setData([
                            'sucesss' => true,
                        ])
                        ->setStatusCode(202)
                        ->json();
        }

        $this->dbRepository->unsetDatabase($dbName);

        throw new DatabaseException('delete');
    }

    public function deleteFilteringData(GenericRequest $request, $dbName, $tableName)
    {
        $query = $this->dbRepository->getQuery($tableName, $dbName, request()->header('Database-Token'));

        $result = $this->resultBuilder
            ->buildFilteringDelete(request(), $query, $tableName);

        $query = $result['query'];

        $rowsDeleted = $query->delete();

        if ($rowsDeleted > 0) {
            $this->dbRepository->unsetDatabase($dbName);

            return $this->response
                        ->setData([
                            'sucesss' => true,
                            'rows_deleted' => $rowsDeleted,
                        ])
                        ->setStatusCode(202)
                        ->json();
        }

        $this->dbRepository->unsetDatabase($dbName);

        return $this->response
                    ->setData([
                        'sucesss' => false,
                        'rows_deleted' => 0,
                    ])
                    ->setStatusCode(202)
                    ->json();
    }

    public function getDatabaseInfo(GetDatabaseRequest $request, $label)
    {
        $db = $this->getSingleDatabase($label, [
            'label',
            'driver',
            'host',
            'port',
            'database',
            'charset',
        ]);

        return $this->response
                    ->setData($db)
                    ->json();
    }

    public function insertDatabase(InsertDatabaseRequest $request)
    {
        $db = new Dbs();
        $db->fill($request->only(
            'label',
            'driver',
            'host',
            'port',
            'username',
            'password',
            'database',
            'charset'
        ));

        $db->token = Helpers::securerandom();
        $db->save();

        $db = $this->getSingleDatabase($request->get('label'), [
            'label',
            'driver',
            'host',
            'port',
            'database',
            'charset',
            'token',
        ]);

        return $this->response
                    ->setData($db)
                    ->setStatusCode(201)
                    ->json();
    }

    public function updateDatabase(UpdateDatabaseRequest $request, $label)
    {
        $db = $this->getSingleDatabase($label);

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

        return $this->response
                    ->setData($db)
                    ->setStatusCode(202)
                    ->json();
    }

    public function deleteDatabase(DeleteDatabaseRequest $request, $label)
    {
        $db = $this->getSingleDatabase($label);

        $db->delete();

        return $this->response
                    ->setStatusCode(204)
                    ->json(true);
    }

    private function getSingleDatabase($id, $fields = ['*'])
    {
        $db = Dbs::select($fields)->where('label', $id);

        if (auth()->user()->role !== 'master') {
            $db = $db->where('token', request()->header('Database-Token'));
        }

        return $db->firstOrFail();
    }
}
