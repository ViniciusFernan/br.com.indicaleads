<?php

/**
 * Usuario.model [ MODEL USUARIO ]
 * Responsável por gerenciar os usuários no Admin do sistema!
 */
class NotificationsModel {

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




    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */


    /**
     * Seleciona o Usuário
     * @param Int $idUsuario
     */
    public function getNotifications($idUsuario, $idTipoNotificacao){
        $resp=[];
        if(!empty($idTipoNotificacao)){
            //pesquisa especifica
            $xQuery='AND central_de_notificacao.idTipoNotificacao=:idTipoNotificacao';
        }else{
            $xQuery="AND ( 
                            central_de_notificacao.idTipoNotificacao=1 
                        OR central_de_notificacao.idTipoNotificacao=3
                        OR central_de_notificacao.idTipoNotificacao=10
                      )";
        }

        $xParse=(!empty($idTipoNotificacao) ? "&idTipoNotificacao=".$idTipoNotificacao:'');

        $sql = "SELECT 
                central_de_notificacao.idNotificacao, 
                central_de_notificacao.idUsuario, 
                central_de_notificacao.idReferencia, 
                central_de_notificacao.idTipoNotificacao,  
                central_de_notificacao_tipo.tipoNotificacao, 
                central_de_notificacao_tipo.descricao 
                FROM  central_de_notificacao
                INNER JOIN central_de_notificacao_tipo ON central_de_notificacao_tipo.idTipoNotificacao = central_de_notificacao.idTipoNotificacao
                 WHERE central_de_notificacao.idUsuario=:idUsuario {$xQuery}
                 AND DATE(central_de_notificacao.dataCadastro) = CURRENT_DATE()
                 AND ( central_de_notificacao.statusNotificacaoAplicativo=1 
                       OR central_de_notificacao.statusNotificacaoAplicativo IS NULL )
                 
