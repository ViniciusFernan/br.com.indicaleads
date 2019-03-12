<?php

/**
 * Classe designada a filtragem de dados
 *
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */
class Filter {

    /**
     * Variaveis a serem usadas
     * @var array|string
     */
    private static $Var;
    private static $Format;
    private static $Data;

    /**
     * Retira pontuacao da string
     * @param string $st_data
     * @return string
     */
    static function alphaNum($st_data) {
        $st_data = preg_replace("([[:punct:]]| )", '', $st_data);
        return $st_data;
    }

    /**
     * Remove acentuacao da string
     * @param String $str
     * @return String
     */
    static function removeAcentos($str) {
        // assume $str esteja em UTF-8
        $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $to = "aaaaeeiooouucAAAAEEIOOOUUC";
        $keys = array();
        $values = array();
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        return strtr($str, $mapping);
    }

    /**
     * Retira caracteres nao numericos da string
     * @param string $st_data
     * @return string
     */
    static function numeric($st_data) {
        $st_data = preg_replace("([[:punct:]]|[[:alpha:]]| )", '', $st_data);
        return $st_data;
    }

    /**
     *
     * Retira tags HTML / XML e adiciona "\" antes
     * de aspas simples e aspas duplas
     * @param string $st_string
     */
    static function cleanString($st_string) {
        return addslashes(strip_tags($st_string));
    }

    /**
     * Filtra uma string contra Sql Injection
     * @param String $string
     * @param Bool $adicionaBarras
     * @return String - String Filtrada
     */
    private static function antiSQLSItring($string, $adicionaBarras = false) {
        if (!is_array($string)) {
            // remove palavras que contenham sintaxe sql
            $string = preg_replace("/(from|alter table|select|insert|delete|update|where|drop table|show tables|#|\*|--|\\\\)/i", "", $string);

            $string = trim($string); //limpa espaços vazio
            $string = strip_tags($string); //tira tags html e php
            if ($adicionaBarras || !get_magic_quotes_gpc())
                $string = addslashes($string);
            return $string;
        } else {
            return $string;
        }
    }

    /**
     * Trata sql injection para string e array
     * Se for um array, passa por todos os indices
     * @param Mixed $array/$string
     * @return Mixed - Variavel Limpa
     */
    public static function SqlInjection($var) {

        if (empty($var))
            return;

        self::$Var = $var;

        if (is_array(self::$Var)) {
            foreach (self::$Var as $key => $value) {
                if (!is_array($value)) {
                    $resp[$key] = self::antiSQLSItring($value);
                } else {
                    $resp[$key] = self::SqlInjection($value);
                }
            }
            return $resp;
        } else {
            return self::antiSQLSItring($var);
        }
    }

    /**
     * Remove linhas em branco de uma string
     * @param String $str
     * @return String
     */
    public static function RemoveBlankLines($str) {

        self::$Var = $str;
        self::$Format = "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/";

        self::$Var = preg_replace(self::$Format, "\n", self::$Var);
        return self::$Var;
    }

    /**
     * CONVERTE DATA BR PARA O FORMATO YYYY-MM-DD
     * @param String $Data
     * @return String
     */
    public static function DataToDate($Data) {
        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format[0]);

