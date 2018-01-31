<?php

namespace App\Support;

use Illuminate\Http\Request;
use App\Support\Firebird;

class ResultBuilder
{
    public function __construct()
    {
        $this->firebird = new Firebird();
    }

    public function buildMany(Request $request, $query, $tableName)
    {
        $limit = 10;
        $page = 0;

        if (request()->has('fields')) {
            foreach (explode(',', request()->get('fields')[$tableName]) as $f) {
                $query->addSelect($f);
            }
        }

        if (request()->has('sort')) {
            $orderbys = explode(',', request()->get('sort'));

            foreach ($orderbys as $key => $ob) {
                if ($ob[0] === '-') {
                    $query->orderBy($tableName . '.' . substr($ob, 1), 'DESC');
                } else {
                    $query->orderBy($tableName . '.' . $ob, 'ASC');
                }
            }
        }

        if (request()->has('filter')) {
            foreach (request()->get('filter') as $key => $f) {
                $query->where($tableName . '.' . $key, $f);
            }
        }

        if (request()->has('limit')) {
            $limit = request()->get('limit');

            if ($limit < 1) {
                $limit = 1;
            }
        }

        if (request()->has('page')) {
            $page = request()->get('page') - 1;

            if (request()->get('page') <= 0) {
                $page = 0;
            }
        }

        $query->limit($limit)->offset($page);

        return $query;
    }
    public function buildSingle(Request $request, $query, $tableName, $id)
    {
        if (request()->has('fields')) {
            foreach (explode(',', request()->get('fields')[$tableName]) as $f) {
                $query->addSelect($f);
            }
        }

        if ($query->getConnection()->getName() === 'firebird') {
            $primaryKeys = $this->firebird->getPrimaryKey($tableName);

            if (count($primaryKeys) > 1) {
                throw new \Exception('Table has more than one primary key. Use "filter"');
            }
        }

        return $query;
    }

    public function buildCreate(Request $request, $query, $tableName)
    {
        dd($this->firebird->getNextId($tableName));
    }
}
