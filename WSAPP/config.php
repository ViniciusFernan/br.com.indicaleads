<?php
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:GET,HEAD,POST,PUT,DELETE,TRACE,OPTIONS');


/**
 * Arquivo de Configuração do Sistema.
 * @package WEBSERVICE
 * @author AVF
 * @version 1.0
 */

/** Caminho para a raiz */
define('ABSPATH', dirname(__FILE__));

/** Criptografia da senha  */
define('HASH', '502ff82f7f1f8218dd41201fe4353687');


//CORRIGE HORARIO DO SISTEMA
date_default_timezone_set("Brazil/East");

/** Configurações da conexão com o banco de dados */
define('HOSTNAME', 'maisindicacoes.com.br'); //Hostname do banco
define('DB_NAME', 'maisindi_indica'); // Nome do DB
define('DB_USER', 'maisindi_indica'); // Usuário do DB
define('DB_PASSWORD', '9DS4wUneVtT5SnO2vJ'); // Senha do DB
define('DB_CHARSET', 'utf8'); // Charset da conexão PDO

// chave de integração firebase
define('GCM_API_KEY', 'AAAA7LIDiyI:APA91bFK4lmZeXju-3a3a2sm2MxHiGrMtyshlP5UtV4VW7rRuiczDRmeTR0-3VhOhw5BT4oPJ1jSxvBcqOcLZkXje5NAF_LHuhhSM5zMr3X4g9I3npfrUN2S3Q2yR89c0KtBSBXwlYjw');



/*
 * Ativa o Debug do PHP
 */
define('DEBUG', true);
if (!defined('DEBUG') || DEBUG === false) {
    // Esconde todos os erros
    error_reporting(0);
    ini_set("display_errors", 0);
} else {
    // Mostra todos os erros
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}


/**
 * Função para carregar automaticamente todas as classes padrão
 * Ver: http://php.net/manual/pt_BR/function.autoload.php.
 * Nossas classes estão na pasta lib/, lib/Conn e lib/Helpers.
 * O autoload identifica qual pasta que contém o arquivo da classe e importa
 * O nome do arquivo deverá ser NomeDaClasse.class.php.
 * Por exemplo: para a classe Application, o arquivo vai chamar Application.class.php
 */
function __autoload($Class) {
    // Diretórios das classes
    $cDir = ['lib', 'lib/Helpers', 'lib/Conn'];
    $inc = null;

    foreach ($cDir as $dirName) {
        $arquivo = ABSPATH . "/{$dirName}/{$Class}.class.php";
        if (file_exists($arquivo) && !is_dir($arquivo)){
            $inc = true;
            break;
        }
    } //endoreach

    if ($inc) {
        require_once $arquivo;
    } else {
        trigger_error("Não foi possível incluir {$Class}.class.php", E_USER_ERROR);
        die;
    }
}



/*
 * TRATAMENTO DE ERROS
 * CSS constantes :: Mensagens de Erro
 */
define('ERR_ACCEPT', 'alert-success');
define('ERR_INFOR', 'alert-info');
define('ERR_ALERT', 'alert-warning');
define('ERR_ERROR', 'alert-danger');

/**
 * FrontErro :: Exibe erros lançados no Front (Com Estilo CSS)
 * @param String|Array $ErrMsg - Mensagem do Erro
 * @param String $ErrNo - Padrão de Erro PHP [E_USER_NOTICE | ERR_INFOR | E_USER_WARNING | ERR_ALERT]
 * @param boolean $ErrDie - True caso queira parar a execução da pagina
 */
function FrontErro($ErrMsg, $ErrNo, $ErrDie = null) {

    $CssClass = ($ErrNo == E_USER_NOTICE ? ERR_INFOR : ($ErrNo == E_USER_WARNING ? ERR_ALERT : ($ErrNo == E_USER_ERROR ? ERR_ERROR : $ErrNo)));
    echo '<div class="alert ' . $CssClass . ' alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                  ' . $ErrMsg . '
                </div>';

    if ($ErrDie):
        die;
    endif;
}

/**
 * PHPErro :: personaliza o gatilho do PHP
 * Para disparar manualmente use no try catch: PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
 * @param String $ErrNo - Padrão de Erro PHP [E_USER_NOTICE | ERR_INFOR | E_USER_WARNING | ERR_ALERT]
 * @param String $ErrMsg - Mensagem do Erro
 * @param type $ErrFile - Arquivo do erro
 * @param type $ErrLine - Linha do erro
 */
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? ERR_INFOR : ($ErrNo == E_USER_WARNING ? ERR_ALERT : ($ErrNo == E_USER_ERROR ? ERR_ERROR : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">";
    echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class=\"ajax_close\"></span></p>";

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

