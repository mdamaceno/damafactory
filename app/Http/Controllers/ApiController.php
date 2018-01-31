<?php

namespace App\Http\Controllers;

use App\Support\Helpers;
use App\Support\ConnectDatabase;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->connDB = new ConnectDatabase();
    }

    public function getData($dbName, $tableName)
    {
        $database = \DB::table('dbs')->where('label', $dbName)->first();

        $this->connDB->setDatabase($database->driver, $database);

        $limit = 10;
        $page = 0;
        $query = \DB::table($tableName);

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

        $result = $query
          ->limit($limit)
          ->offset($page)
          ->get();

        $this->connDB->unsetDatabase($database->driver);

        return response()->json([
            'data' => Helpers::array_utf8_encode($result),
        ]);
    }
}
