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
            if ($this->isGenerator($pk)) {
                // if (in_array($pk, $this->getGenerators())) {
                $sql = 'SELECT GEN_ID( ' . $pk . ', 1 ) AS ID FROM RDB$DATABASE;';

                $result = \DB::connection('firebird')
                  ->select(\DB::raw($sql));

                foreach ($result as $r) {
                    $arr[$pk] = $r->ID;
                }
            }
        }

        return $arr;
    }

    public function getGenerators()
    {
        $arr = [];
        $sql = 'SELECT RDB$GENERATOR_NAME AS SEQUENCE_NAME FROM RDB$GENERATORS';
        $result = \DB::connection('firebird')
              ->select(\DB::raw($sql));

        foreach ($result as $r) {
            array_push($arr, trim(get_object_vars($r)['SEQUENCE_NAME']));
        }

        return $arr;
    }

    public function isGenerator($gk)
    {
        $arr = [];
        $sql = "SELECT COUNT(*) IS_GENERATOR FROM RDB\$GENERATORS WHERE RDB\$GENERATOR_NAME = '{$gk}'";
        $result = \DB::connection('firebird')
              ->select(\DB::raw($sql));

        return (bool) $result[0]->IS_GENERATOR;
    }

    public function getComputedColumns($tableName)
    {
        $arr = [];
        $sql = "
            SELECT
                F.RDB\$FIELD_NAME AS FIELD_NAME
            FROM RDB\$RELATION_FIELDS F
            JOIN RDB\$FIELDS F2 ON F2.RDB\$FIELD_NAME = F.RDB\$FIELD_SOURCE
            JOIN RDB\$RELATIONS R ON F.RDB\$RELATION_NAME = R.RDB\$RELATION_NAME

            WHERE F.RDB\$RELATION_NAME = '" . $tableName . "'
            AND F2.RDB\$COMPUTED_SOURCE IS NOT NULL;";

        $result = \DB::connection('firebird')
              ->select(\DB::raw($sql));

        foreach ($result as $r) {
            array_push($arr, trim($r->FIELD_NAME));
        }

        return $arr;
    }
}
