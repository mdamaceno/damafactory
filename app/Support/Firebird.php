<?php

namespace App\Support;

class Firebird
{
    public function getPrimaryKey($tableName)
    {
        $sql = "SELECT
            IX.RDB\$INDEX_NAME AS INDEX_NAME,
            SG.RDB\$FIELD_NAME AS FIELD_NAME
        FROM
            RDB\$INDICES IX
            LEFT JOIN RDB\$INDEX_SEGMENTS SG ON IX.RDB\$INDEX_NAME = SG.RDB\$INDEX_NAME
            LEFT JOIN RDB\$RELATION_CONSTRAINTS RC ON RC.RDB\$INDEX_NAME = IX.RDB\$INDEX_NAME
        WHERE
            RC.RDB\$CONSTRAINT_TYPE = 'PRIMARY KEY'
        AND RC.RDB\$RELATION_NAME = '" . $tableName . "'";

        $result = \DB::connection('firebird')
          ->select(\DB::raw($sql));
        
        $arr = [];

        if (count($result) > 0) {
            foreach ($result as $r) {
                array_push($arr, trim($r->FIELD_NAME));
            }
        }

        return $arr;
    }
}
