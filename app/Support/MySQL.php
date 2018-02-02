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

    public function getNextId($tableName)
    {
        $primaryKeys = $this->getPrimaryKey($tableName);
        $arr = [];

        $sql = "
            SELECT AUTO_INCREMENT FROM information_schema.tables
            WHERE table_name = '" . $tableName . "'
            AND table_schema = DATABASE();";

        $result = \DB::connection('mysql')
          ->select(\DB::raw($sql));

        foreach ($primaryKeys as $key => $pk) {
            $arr[$pk] = $result[0]->AUTO_INCREMENT;
        }

        return $arr;
    }

    public function isAutoIncremented($tableName, $column)
    {
        $sql = "
            SHOW COLUMNS
            FROM " . $tableName . "
            WHERE Extra LIKE '%auto_increment%';";

        $result = \DB::connection('mysql')
          ->select(\DB::raw($sql));

        if (count($result) < 0) {
            return false;
        }

        foreach ($result as $r) {
            if ($r->Field === $column) {
                return true;
            }
        }

        return false;
    }
}
