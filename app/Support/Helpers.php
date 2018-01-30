<?php

namespace App\Support;

class Helpers
{
    public static function array_utf8_encode($dat)
    {
        if (is_string($dat))
            return utf8_encode($dat);
        if (!is_array($dat))
            return $dat;
        $ret = array();
        foreach ($dat as $i => $d)
            $ret[$i] = self::array_utf8_encode($d);
        return $ret;
    }

    public static function array_change_key_case_recursive($arr, $case=CASE_LOWER)
    {
        return array_map(function($item)use($case){
          if(is_array($item))
              $item = Helpers::array_change_key_case_recursive($item, $case);
          return $item;
        },array_change_key_case($arr, $case));
    }

    /**
     * Função para criptografia de dados
     * @param string $mCad Valor de enrada
     * @param int $mOp Tipo de criptografia - 0 encrypt | 1 decrypt
     * @return string $mCad criptografado
     */
    public static function fCrypt($mCad, $mOp = 0) {
        if ((strlen($mCad) / 2) != intval(strlen($mCad) / 2)) {
            $mCad .= ' ';
        }
        $ate = (strlen($mCad) / 2);
        $result = "";
        for ($i = 1; $i <= $ate; $i++) {
            $x1 = ord(substr($mCad, (($i - 1) * 2), 1));
            $x2 = ord(substr($mCad, (($i - 1) * 2) + 1, 1));
            if ($mOp == '1') {
                $r2 = chr(( - $x2 + $x1 + 90) / 2);
                $r1 = chr($x1 - ((- $x2 + $x1 + 90) / 2));
            } else {
                $r2 = chr($x1 + 90 - $x2);
                $r1 = chr($x1 + $x2);
            }
            $result .= $r1 . $r2;
        }
        return $result;
    }
}
