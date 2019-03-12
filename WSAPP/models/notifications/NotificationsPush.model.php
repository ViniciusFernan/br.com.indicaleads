<?php

/**
 * Usuario.model [ MODEL USUARIO ]
 * Responsável por gerenciar os usuários no Admin do sistema!
 */
class NotificationsPushModel {

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

    public function SendNotificationsNovoLead(){
        require_once ABSPATH . "/models/usuarios/Usuarios.model.php";
        require_once ABSPATH . "/models/notifications/Notifications.model.php";
        require_once ABSPATH . "/models/notifications/NotificationsGCM.model.php";

        $usuario = new UsuariosModel;
        $notifications = new NotificationsModel;
        $GCMNotification = new NotificationsGCMModel;

        $titulo = 'Novo lead';
        $mensagem = 'Você acaba de receber um novo lead!';

        $notifications->getALLCurrentNotificationsFromTipoFromAplicativo(1);
        if(!empty($notifications->getResult())) {

            foreach ($notifications->getResult() as $key => $notificacao){
                $usuario->getDispositivosDoUsuario($notificacao['idUsuario']);
                if($usuario->getResult()){
                    foreach ($usuario->getResult() as $k => $dispositivo) {
                        if (!empty($dispositivo['gcmId'])) {
                            $chavesDosDispositivosUsuarioCorrente = $dispositivo['gcmId'];

                            $notifications->atualizaStatusNotificacao($notificacao['idNotificacao'], 3);

                            $templateFrom = 'meusleads.html';
                            $idLead = (!empty($notificacao['idReferencia']) ? $notificacao['idReferencia'] : NULL);
                            $GCMNotification->sendMesagemGCMAction([$chavesDosDispositivosUsuarioCorrente], $titulo, $mensagem, $templateFrom, $idLead);
                        }
                    }

                }else{
                    echo "[SEM USUARIO A SER NOTIFICADO => NOVO LEAD ] <br/>";
                }
            }
        }else{
            echo "[SEM NOTIFICAÇÃO A SER ENVIADA => NOVO LEAD ] <br/>";
        }
    }


    public function SendNotifications10MinutosParaPerder(){
        require_once ABSPATH . "/models/usuarios/Usuarios.model.php";
        require_once ABSPATH . "/models/notifications/Notifications.model.php";
        require_once ABSPATH . "/models/notifications/NotificationsGCM.model.php";

        $usuario = new UsuariosModel;
        $notifications = new NotificationsModel;
        $GCMNotification = new NotificationsGCMModel;

        $titulo='Perda de lead em 10 minutos';
        $mensagem='voçê irá perder um lead em 10minutos';


        $notifications->getALLCurrentNotificationsFromTipoFromAplicativo(3);
        if(!empty($notifications->getResult())) {

            foreach ($notifications->getResult() as $key => $notificacao){
                $usuario->getDispositivosDoUsuario($notificacao['idUsuario']);
                if($usuario->getResult()){
                    foreach ($usuario->getResult() as $k => $dispositivo) {
                        if (!empty($dispositivo['gcmId'])) {
                            $chavesDosDispositivosUsuarioCorrente = $dispositivo['gcmId'];

                            $notifications->atualizaStatusNotificacao($notificacao['idNotificacao'], 3);

                            $templateFrom = 'meusleads.html';
                            $idLead = (!empty($notificacao['idReferencia']) ? $notificacao['idReferencia'] : NULL);
                            $GCMNotification->sendMesagemGCMAction([$chavesDosDispositivosUsuarioCorrente], $titulo, $mensagem, $templateFrom, $idLead);
                        }
                    }
                }else{
                    echo "[SEM USUARIO A SER NOTIFICADO => 10 MINUTOS ] <br/>";
                }
            }
        }else{
            echo "[SEM NOTIFICAÇÃO A SER ENVIADA => 10 MINUTOS ] <br/>";
        }

    }



    public function SendNotificationsBloqueadoPorPerderLead(){
        require_once ABSPATH . "/models/usuarios/Usuarios.model.php";
        require_once ABSPATH . "/models/notifications/Notifications.model.php";
        require_once ABSPATH . "/models/notifications/NotificationsGCM.model.php";

        $usuario = new UsuariosModel;
        $notifications = new NotificationsModel;
        $GCMNotification = new NotificationsGCMModel;

        $titulo='Bloqueado no plantão';
        $mensagem='voçê foi bloqueado  por perda excessiva de indicações, Favor procurar seu supervisor!';

        $notifications->getALLCurrentNotificationsFromTipoFromAplicativo(10);
        if(!empty($notifications->getResult())) {

            foreach ($notifications->getResult() as $key => $notificacao){
                $usuario->getDispositivosDoUsuario($notificacao['idUsuario']);
                if($usuario->getResult()){
                    foreach ($usuario->getResult() as $k => $dispositivo) {
                        if (!empty($dispositivo['gcmId'])) {
                            $chavesDosDispositivosUsuarioCorrente = $dispositivo['gcmId'];

                            $notifications->atualizaStatusNotificacao($notificacao['idNotificacao'], 3);

                            $GCMNotification->sendMesagemGCMAction([$chavesDosDispositivosUsuarioCorrente], $titulo, $mensagem, NULL, NULL );
                        }
                    }
                }else{
                    echo "[SEM USUARIO A SER NOTIFICADO => BLOQUEIO ] <br/>";
                }
            }
        }else{
            echo "[SEM NOTIFICAÇÃO A SER ENVIADA => BLOQUEIO ] <br/>";
        }
    }





}
