<?php

namespace App\Support;

use Illuminate\Http\Request;
use App\Support\Firebird;
use App\Support\MySQL;
use App\Exceptions\NoPrimaryKeyException;
use App\Exceptions\ManyPrimaryKeysException;
use App\Exceptions\NotFoundException;

class ResultBuilder
{
    public function buildMany(Request $request, $query, $tableName)
    {
        $limit = 10;
        $page = 0;

        if ($request->has('fields')) {
            foreach (explode(',', $request->get('fields')[$tableName]) as $f) {
                $query->addSelect($f);
            }
        }

        if ($request->has('sort')) {
            $orderbys = explode(',', $request->get('sort'));

            foreach ($orderbys as $key => $ob) {
                if ($ob[0] === '-') {
                    $query->orderBy($tableName . '.' . substr($ob, 1), 'DESC');
                } else {
                    $query->orderBy($tableName . '.' . $ob, 'ASC');
                }
            }
        }

        if ($request->has('filter')) {
            foreach ($request->get('filter') as $key => $f) {
                $query->where($tableName . '.' . $key, $f);
            }
        }

        if ($request->has('limit')) {
            $limit = $request->get('limit');

            if ($limit < 1) {
                $limit = 1;
            }
        }

        if ($request->has('page')) {
            $page = $request->get('page') - 1;

            if ($request->get('page') <= 0) {
                $page = 0;
            }
        }

        $query->limit($limit)->offset($page);

        return $query;
    }
    public function buildSingle(Request $request, $query, $tableName, $id)
    {
        if ($request->has('fields')) {
            foreach (explode(',', $request->get('fields')[$tableName]) as $f) {
                $query->addSelect($f);
            }
        }

        $primaryKeys = $this->getPrimaryKeyDatabase($query, $tableName);

        $q = $query->where($primaryKeys[0], $id);

        if ($q->count() < 1) {
            throw new NotFoundException();
        }

        return $query;
    }

    public function buildCreate(Request $request, $query, $tableName)
    {
        $arr = [
            'id' => [],
            'paramsToSave' => [],
        ];

        foreach ($request->all() as $key => $r) {
            $arr['paramsToSave'][$key] = $r;
        }

        if ($query->getConnection()->getName() === 'firebird') {
            $firebird = new Firebird();

            foreach ($firebird->getNextId($tableName) as $key => $id) {
                $arr['paramsToSave'][$key] = $id;
                $arr['id'][$key] = $id;
            }
        }

        if ($query->getConnection()->getName() === 'mysql') {
            $mysql = new MySQL();

            foreach ($mysql->getNextId($tableName) as $key => $id) {
                $arr['paramsToSave'][$key] = $id;
                $arr['id'][$key] = $id;
            }
        }

        return $arr;
    }

    public function buildUpdate(Request $request, $query, $tableName, $id)
    {
        $arr = [
            'id' => [],
            'paramsToSave' => [],
        ];

        foreach ($request->all() as $key => $r) {
            $arr['paramsToSave'][$key] = $r;
        }

        $primaryKeys = $this->getPrimaryKeyDatabase($query, $tableName);
        $query = $query->where($primaryKeys[0], $id);

        if ($query->where($primaryKeys[0], $id)->count() < 1) {
            throw new NotFoundException();
        }

        foreach ($primaryKeys as $key => $p) {
            $arr['id'][$p] = $id;
        }

        return $arr;
    }

    public function buildFilteringUpdate(Request $request, $query, $tableName)
    {
        if (!$request->has('filter')) {
            throw new \Exception("Error Processing Request");
        }

        $arr = [
            'paramsToSave' => [],
            'query' => null,
        ];

        if ($query->getConnection()->getName() === 'firebird') {
            $firebird = new Firebird();
            $primaryKeys = $firebird->getPrimaryKey($tableName);

            foreach ($request->except('filter') as $key => $r) {
                if (!in_array($key, $primaryKeys) && !$firebird->isGenerator($key)) {
                    $arr['paramsToSave'][$key] = $r;
                }
            }
        }

        if ($query->getConnection()->getName() === 'mysql') {
            $mysql = new MySQL();
            foreach ($request->except('filter') as $key => $r) {
                if (!$mysql->isAutoIncremented($tableName, $key)) {
                    $arr['paramsToSave'][$key] = $r;
                }
            }
        }

        foreach ($request->get('filter') as $key => $f) {
            $arr['query'] = $query->where($tableName . '.' . $key, $f);
        }

        return $arr;
    }

    public function buildDelete(Request $request, $query, $tableName, $id)
    {
        $arr = [
            'id' => [],
            'query' => null,
        ];

        $primaryKeys = $this->getPrimaryKeyDatabase($query, $tableName);
        $query = $query->where($primaryKeys[0], $id);

        if ($query->where($primaryKeys[0], $id)->count() < 1) {
            throw new NotFoundException();
        }

        foreach ($primaryKeys as $key => $p) {
            $arr['id'][$p] = $id;
        }

        $arr['query'] = $query;

        return $arr;
    }

    private function getPrimaryKeyDatabase($query, $tableName)
    {
        if ($query->getConnection()->getName() === 'firebird') {
            $db = new Firebird();
        }

        if ($query->getConnection()->getName() === 'mysql') {
            $db = new MySQL();
        }

        $primaryKeys = $db->getPrimaryKey($tableName);

        if (count($primaryKeys) === 0) {
            throw new NoPrimaryKeyException();
        }

        if (count($primaryKeys) > 1) {
            throw new ManyPrimaryKeysException();
        }

        return $primaryKeys;
    }
}
