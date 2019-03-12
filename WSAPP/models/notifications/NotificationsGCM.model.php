<?php

/**
 */
class NotificationsGCMModel {

    private $Data;
    private $User; //idUsuario
    private $Error;
    private $Result;
    private $GCM_API_KEY;

    //Paginacao
    private $paginacao;



    /**
     * <b>Verificar Cadastro:</b> Retorna TRUE se o cadastro ou update for efetuado ou FALSE se não.
     * Para verificar erros execute um getError();
     * @return BOOL $Var = True or False
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com um erro e um tipo.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }


    /**
    * METODO QUE INICIA A CLASSE SETANDO A CHAVE DO SERVIDOR
    * FUTURAMENTE ESSA CHAVE PODE SER TRAZIDA DE UMA CONFIGURAÇÃO DO SISTEMA
    */
    public function __construct(){
        $this->GCM_API_KEY=GCM_API_KEY;
    }


    /**
     * Prepara alerta google GCM para disparo
     * @param Array $chaveDoDispositivo [chave_1, chave_2]
     * @param String $titulo "Titulo Mensagem ao usuario"
     * @param String $mensagem "Conteudo da mensagem"
     */
    public function sendMesagemGCMAction($chaveDoDispositivo, $titulo, $mensagem, $url=NULL, $idItem=NULL){
        $this->Result = false;
        if(!empty($chaveDoDispositivo)):

            $chaveDoDispositivo = (is_array($chaveDoDispositivo) ? $chaveDoDispositivo : [$chaveDoDispositivo] );
            $titulo = (!empty($titulo)? $titulo : 'Tem coisa novo na area!');
            $mensagem = (!empty($mensagem) ? $mensagem : 'Corre la para ver em primeira mão!!');

            $url = (!empty($url)? $url : NULL);
            $idItem = (!empty($idItem)? $idItem : NULL);

            if($this->setGCMNotificationAction($chaveDoDispositivo, $titulo, $mensagem, $url, $idItem)){
                $this->Result = true;
            }

        endif;

    }



    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    /**
     * Dispara alerta google GCM
     * @param Array $chaveDoDispositivo [chave_1, chave_2]
     * @param String $titulo "Titulo Mensagem ao usuario"
     * @param String $mensagem "Conteudo da mensagem"
     * @param String $url "pagina de destino quando clicar na notificação"
     * @param int $idItem "Item a abrir ao chegar na pagina de destino"
     */
    private function setGCMNotificationAction($chaveDoDispositivo, $titulo, $mensagem, $url=NULL, $idItem=NULL){

        $chaveDoDispositivo; //chaves dos aps instalados nos usuarios DEVE SER UM ARRAY 1 ou mais chave [chave_1, chave_2]

        $title = $titulo;

        $message = $mensagem;


        $headers = array(
            'Authorization: key='.$this->GCM_API_KEY,
            'Content-Type: application/json'
        );

        $fields = array(
            'registration_ids' => $chaveDoDispositivo,
            "data" => array(
                'title' => $title,
                'message' => $message,
                //'msgcnt' => count($message),
                'content-available' => '1', //only for IOS
                'timestamp' => date('Y-m-d h:i:s'),
                'sound' => 1,
                'vibrate' => 1,
                'url' => $url,
                'idItem' => $idItem,
            ),
        );



        //Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
        $GCM = curl_init();
        curl_setopt( $GCM,CURLOPT_URL, $url );
        curl_setopt( $GCM,CURLOPT_POST, true );
        curl_setopt( $GCM,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $GCM,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $GCM,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $GCM,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $resultJson = curl_exec($GCM);
        $result = json_decode($resultJson);
        curl_close( $GCM );

        if($result->failure==1){
            $this->logCGMError($resultJson);
           return false;
        }else{
            return true;
        }
    }


    private function logCGMError($msg){

        $fp = fopen("logGCM.txt", "a");
        fwrite($fp, $msg."\r\n");
        fclose($fp);

    }


}
