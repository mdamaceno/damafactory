<?php

namespace App\Support;

class MySQL
{
    public function getPrimaryKey($tableName)
    {
        $sql = "SHOW KEYS FROM " . $tableName . " WHERE Key_name = 'PRIMARY';";

        $result = \DB::connection('mysql')
          ->select(\DB::raw($sql));

        $arr = [];

        if (count($result) > 0) {
            foreach ($result as $r) {
                array_push($arr, trim($r->Column_name));
            }
        }

        return $arr;
    }
}