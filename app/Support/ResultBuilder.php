<?php

namespace App\Support;

use Illuminate\Http\Request;
use App\Support\Firebird;
use App\Support\MySQL;
use App\Exceptions\NoPrimaryKeyException;
use App\Exceptions\ManyPrimaryKeysException;

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

        if ($query->getConnection()->getName() === 'firebird') {
            $firebird = new Firebird();
            $primaryKeys = $firebird->getPrimaryKey($tableName);

            if (count($primaryKeys) === 0) {
                throw new NoPrimaryKeyException();
            }

            if (count($primaryKeys) > 1) {
                throw new ManyPrimaryKeysException();
            }
        }

        if ($query->getConnection()->getName() === 'mysql') {
            $mysql = new MySQL();
            $primaryKeys = $mysql->getPrimaryKey($tableName);

            if (count($primaryKeys) === 0) {
                throw new NoPrimaryKeyException();
            }

            if (count($primaryKeys) > 1) {
                throw new ManyPrimaryKeysException();
            }
        }

        $query->where($primaryKeys[0], $id);

        return $query;
    }

    public function buildCreate(Request $request, $query, $tableName)
    {
        dd($this->firebird->getNextId($tableName));
    }
}
