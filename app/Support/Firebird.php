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

    public function getNextId($tableName)
    {
        $primaryKeys = $this->getPrimaryKey($tableName);
        $arr = [];

        foreach ($primaryKeys as $pk) {
            if (in_array($pk, $this->getGenerators())) {
                $sql = "SELECT GEN_ID( " . $pk . ", 0 ) AS ID FROM RDB\$DATABASE;";
                
                $result = \DB::connection('firebird')
                  ->select(\DB::raw($sql));

                foreach ($result as $r) {
                    $arr[$pk] = $r->ID + 1;
                }
            }
        }

        return $arr;
    }

    public function getGenerators()
    {
        $arr = [];
        $sql = "SELECT RDB\$GENERATOR_NAME AS SEQUENCE_NAME FROM RDB\$GENERATORS";
        $result = \DB::connection('firebird')
              ->select(\DB::raw($sql));

        foreach ($result as $r) {
            array_push($arr, trim(get_object_vars($r)['SEQUENCE_NAME']));
        }

        return $arr;
    }
}
