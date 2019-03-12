<?php

/**
 * Usuario.model [ MODEL USUARIO ]
 * Responsável por gerenciar os usuários no Admin do sistema!
 */
class DispositivoModel {

    private $Data;
    private $User; //idUsuario
    private $Error;
    private $Result;

    //Paginacao
    private $paginacao;


    //Nome da tabela no banco de dados
    const Entity = 'central_de_noticacao';


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



    public function registrarDispositivoNotifications($idUsuario, $idDispositivo, $gcmId){

        $idRegistroApp = Check::checkRegistroDeDispositivoDoUsuario($idUsuario, $idDispositivo);
        if(!empty($idRegistroApp)){
            $ct = new Update;
            $ct->ExeUpdate('app_id_user',['gcmId'=>$gcmId], "WHERE idUsuario=:idUsuario AND idDispositivo=:idDispositivo","idUsuario={$idRegistroApp[0]['idUsuario']}&idDispositivo={$idRegistroApp[0]['idDispositivo']}" );
            $this->Result = $ct->getResult();
        }else{
            $ctNew = new Create;
            $ctNew->ExeCreate('app_id_user',['idUsuario'=>$idUsuario, 'idDispositivo'=>$idDispositivo, 'gcmId'=>$gcmId]);
            $this->Result = $ctNew->getResult();
        }
    }

    
}