        self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0];
        return self::$Data;
    }

    /**
     * <b>Tranforma Data:</b> Transforma uma data no formato DD/MM/YY em uma data no formato TIMESTAMP!
     * @param STRING $Name = Data em (d/m/Y) ou (d/m/Y H:i:s)
     * @return STRING = $Data = Data no formato timestamp!
     */
    public static function DateToBr($Data, $divisor = NULL) {
        $d = ($divisor ? $divisor : '/');
        self::$Data = $Data;
        self::$Format = explode(' ', self::$Data);
        if (!empty(self::$Format[1])):
            return date("d{$d}m{$d}Y H:i:s", strtotime(self::$Data));
        else:
            return date("d{$d}m{$d}Y", strtotime(self::$Data));
        endif;
    }


    /**
     * Converte data br (dd/mm/yyyy hh:mm:ss) para DateTime (YYYY-mm-dd HH:ii:ss)
     * @param type $Data
     * @return type
     */
    public static function DataToDatetime($Data) {
        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format[0]);

        if (empty(self::$Format[1])):
            self::$Format[1] = date('H:i:s');
        endif;

        self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0] . ' ' . self::$Format[1];
        return self::$Data;
    }


    /**
     * Converte o valor passado pelo dia da semana em extenso
     * @param Int $intDiaSemana
     * @return boolean|string
     */
    public static function NumParaDiaSemana($intDiaSemana) {

        if (!is_numeric($intDiaSemana)) {
            return false;
        }

        switch ($intDiaSemana) {
            case '0':
                return "Domingo";
                break;
            case '1':
                return "Segunda";
                break;
            case '2':
                return "Terça";
                break;
            case '3':
                return "Quarta";
                break;
            case '4':
                return "Quinta";
                break;
            case '5':
                return "Sexta";
                break;
            case '6':
                return "Sábado";
                break;
            default:
                break;
        }

    }


    /*****
     * Vinicius
     * RETURN TEMPO DE POSTAGEM EM DATA OU STRING.
     * POSTADO AGORA !
     * ALGUNS SEGUNDOS !
     * DIAS ATRÁS
     */

    public static function returnTempoDePostagem($dateDB){
        date_default_timezone_set('America/Sao_Paulo');
        $timestamp = strtotime($dateDB);
        $diferenca = strtotime(date('Y-m-d H:i:s')) - strtotime($dateDB);

        if (date('Y-m-d') == date('Y-m-d', $timestamp)){// se for hoje
            if($diferenca < 60){//menos de 1 minuto

                $hora = "Agora";
            }elseif ($diferenca >= 60 && $diferenca <= 3600) {//menos de uma hora

                $hora = floor($diferenca / 60) . " min. atrás";
            }elseif ($diferenca > 3600) {//mais de uma hora

                $hora = floor($diferenca / 3600) . " horas atrás";
            }
        }elseif (date('Y-m-d', strtotime('-1 day')) == date('Y-m-d', $timestamp)) {//se for ontem

            $hora = "Ontem as " . date('H:i', strtotime($dateDB));
        }else {//se for outros dias

            $hora = date('d/m/Y H:i:s', $timestamp);
        }

        return $hora;
    }
    
    /**
     * Descreve o tempo restante baseado na hora atual
     * @param STRING $dataHora
     * @return STRING - Tempo faltante em extenso
     */
    public static function TempoRestante($dataHora) {
        date_default_timezone_set('America/Sao_Paulo');
        $timestamp = strtotime($dataHora);
        $diferenca = strtotime(date('Y-m-d H:i:s')) - strtotime($dataHora);

        if (date('Y-m-d') == date('Y-m-d', $timestamp)) {// se for hoje
            if ($diferenca < 60) {//menos de 1 minuto
                $hora = "Em 1 minuto.";
            } elseif ($diferenca >= 60 && $diferenca <= 3600) {//menos de uma hora
                $tmp = floor($diferenca / 60);
                $hora = "Em " . $tmp . " minuto" . ($tmp > 1 ? "s" : "") . ".";
            } elseif ($diferenca > 3600) {//mais de uma hora
                $tmp = floor($diferenca / 3600);
                $hora = $tmp . " hora" . ($tmp > 1 ? "s" : "");
            }
        } elseif (date('Y-m-d', strtotime('+1 day')) == date('Y-m-d', $timestamp)) {//se for amanha
            $hora = "Amanhã as " . date('H:i', strtotime($dataHora));
        } else {//se for outros dias
            $hora = date('d/m/Y H:i:s', $timestamp);
        }

        return $hora;
    }

    public static function  transformarMinutosEmHora($mins) {
        if ($mins < 0):
            $min = abs($mins);
        else:
            $min = $mins;
        endif;

        $h = floor($min / 60);
        $m = ($min - ($h * 60)) / 100;
        $horas = $h + $m;

        if ($mins < 0):
            $horas *= -1;
        endif;

        $sep = explode('.', $horas);
        $h = $sep[0];
        if (empty($sep[1])):
            $sep[1] = 00;
        endif;
        $m = $sep[1];

        if (strlen($m) < 2):
            $m = $m . 0;
        endif;
        return sprintf('%02d:%02d', $h, $m);
    }


    /*****
     * Vinicius
     * RETURN BROWSER.
     */

    public static function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }


    public static function  tratarUrlsViews($url){
        $url = explode('?', $url)[0];
        $url = str_replace(['http://', 'https://', 'www.'], '', $url);
        $url = str_replace(['.com.br'], '...', $url);
        $uarray = explode('/', $url);
        $uarray = array_filter($uarray);
        $url1 = $uarray[0];
        $url2 = (count($uarray)>1 ?  end($uarray) : '');
        return 'www.'.$url1."/".$url2;
    }


}
