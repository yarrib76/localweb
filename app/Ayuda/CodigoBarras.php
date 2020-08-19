<?php
/**
 * Created by PhpStorm.
 * User: yarrib76
 * Date: 20/2/18
 * Time: 12:49
 */

namespace Donatella\Ayuda;


class CodigoBarras
{
    public function crearDigitoCOntrol($codifoBarras)
    {
        $digito = 0;
        switch (strlen($codifoBarras))
        {
            Case 12:
                $codTmp = substr('0000000000000000' . $codifoBarras, -17);
                $bPal = 3;
                $calTotal = 0;
                for ($numC = 0; $numC <= 17; $numC++){
                    //preg_match_all(substr($codTmp, $numC, 1),$match);
                    $calTotal = $calTotal + (int)substr($codTmp, $numC, 1) * $bPal;
                    $bPal = 4 - $bPal;
                }
                $digito = $calTotal % 10;
                if ($digito == 0){
                    $digito = 0;
                }else{
                    $digito = 10 - $digito;
                }
        }
        return $digito;
    }
}