                 ORDER BY central_de_notificacao.idNotificacao ASC   
                       ";

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$idUsuario}{$xParse}");
        if (!empty($Select->getResult())):
            $arrayR=[];
            foreach($Select->getResult() as $key => $notify){
                $notify['linkPage'] = $this->getLinkPorTipoDeNotificacao($notify['idTipoNotificacao']);
                $arrayR[$notify['idNotificacao']]=$notify;
            }
            $this->Result = $arrayR;
        else:
            $this->Result = false;
        endif;
    }



    /**
     * Seleciona o Usuário
     * @param Int $idUsuario
     */
    public function getALLCurrentNotificationsFromTipoFromAplicativo($idTipoNotificacao){

        $sql = 'SELECT central_de_notificacao.* FROM central_de_notificacao 
                INNER JOIN app_id_user ON central_de_notificacao.idUsuario = app_id_user.idUsuario
                WHERE central_de_notificacao.dataCadastro>=(NOW()-INTERVAL 3 HOUR)
                AND (central_de_notificacao.statusNotificacaoAplicativo IS NULL OR central_de_notificacao.statusNotificacaoAplicativo=1 )
                AND central_de_notificacao.idTipoNotificacao=:idTipoNotificacao
                GROUP BY central_de_notificacao.idNotificacao
                ORDER BY central_de_notificacao.dataCadastro ASC
                LIMIT 1000';

        $Select = new Select;
        $Select->FullSelect($sql, "idTipoNotificacao={$idTipoNotificacao}");
        if (!empty($Select->getResult())):
            $this->Result = $Select->getResult();
        else:
            $this->Result = false;
        endif;
    }


    /**
     * Atualiza um status especifico
     * @param INTEGER $idNotificacao
     * @param INTEGER $statusEmail
     * @param INTEGER $statusSistema
     * @return BOOLEAN - True para atualizado com sucesso!
     */
    public function atualizaStatusNotificacao($idNotificacao, $statusNotificacaoAplicativo = NULL) {
        $this->Result = false;
        if(!empty($idNotificacao) && !empty($statusNotificacaoAplicativo)){
            $up = new Update;
            $up->ExeUpdate("central_de_notificacao", ['statusNotificacaoAplicativo'=>$statusNotificacaoAplicativo], "WHERE idNotificacao=:idNotificacao", "idNotificacao=$idNotificacao");
            if ($up->getResult()):
                $this->Result = true;
            endif;
        }
    }


    /**
     * DE->PARA  / INDICA A URL DE DESTINO ISSO EVITARA REBUILD NO APP CASO APAREÇA NOVAS DIREIONAMENTO
     * URL DE DIRECIONAMENTO DEPENDE DO TIPO DE NOTIFICAÇÃO
     * TIPO 1 -> PAGE LEAD
     * TIPO 3 -> PAGE LEAD
     * TIPO 10 -> NOT LINK
     */

    private function getLinkPorTipoDeNotificacao($idTipoNotificacao){
        $return = '';
        switch ($idTipoNotificacao){
            case 1 :
                $return = 'meusleads.html';
                break;
            case 3 :
                $return = 'meusleads.html';
                break;
            case 6 :
                $return = 'plantoes.html';
                break;
        }
        return $return;
    }



    public function addConfiguracaoNotificationAplicativo($idAppIdUser, $configNotificationsApp){
        $this->Result = false;
        if(!empty($idAppIdUser) && !empty($configNotificationsApp)){
            $up = new Update;
            $up->ExeUpdate("app_id_user", ['configNotificationsApp'=>$configNotificationsApp], "WHERE idAppIdUser=:idAppIdUser", "idAppIdUser=$idAppIdUser");
            if ($up->getResult()):
                $this->Result = $up->getResult();
            endif;
        }
    }


    public function checkSeUsuarioBloqueouReceberEssaNotificacaoNesseAparelho($configNotificationsApp, $tipoNotification){
        if(!empty($configNotificationsApp)){
            $config = (!empty($configNotificationsApp) ? unserialize($configNotificationsApp) : true );

            $respNotfic=true;
            foreach ($config as $key => $value){
                if($value['key']==$tipoNotification && $value['val']==0){
                    $respNotfic = false;
                }
            }
            return $respNotfic;
        }else{
            return true;
        }


    }




    /** METODO COPIADO EXATAMENTE IGUAL AO INDICA, SERA USADO AQUI PARA O CADASTRO DE UMA NOTIFICAÇÃO DIARIA DE PLANTÃO
     * FARÁ ISSO AS 06:00 DA MANHÃ TODOS OS DIAS DURANTE A EXECUÇÃO DO CRON DEPOIS OS METODOS RESPONSAVEIS ENVIARÁ NOTIFICAÇÕES DE PUSH ALERTANDO O CORRETOR QUE ELE TEM UM PLANTÃO NAQUELE DIA
     *
     *
     * Cadastra um novo registro na tabela
     * @param INTEGER $idUsuario
     * @param INTEGER $idTipoNotificacao
     * @param INTEGER $statusNotificacaoEmail - [0=nao enviar | 1=disponivel | 2=enviando | 3=enviado]
     * @param INTEGER $statusNotificacaoSistema - [0=nao notificar | 1=disponivel | 3=notificacao visualizada]
     * @param STRING $idReferencia - Usado para relacionamento fraco
     * @param STRING $referencia - Usado para informar tabla do relacionamento fraco | lead | usuario | corretora | etc.
     * @param STRING $emailPersonalizado - usado para enviar notificacao personalizada ao email do usuario
     * @param STRING $sistemaPersonalizado - Usado para mostrar notificacao personalizada ao usuario
     * @return boolean - True para cadastrado com sucesso!
     */
    public function registraNotificacao($idUsuario, $idTipoNotificacao, $statusNotificacaoEmail, $statusNotificacaoSistema, $idReferencia = NULL, $emailPersonalizado = NULL, $sistemaPersonalizado = NULL, $referencia=NULL) {

        //Validacao
        if (empty($idUsuario)):
            $this->result = ['resp' => 'error', "msg" => "Usuário Inválido"];
            return false;
        elseif (empty($idTipoNotificacao)):
            $this->result = ['resp' => 'error', "msg" => "Tipo de Notificação Inválida"];
            return false;
        endif;

        $Dados['idUsuario'] = $idUsuario;
        $Dados['idReferencia'] = $idReferencia;
        $Dados['idTipoNotificacao'] = $idTipoNotificacao;
        $Dados['dataCadastro'] = date("Y-m-d H:i:s");
        $Dados['statusNotificacaoEmail'] = $statusNotificacaoEmail;
        $Dados['statusNotificacaoSistema'] = $statusNotificacaoSistema;
        $Dados['notificacaoEmailPersonalizado'] = $emailPersonalizado;
        $Dados['notificacaoSistemaPersonalizado'] = $sistemaPersonalizado;
        $Dados['referencia'] = $referencia;


        //Insercao
        $create = new Create();
        $create->ExeCreate("central_de_notificacao", $Dados);

        if ($create->getResult()):
            $this->result = ['resp' => 'success', "msg" => "Cadastrado com Sucesso!", "idNotificacao" => $create->getResult()];
            return true;
        endif;
        $this->result = ['resp' => 'error', "msg" => "Erro ao cadastrar a notificação"];
        return false;
    }

    /** METODO DE AUXILIO PARA NOTIFICAÇÕES DE PLANTOES
     */
    public function checkSeNotificacaoJaFoiCadastrada($idUsuario, $idReferencia,  $idTipoNotificacao){

        $sql = 'SELECT central_de_notificacao.* FROM central_de_notificacao 
                WHERE DATE(central_de_notificacao.dataCadastro)= CURRENT_DATE 
                AND central_de_notificacao.idUsuario=:idUsuario 
                AND central_de_notificacao.idReferencia=:idReferencia
                AND central_de_notificacao.idTipoNotificacao=:idTipoNotificacao
                ';

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$idUsuario}&idReferencia={$idReferencia}&idTipoNotificacao={$idTipoNotificacao}");
        if (!empty($Select->getResult())):
            return $Select->getResult();
        else:
            return false;
        endif;
    }





}
