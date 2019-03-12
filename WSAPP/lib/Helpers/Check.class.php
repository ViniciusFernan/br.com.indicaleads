<?php

/**
 * DataValid.class [ HELPER ]
 * Classe responável por manipular e validade dados do sistema!
 *
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */
class Check {

    private static $Data;
    private static $Format;



    /**
     * <b>Verifica E-mail:</b> Executa validação de formato de e-mail. Se for um email válido retorna true, ou retorna false.
     * @param STRING $Email = Uma conta de e-mail
     * @return BOOL = True para um email válido, ou false
     */
    public static function Email($Email) {
        self::$Data = (string) $Email;
        self::$Format = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';

        if (preg_match(self::$Format, self::$Data)):
            return true;
        else:
            return false;
        endif;
    }


    /**
     * Verifica se o dado passado e um numero
     * @param mixed $mx_value;
     * @return boolean
     */
    static function isNumeric($mx_value) {

        self::$Data = $mx_value;

        self::$Data = str_replace(',', '.', self::$Data);
        if (!(is_numeric(self::$Data)))
            return false;
        return true;
    }

    /**
     * Verifica se o dado passado e um numero inteiro
     * @param mixed $mx_value;
     * @return boolean
     */
    static function isInteger($mx_value) {

        self::$Data = $mx_value;

        if (!self::isNumeric(self::$Data))
            return false;

        if (preg_match('/[[:punct:]&^-]/', self::$Data) > 0)
            return false;
        return true;
    }


    /**
     * Valida CPF
     * @param STRING $cpf
     * @return boolean - True para CPF Válido
     */
      public static function CPF($cpf){
            $cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
            if ( strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
                return FALSE;
            } else { // Calcula os números para verificar se o CPF é verdadeiro
                for ($t = 9; $t < 11; $t++) {
                    for ($d = 0, $c = 0; $c < $t; $c++) {
                        $d += $cpf{$c} * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;
                    if ($cpf{$c} != $d) {
                        return FALSE;
                    }
                }
                return TRUE;
            }
    }


    public static function checkRegistroDeDispositivoDoUsuario($idUsuario, $idDispositivo){

        $sql = 'SELECT *  FROM  app_id_user
                WHERE app_id_user.idUsuario=:idUsuario  
                AND app_id_user.idDispositivo=:idDispositivo';

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$idUsuario}&idDispositivo={$idDispositivo}");
        if (!empty($Select->getResult())):
            return $Select->getResult();
        else:
            return false;
        endif;

    }

    public static function checkDispositivo($idDispositivo){

        $sql = 'SELECT *  FROM  app_id_user
                WHERE app_id_user.idDispositivo=:idDispositivo';

        $Select = new Select;
        $Select->FullSelect($sql, "idDispositivo={$idDispositivo}");
        if (!empty($Select->getResult())):
            return $Select->getResult();
        else:
            return false;
        endif;

    }



}
